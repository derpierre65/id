<?php

namespace derpierre65\Id\Traits;

use derpierre65\Id\Derpierre65Id;
use derpierre65\Id\Enums\GrantType;
use derpierre65\Id\Helpers\Result;
use InvalidArgumentException;

/**
 * @mixin Derpierre65Id
 */
trait OAuthTrait
{
	/**
	 * Generates a new authorization url to generate a code request.
	 */
	public function getAuthorizeUrl(string $responseType = 'code', array $scopes = [], string $state = null) : string
	{
		$parameters = [
			'response_type' => $responseType,
			'client_id' => $this->getClientId(),
			'redirect_uri' => $this->getRedirectUri(),
			'scope' => implode(' ', $scopes),
		];

		if ( $state !== null ) {
			$parameters['state'] = $state;
		}

		return self::OAUTH_BASE_URI.'authorize/?'.http_build_query($parameters);
	}

	public function getOAuthToken(?string $code = null, string $grantType = GrantType::AUTHORIZATION_CODE, array $scopes = []) : Result
	{
		if ( !$clientId = $this->getClientId() ) {
			throw new InvalidArgumentException('The OAuth request requires a client id to be set.');
		}
		if ( !$clientSecret = $this->getClientSecret() ) {
			throw new InvalidArgumentException('The OAuth request requires a client secret to be set.');
		}

		$parameters = [
			'grant_type' => $grantType,
			'client_id' => $clientId,
			'client_secret' => $clientSecret,
		];

		if ( $grantType === GrantType::AUTHORIZATION_CODE ) {
			$parameters['redirect_uri'] = $this->getRedirectUri();
		}

		if ( $code !== null ) {
			switch ( $grantType ) {
				case GrantType::REFRESH_TOKEN:
					$parameters['refresh_token'] = $code;
					break;
				default:
					$parameters['code'] = $code;
			}
		}

		if ( !empty($scopes) ) {
			$parameters['scope'] = $this->buildScopes($scopes);
		}

		return $this->json(self::BASE_URI.'oauth2/token', [], $parameters);
	}

	protected function buildScopes(array $scopes = []) : string
	{
		return implode(' ', $scopes);
	}

	public function isAuthenticationUri(string $uri) : bool
	{
		return str_starts_with($uri, self::OAUTH_BASE_URI)
		       || str_starts_with($uri, self::BASE_URI.'oauth2/')
		       || str_starts_with($trimmedUri = ltrim($uri, '/'), 'oauth2/')
		       || str_starts_with($trimmedUri, 'api/oauth2/');
	}
}