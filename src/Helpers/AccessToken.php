<?php

namespace derpierre65\Id\Helpers;

use Carbon\CarbonInterface;
use derpierre65\Id\Enums\GrantType;
use Illuminate\Contracts\Support\Arrayable;

class AccessToken implements Arrayable
{
	/**
	 * oauth access token
	 */
	public string $accessToken;

	/**
	 * oauth refresh token to generate a new access token
	 */
	public string $refreshToken;

	/**
	 * access token will expire in expiresIn seconds.
	 */
	public int $expiresIn;

	/**
	 * @see GrantType
	 */
	public string $tokenType;

	/**
	 * access token will expire at expiresAt
	 */
	public CarbonInterface $expiresAt;

	public function __construct(array $data)
	{
		$this->accessToken = $data['access_token'];
		$this->refreshToken = $data['refresh_token'];
		$this->expiresIn = $data['expires_in'];
		$this->tokenType = $data['token_type'];
		$this->expiresAt = $data['expires_at'] ?? now()->addSeconds($this->expiresIn);
	}

	public function toArray() : array
	{
		return [
			'access_token' => $this->accessToken,
			'expires_in' => $this->expiresIn,
			'token_type' => $this->tokenType,
			'expires_at' => $this->expiresAt,
		];
	}

	/**
	 * Returns true if the token has expired.
	 */
	public function isExpired() : bool
	{
		return $this->expiresAt->isPast();
	}
}