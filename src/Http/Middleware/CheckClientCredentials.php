<?php

namespace derpierre65\Id\Http\Middleware;

use Closure;
use derpierre65\Id\Exceptions\MissingScopeException;
use derpierre65\Id\Helpers\JwtParser;
use Illuminate\Http\Request;
use stdClass;

class CheckClientCredentials
{
	protected bool $throwException = true;

	public function handle(Request $request, Closure $next, ...$scopes)
	{
		$token = $this->getJwtParser()->decode($request, $this->throwException);

		if ( $token ) {
			$request->attributes->set('oauth_access_token_id', $token->jti);
			$request->attributes->set('oauth_client_id', $token->aud);
			$request->attributes->set('oauth_client_first_party', $token->first_party ?? false);
			$request->attributes->set('oauth_user_id', $token->sub ? : null);
			$request->attributes->set('oauth_scopes', $token->scopes);

			$this->validateScopes($token, $scopes);
		}

		return $next($request);
	}

	private function getJwtParser() : JwtParser
	{
		return app(JwtParser::class);
	}

	protected function validateScopes(stdClass $token, array $scopes) : void
	{
		if ( in_array('*', $token->scopes) ) {
			return;
		}

		$missing = [];
		foreach ( $scopes as $scope ) {
			if ( !in_array($scope, $token->scopes) ) {
				$missing[] = $scope;
			}
		}

		if ( !empty($missing) ) {
			throw new MissingScopeException($scopes);
		}
	}
}