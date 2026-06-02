<?php

class AdminerPermanentLoginSecret extends Adminer\Plugin
{
	private const PERMANENT_LOGIN_SECRET = 'Joyark_Adminer_Secret_2026_X7kP9mQvL2wR8tY5uZ3vB6nM9pQ2wE4rT7yU';

	public function permanentLogin(bool $create = false): string
	{
		return self::PERMANENT_LOGIN_SECRET;
	}
}

return new AdminerPermanentLoginSecret();
