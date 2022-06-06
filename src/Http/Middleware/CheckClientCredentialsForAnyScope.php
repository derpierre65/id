<?php

namespace derpierre65\Id\Http\Middleware;

use derpierre65\Id\Exceptions\MissingScopeException;

class CheckClientCredentialsForAnyScope extends CheckClientCredentials
{
	protected function validateScopes($token, $scopes) : void
	{
		if ( in_array('*', $token->scopes) ) {
			return;
		}

		foreach ( $scopes as $scope ) {
			if ( in_array($scope, $token->scopes) ) {
				return;
			}
		}

		throw new MissingScopeException($scopes);
	}
}