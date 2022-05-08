<?php

namespace derpierre65\Id\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;

class MissingScopeException extends AuthorizationException
{
	/**
	 * The scopes that the user did not have.
	 *
	 * @var array
	 */
	protected array $scopes;

	/**
	 * Create a new missing scope exception.
	 *
	 * @param array|string $scopes
	 * @param string $message
	 *
	 * @return void
	 */
	public function __construct($scopes = [], $message = 'Invalid scope(s) provided.')
	{
		parent::__construct($message);

		$this->scopes = Arr::wrap($scopes);
	}

	/**
	 * Get the scopes that the user did not have.
	 *
	 * @return array
	 */
	public function scopes() : array
	{
		return $this->scopes;
	}
}