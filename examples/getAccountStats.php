<?php

/**
 *	Retrieves account stats, such as credits spend and date of subscription.
 */

require_once '../init.php';

// TODO: this example assumes you set the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

// Get an instance of an Account Client
$account = Gengo_Api::factory('account', $api_key, $private_key);

// Actually requests the stats.
$account->getStats(); 

// Display server response.
echo $account->getResponseBody();

/*
 * Typical answer: credits spent and date of subscription (as a timestamp).
 {"opstat":"ok","response":{"credits_spent":"1095.95","user_since":1234089500}}
 */

?>
