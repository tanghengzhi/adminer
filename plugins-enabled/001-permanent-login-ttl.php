<?php

class AdminerPermanentLoginTtl extends Adminer\Plugin
{
	public function __construct(private int $ttlSeconds)
	{
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
					$cookie = preg_replace('/;\s*expires=[^;]*/i', '', $cookie);
					$cookie .= '; expires=' . $expires;
				}
				header('Set-Cookie: ' . $cookie, false);
			}
		});
	}
}

if (!function_exists('header_register_callback')) {
	error_log('AdminerPermanentLoginTtl is disabled: header_register_callback is not available.');
	return new class extends Adminer\Plugin {
	};
}

return new AdminerPermanentLoginTtl(365 * 24 * 60 * 60);
