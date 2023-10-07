<?php

namespace derpierre65\Id;

use derpierre65\Id\Helpers\Paginator;
use derpierre65\Id\Helpers\Result;
use derpierre65\Id\Traits\ClientCredentialsTrait;
use derpierre65\Id\Traits\OAuthTrait;
use derpierre65\Id\Traits\UsersTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;

class Derpierre65Id
{
	use OAuthTrait;
	use UsersTrait;
	use ClientCredentialsTrait;

	public const OAUTH_BASE_URI = 'https://id.derpierre65.dev/oauth2/';

	public const BASE_URI = 'https://api.id.derpierre65.dev';

	/**
	 * Guzzle is used to make http requests.
	 */
	protected Client $client;

	/**
	 * Client ID.
	 */
	protected ?string $clientId = null;

	/**
	 * client secret.
	 */
	protected ?string $clientSecret = null;

	/**
	 * Client redirect url.
	 */
	protected ?string $redirectUri = null;

	/**
	 * Current access token
	 */
	protected ?string $token = null;

	public function __construct()
	{
		if ( $clientId = config('derpierre65-id.client_id') ) {
			$this->setClientId($clientId);
		}
		if ( $clientSecret = config('derpierre65-id.client_secret') ) {
			$this->setClientSecret($clientSecret);
		}
		if ( $redirectUri = config('derpierre65-id.redirect_url') ) {
			$this->setRedirectUri($redirectUri);
		}

		$this->client = new Client([
			'base_uri' => self::BASE_URI,
		]);
	}

	public function get(string $path = '', array $parameters = [], Paginator $paginator = null) : Result
	{
		return $this->query('GET', $path, $parameters, $paginator, []);
	}

	public function post(string $path = '', array $parameters = [], Paginator $paginator = null) : Result
	{
		return $this->query('POST', $path, $parameters, $paginator, []);
	}

	public function patch(string $path = '', array $parameters = [], Paginator $paginator = null) : Result
	{
		return $this->query('PATCH', $path, $parameters, $paginator, []);
	}

	public function put(string $path = '', array $parameters = [], Paginator $paginator = null) : Result
	{
		return $this->query('PUT', $path, $parameters, $paginator, []);
	}

	public function delete(string $path = '', array $parameters = [], Paginator $paginator = null) : Result
	{
		return $this->query('DELETE', $path, $parameters, $paginator, []);
	}

	public function json(string $path = '', array $parameters = [], array $jsonParameters = [], Paginator $paginator = null) : Result
	{
		return $this->query('POST', $path, $parameters, $paginator, [
			RequestOptions::JSON => $jsonParameters,
		]);
	}

	protected function query(string $method = 'GET', string $path = '', array $parameters = [], Paginator $paginator = null, array $options = []) : Result
	{
		// if ( null !== $paginator ) {
		// TODO add paginator
		// }

		if ( !$this->isAuthenticationUri($path) && !$this->getToken() && $this->shouldFetchClientCredentials() ) {
			$token = $this->getClientCredentials();
			if ( null === $token ) {
				throw new InvalidArgumentException('Invalid Client token.');
			}

			$this->setToken($token->accessToken);
		}

		try {
			$headers = [
				'Client-ID' => $this->getClientId(),
				'Accept' => 'application/json',
			];
			if ( $this->getToken() ) {
				$headers['Authorization'] = 'Bearer '.$this->getToken();
			}

			$response = $this->client->request($method, $path, array_merge($options, [
				RequestOptions::HEADERS => $headers,
				RequestOptions::QUERY => $parameters,
			]));
			$result = new Result($response, null);
		} catch ( RequestException $exception ) {
			$result = new Result($exception->getResponse(), $exception);
		} catch ( GuzzleException $exception ) {
			$result = new Result(null, $exception);
		}

		// TODO maybe check the error message
		if ( $this->shouldCacheClientCredentials() && $result->status === 401 ) {
			$this->clearClientCredentialsToken();
		}

		$this->setToken(null);

		return $result;
	}

	//<editor-fold desc="Setters/Getters">
	public function getClientId() : ?string
	{
		return $this->clientId;
	}

	public function setClientId(?string $clientId) : self
	{
		$this->clientId = $clientId;

		return $this;
	}

	public function getClientSecret() : ?string
	{
		return $this->clientSecret;
	}

	public function setClientSecret(?string $clientSecret) : self
	{
		$this->clientSecret = $clientSecret;

		return $this;
	}

	public function getRedirectUri() : ?string
	{
		return $this->redirectUri;
	}

	public function setRedirectUri(?string $redirectUri) : self
	{
		$this->redirectUri = $redirectUri;

		return $this;
	}

	public function getToken() : ?string
	{
		return $this->token;
	}

	public function setToken(?string $token) : self
	{
		$this->token = $token;

		return $this;
	}
	//</editor-fold>
}