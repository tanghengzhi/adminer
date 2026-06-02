<?php

class AdminerPermanentLoginSecret extends Adminer\Plugin
{
	private string $permanentLoginSecret;

	public function __construct()
	{
		$secret = getenv('ADMINER_PERMANENT_LOGIN_SECRET');
		if ($secret === false) {
			throw new RuntimeException('ADMINER_PERMANENT_LOGIN_SECRET environment variable is not set in the runtime environment.');
		}

		$secret = trim($secret);
		if ($secret === '') {
			throw new RuntimeException('ADMINER_PERMANENT_LOGIN_SECRET cannot be empty. Use a secure secret with at least 32 bytes.');
		}

		$secretLength = function_exists('mb_strlen') ? mb_strlen($secret, '8bit') : strlen($secret);
		if ($secretLength < 32) {
			throw new RuntimeException('ADMINER_PERMANENT_LOGIN_SECRET is too short. Use a secure secret with at least 32 bytes.');
		}

		$this->permanentLoginSecret = $secret;
	}

	public function permanentLogin(bool $create = false): string
	{
		return $this->permanentLoginSecret;
	}
}

return new AdminerPermanentLoginSecret();
