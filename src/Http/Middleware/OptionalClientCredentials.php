<?php

namespace derpierre65\Id\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptionalClientCredentials extends CheckClientCredentials
{
	protected bool $throwException = false;
}