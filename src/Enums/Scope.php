<?php

namespace derpierre65\Id\Enums;

class Scope
{
	/** allows /users/{user.id}  */
	public const USER = 'user';

	/** allows /users/{user.id} without email */
	public const USER_READ = 'user:read';

	/** enables /users/{user.id} to return an email */
	public const USER_EMAIL = 'user:email';

	/** allows /users/{user.id}/beta to return all beta clients */
	public const USER_BETA = 'user:beta';

	/** allows /users/{user.id}/connections to return all user connections */
	public const USER_CONNECTIONS = 'user:connections';
}