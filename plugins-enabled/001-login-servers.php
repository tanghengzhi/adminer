<?php

require_once '/var/www/html/plugins/login-servers.php';

// Adminer plugin uses "server" as MySQL driver identifier.
$defaultDriver = 'server';
$defaultServer = getenv('ADMINER_DEFAULT_SERVER') ?: 'db';
$servers = array();

$rawConfiguredServers = getenv('ADMINER_LOGIN_SERVERS');
if ($rawConfiguredServers === false || trim($rawConfiguredServers) === '') {
	$configuredServers = array();
} else {
	$configuredServers = json_decode($rawConfiguredServers, true);
	if (json_last_error() !== JSON_ERROR_NONE) {
		error_log('Invalid ADMINER_LOGIN_SERVERS JSON: ' . json_last_error_msg());
		$configuredServers = array();
	} elseif (!is_array($configuredServers)) {
		$configuredServers = array();
	}
}

foreach ($configuredServers as $index => $server) {
	if (!is_array($server)) {
		error_log("Skip ADMINER_LOGIN_SERVERS[$index]: each item must be an object like {\"name\":\"...\",\"server\":\"...\"}.");
		continue;
	}
	$name = trim((string) ($server['name'] ?? ''));
	$serverAddress = trim((string) ($server['server'] ?? ''));
	if ($name === '' || $serverAddress === '') {
		error_log("Skip ADMINER_LOGIN_SERVERS[$index]: 'name' and 'server' are required.");
		continue;
	}
	if (isset($servers[$name])) {
		error_log("Skip ADMINER_LOGIN_SERVERS[$index]: duplicate name '$name'.");
		continue;
	}
	$servers[$name] = array(
		'server' => $serverAddress,
		'driver' => trim((string) ($server['driver'] ?? '')) ?: $defaultDriver,
	);
}

$defaultLabel = 'Default';
while (isset($servers[$defaultLabel])) {
	$defaultLabel .= ' (auto)';
}
$servers[$defaultLabel] = array(
	'server' => $defaultServer,
	'driver' => $defaultDriver,
);

return new AdminerLoginServers($servers);
