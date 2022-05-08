<?php

namespace derpierre65\Id\Http\Middleware;

use derpierre65\Id\Exceptions\MissingScopeException;

class CheckClientCredentialsForAnyScope extends CheckCredentials
{
	protected function validateScopes($token, $scopes)
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