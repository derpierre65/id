<?php

namespace derpierre65\Id\Traits;

use derpierre65\Id\derpierre65Id;
use derpierre65\Id\Enums\GrantType;
use derpierre65\Id\Exceptions\OAuthTokenRequestException;
use derpierre65\Id\Helpers\AccessToken;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin derpierre65Id
 */
trait ClientCredentialsTrait
{
	protected function shouldFetchClientCredentials() : bool
	{
		return $this->getClientId() && $this->getClientSecret() && config('derpierre65-id.client_credentials.auto_generate');
	}

	protected function shouldCacheClientCredentials() : bool
	{
		return config('derpierre65-id.client_credentials.cache');
	}

	public function getClientCredentials() : ?AccessToken
	{
		if ( $this->shouldCacheClientCredentials() && $token = $this->getCachedClientCredentialsToken() ) {
			return $token;
		}

		$result = $this->getOAuthToken(null, GrantType::CLIENT_CREDENTIALS);

		if ( !$result->success() ) {
			$exception = $result->exception;

			if ( null === $exception ) {
				return null;
			}

			throw new OAuthTokenRequestException(
				'Could not fetch the OAuth credentials (OAuthTokenRequestException)',
				$exception->getRequest(),
				$result->response(),
				$exception
			);
		}

		$token = new AccessToken(
			(array) $result->data()
		);

		if ( $this->shouldCacheClientCredentials() ) {
			$this->storeClientCredentialsToken($token);
		}

		return $token;
	}

	protected function getCachedClientCredentialsToken() : ?AccessToken
	{
		$key = config('derpierre65-id.client_credentials.cache_key');

		$stored = $this->getClientCredentialsCacheRepository()->get($key);

		if ( empty($stored) ) {
			return null;
		}

		$token = new AccessToken($stored);

		if ( !$token->isExpired() ) {
			return $token;
		}

		$this->getClientCredentialsCacheRepository()->delete($key);

		return null;
	}

	protected function storeClientCredentialsToken(AccessToken $token) : void
	{
		$this->getClientCredentialsCacheRepository()->set(config('derpierre65-id.client_credentials.cache_key'), $token->toArray());
	}

	protected function clearClientCredentialsToken() : void
	{
		$this->getClientCredentialsCacheRepository()->forget(config('derpierre65-id.client_credentials.cache_key'));
	}

	protected function getClientCredentialsCacheRepository() : Repository
	{
		return Cache::store(config('derpierre65-id.client_credentials.cache_store'));
	}
}