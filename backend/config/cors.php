<?php
return [
	'paths' => ['api/*', 'sanctum/csrf-cookie'],
	'allowed_methods' => ['*'],
	'allowed_origins' => ['*'], // Allow all origins for testing
	'allowed_headers' => ['*'], // Allow all headers for testing
	'exposed_headers' => [],
	'max_age' => 0,
	'supports_credentials' => false,
];