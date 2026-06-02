<?php

class AdminerPermanentLoginTtl extends Adminer\Plugin
{
	public function __construct(private int $ttlSeconds)
	{
		if (!function_exists('header_register_callback')) {
			return;
		}

		header_register_callback(function (): void {
			$headers = headers_list();
			$setCookieHeaders = array();

			foreach ($headers as $header) {
				if (stripos($header, 'Set-Cookie:') !== 0) {
					continue;
				}
				$setCookieHeaders[] = trim(substr($header, strlen('Set-Cookie:')));
			}

			if ($setCookieHeaders === array()) {
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

return new AdminerPermanentLoginTtl(31536000);
