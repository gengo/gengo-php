<?php

/**
 * Retrieve account balance in credits.
 */

require_once '../init.php';

// TODO: this example assumes you set the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

// Get an instance of an Account Client
$account = Gengo_Api::factory('account', $api_key, $private_key);

// Request the balance.
$account->getBalance(); 

// Show the server response in depth if you need it.
echo $account->getResponseBody();

/*
 * Typical answer:
 {"opstat":"ok","response":{"credits":"100.29"}}
 */

?>
