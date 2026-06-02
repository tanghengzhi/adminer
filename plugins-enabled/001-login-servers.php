<?php

require_once '/var/www/html/plugins/login-servers.php';

// Adminer plugin uses "server" as MySQL driver identifier.
$defaultDriver = 'server';
$defaultServer = getenv('ADMINER_DEFAULT_SERVER') ?: 'db';
$servers = array(
	'Default' => array(
		'server' => $defaultServer,
		'driver' => $defaultDriver,
	),
);

$rawConfiguredServers = getenv('ADMINER_LOGIN_SERVERS') ?: '[]';
$configuredServers = json_decode($rawConfiguredServers, true);
if ($configuredServers === null && json_last_error() !== JSON_ERROR_NONE) {
	error_log('Invalid ADMINER_LOGIN_SERVERS JSON: ' . json_last_error_msg());
	$configuredServers = array();
}
if (is_array($configuredServers)) {
	foreach ($configuredServers as $server) {
		if (!is_array($server)) {
			continue;
		}
		$name = trim((string) ($server['name'] ?? ''));
		$host = trim((string) ($server['server'] ?? ''));
		if ($name === '' || $host === '') {
			continue;
		}
		$servers[$name] = array(
			'server' => $host,
			'driver' => trim((string) ($server['driver'] ?? '')) ?: $defaultDriver,
		);
	}
}

return new AdminerLoginServers($servers);
