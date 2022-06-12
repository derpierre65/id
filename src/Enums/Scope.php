<?php

namespace derpierre65\Id\Enums;

class Scope
{
	/** allows /users/{user}/  */
	public const USER = 'user';

	/** allows /users/{user}/ without email */
	public const USER_READ = 'user:read';

	/** enables /users/{user}/ to return an email */
	public const USER_EMAIL = 'user:read:email';

	/** allows /users/{user}/betas/ to return all beta clients */
	public const USER_BETA = 'user:read:beta';

	/** allows /users/{user}/connections/ to return all user connections */
	public const USER_CONNECTIONS = 'user:read:connections';
}