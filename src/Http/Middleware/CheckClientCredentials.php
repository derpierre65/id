<?php

namespace derpierre65\Id\Http\Middleware;

use derpierre65\Id\Exceptions\MissingScopeException;
use stdClass;

class CheckClientCredentials extends CheckCredentials
{
	protected function validateScopes(stdClass $token, array $scopes)
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