<?php

namespace derpierre65\Id\Providers;

use derpierre65\Id\Derpierre65Id;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(
			dirname(__DIR__).'/../config/derpierre65-id.php',
			'derpierre65-id'
		);

        $this->app->singleton(Derpierre65Id::class, function () {
            return new Derpierre65Id();
        });
	}
}