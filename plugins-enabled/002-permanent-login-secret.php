<?php

class AdminerPermanentLoginSecret extends Adminer\Plugin
{
	private string $permanentLoginSecret;

	public function __construct()
	{
		$secret = getenv('ADMINER_PERMANENT_LOGIN_SECRET');
		$secret = is_string($secret) ? trim($secret) : '';

		if ($secret === '') {
			throw new RuntimeException('Missing ADMINER_PERMANENT_LOGIN_SECRET environment variable.');
		}

		$this->permanentLoginSecret = $secret;
	}

	public function permanentLogin(bool $create = false): string
	{
		return $this->permanentLoginSecret;
	}
}

return new AdminerPermanentLoginSecret();
