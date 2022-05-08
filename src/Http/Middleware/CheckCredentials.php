<?php

namespace derpierre65\Id\Http\Middleware;

use Closure;
use derpierre65\Id\Helpers\JwtParser;
use Illuminate\Http\Request;
use stdClass;

abstract class CheckCredentials
{
	public function handle(Request $request, Closure $next, ...$scopes)
	{
		$token = $this->getJwtParser()->decode($request);

		$request->attributes->set('oauth_access_token_id', $token->jti);
		$request->attributes->set('oauth_client_id', $token->aud);
		$request->attributes->set('oauth_client_first_party', $token->first_party ?? false);
		$request->attributes->set('oauth_user_id', $token->sub ? : null);
		$request->attributes->set('oauth_scopes', $token->scopes);

		$this->validateScopes($token, $scopes);

		return $next($request);
	}

	private function getJwtParser() : JwtParser
	{
		return app(JwtParser::class);
	}

	abstract protected function validateScopes(stdClass $token, array $scopes);
}
