<?php

require_once '/var/www/html/plugins/login-servers.php';

$defaultServer = getenv('ADMINER_DEFAULT_SERVER') ?: 'db';
$servers = array(
	'Default' => array(
		'server' => $defaultServer,
		'driver' => 'server',
	),
);

$configuredServers = json_decode(getenv('ADMINER_LOGIN_SERVERS') ?: '[]', true);
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
			'driver' => trim((string) ($server['driver'] ?? '')) ?: 'server',
		);
	}
}

return new AdminerLoginServers($servers);
