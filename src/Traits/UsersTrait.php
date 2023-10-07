<?php

namespace derpierre65\Id\Traits;

use derpierre65\Id\Derpierre65Id;
use derpierre65\Id\Helpers\Result;

/**
 * @mixin Derpierre65Id
 */
trait UsersTrait
{
	/**
	 * Returns the user object of the requesters account.
	 * Will return the object without an email.
	 * Optionally the user:email can be added which returns the object with an email.
	 *
	 * Root scope: user
	 * Required Scope: user:read
	 * Optionally Scope: user:email
	 */
	public function getAuthenticatedUser() : Result
	{
		return $this->get(self::BASE_URI.'/v1/users/@me');
	}
}