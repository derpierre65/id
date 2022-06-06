<?php

namespace derpierre65\Id\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use stdClass;
use Throwable;

class JwtParser
{
	public const ALLOWED_ALGORITHMS = 'RS256';

	/**
	 * @throws AuthenticationException
	 */
	public function decode(Request $request, bool $throwException = true) : ?stdClass
	{
		JWT::$leeway = 60;

		try {
			return JWT::decode(
				$request->bearerToken(),
				new Key($this->getOauthPublicKey(), self::ALLOWED_ALGORITHMS)
			);
		} catch ( Throwable $exception ) {
			if ( $throwException ) {
				throw new AuthenticationException();
			}
		}

		return null;
	}

	public function getOauthPublicKey() : string
	{
		return file_get_contents(config('derpierre65-id.public_key') ?? __DIR__.'/../../oauth-public.key');
	}
}