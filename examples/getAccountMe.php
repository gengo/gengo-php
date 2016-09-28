<?php

/**
 * Retrieve details of authenticated user
 */

require_once '../init.php';

// TODO: this example assumes you set the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

// Get an instance of an Account Client
$account = Gengo_Api::factory('account', $api_key, $private_key);

// Request the balance.
$account->getMe();

// Show the server response in depth if you need it.
echo $account->getResponseBody();

/*
 * Typical answer:
 * {"opstat":"ok","response":{"email":"john.doe@gengo.com","full_name":"John Doe","display_name":"Johnny","language_code":"en"}}
 */
