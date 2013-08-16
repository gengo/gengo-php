<?php

/**
 * Retrieve an array of preferred translators by langs and tier
 */

require_once '../init.php';

// TODO: this example assumes you set the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'our-private-api-key';

// Get an instance of an Account Client
$account = Gengo_Api::factory('account', $api_key, $private_key);

// Request the balance.
$account->getPreferredTranslators(); 

// Show the server response in depth if you need it.
echo $account->getResponseBody();

/*
 * Typical answer:
 {"opstat":"ok","response":[
    {
    "lc_src" : "en",
    "lc_tgt" : "ja",
    "tier" : "standard",
    "translators" : 
        [
            {"id" : 8596, "number_of_jobs" : 5, "last_login" : 1375824155},
            {"id" : 24123, "number_of_jobs" : 2, "last_login" : 1372822132}
        ]
    },
    {
    "lc_src" : "ja",
    "lc_tgt" : "en",
    "tier" : "pro",
    "translators" : 
        [
            {"id" : 14765, "number_of_jobs" : 10, "last_login" : 1375825234},
            {"id" : 3627, "number_of_jobs" : 1, "last_login" : 1372822132}
        ]
    }
  ]}
 */

?>
