<?php

class AdminerPermanentLoginTtl extends Adminer\Plugin
{
	private const SECONDS_PER_YEAR = 365 * 24 * 60 * 60;

	public function __construct(private bool $enabled, private int $ttlSeconds = self::SECONDS_PER_YEAR)
	{
		if (!$this->enabled) {
			error_log('AdminerPermanentLoginTtl is disabled: header_register_callback is not available.');
			return;
		}

		header_register_callback(function (): void {
			$headers = headers_list();
			$setCookieHeaders = [];

			foreach ($headers as $header) {
				if (stripos($header, 'Set-Cookie:') !== 0) {
					continue;
				}
				$setCookieHeaders[] = trim(substr($header, strlen('Set-Cookie:')));
			}

			if ($setCookieHeaders === []) {
				return;
			}

			header_remove('Set-Cookie');
			$expires = gmdate('D, d M Y H:i:s', time() + $this->ttlSeconds) . ' GMT';

			foreach ($setCookieHeaders as $cookie) {
				if (stripos($cookie, 'adminer_permanent=') === 0) {
					$parts = array_map('trim', explode(';', $cookie));
					$nameValue = array_shift($parts);
					$attributes = [];
					foreach ($parts as $attribute) {
						if (stripos($attribute, 'expires=') === 0) {
							continue;
						}
						$attributes[] = $attribute;
					}
					$attributes[] = 'expires=' . $expires;
					$cookie = $nameValue . '; ' . implode('; ', $attributes);
				}
				header('Set-Cookie: ' . $cookie, false);
			}
		});
	}
}

return new AdminerPermanentLoginTtl(function_exists('header_register_callback'));
