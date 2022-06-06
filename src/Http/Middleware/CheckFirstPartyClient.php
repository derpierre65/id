<?php

namespace derpierre65\Id\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class CheckFirstPartyClient
{
	/**
	 * @throws AuthenticationException
	 */
	public function handle(Request $request, Closure $next, ...$scopes)
	{
		if ( !$request->attributes->get('oauth_client_first_party') ) {
			throw new AuthenticationException('Client is not a first party client.');
		}

		return $next($request);
	}
}