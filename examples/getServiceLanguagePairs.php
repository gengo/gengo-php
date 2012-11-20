<?php

/**
 * Returns supported translation language pairs, tiers, and credit prices.
 */

require_once '../init.php';

// TODO: this example assumes you set the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

// Get an instance of an Service Client
$service = Gengo_Api::factory('service', $api_key, $private_key);

// Request the language pairs.
$service->getLanguagePair(); 

// Display server response.
echo $service->getResponseBody();

/*
 * Typical partial response:
 {"opstat":"ok","response":[
    {"lc_src":"de","lc_tgt":"en","tier":"standard","unit_price":"0.0500"},
    {"lc_src":"de","lc_tgt":"en","tier":"pro","unit_price":"0.1000"},
    {"lc_src":"de","lc_tgt":"en","tier":"ultra","unit_price":"0.1500"},
    {"lc_src":"en","lc_tgt":"de","tier":"standard","unit_price":"0.0500"},
    {"lc_src":"en","lc_tgt":"de","tier":"pro","unit_price":"0.1000"},
    {"lc_src":"en","lc_tgt":"de","tier":"ultra","unit_price":"0.1500"},
    {"lc_src":"en","lc_tgt":"de","tier":"machine","unit_price":"0.0000"},
    {"lc_src":"en","lc_tgt":"es","tier":"standard","unit_price":"0.0500"},
  ...
  ]}
 */

?>
