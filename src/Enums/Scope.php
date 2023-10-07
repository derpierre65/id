<?php

namespace derpierre65\Id\Enums;

class Scope
{
	/** allows /v1/users/{user}/  */
	public const USER = 'user';

	/** allows /v1/users/{user}/ without email */
	public const USER_READ = 'user:read';

	/** enables /v1/users/{user}/ to return an email */
	public const USER_EMAIL = 'user:read:email';

	/** allows /v1/users/{user}/betas/ to return all beta clients */
	public const USER_BETA = 'user:read:beta';

	/** allows /v1/users/{user}/connections/ to return all user connections */
	public const USER_CONNECTIONS = 'user:read:connections';
}