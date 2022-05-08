<?php

return [
	/*
     * The Client ID to use for requests.
     */
	'client_id' => env('DERPIERRE65_CLIENT_ID', ''),

	/*
     * The Client Secret to use for OAuth requests.
     */
	'client_secret' => env('DERPIERRE65_CLIENT_SECRET', ''),

	/*
     * The Redirect URI to use for generating OAuth authorization.
     */
	'redirect_url' => env('DERPIERRE65_REDIRECT_URI', ''),

	/*
	 * Determine the path to the oauth public key.
	 * Set it to null to use the delivered public key.
	 */
	'public_key' => null,

	'client_credentials' => [
		/*
		 * The package will attempt to generate a Client Access Token for requests without a token.
		 * NOTICE: This will only be enabled if a Client ID and Client Secret have been specified.
		 */
		'auto_generate' => true,

		/*
		 * Enable caching the Client Access Token to minimize workload.
		 */
		'cache' => true,

		/*
		 * The cache key to use for storing information.
		 */
		'cache_key' => 'derpierre65-id-client-credentials',

		/*
		 * The cache store to use for storing Client Credentials.
		 */
		'cache_store' => null,
	],
];