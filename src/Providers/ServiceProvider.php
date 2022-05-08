<?php

namespace derpierre65\Id\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(
			dirname(__DIR__).'/../config/derpierre65-id.php',
			'derpierre65-id'
		);
	}
}