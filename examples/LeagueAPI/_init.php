<?php

/********************************d*d**
 *  Autoload & use statements
 *************************************/

require __DIR__ . "/../../vendor/autoload.php";

use RiotAPI\RiotAPI;
use RiotAPI\Definitions\Region;


// ==============================d=d==
//  Default configuration
// ==============================d=d==

//  Your normal API key
const CFG_API_KEY    = "YOUR_API_KEY";

//  Your Tournament enabled API key, if you've got one
const CFG_TAPI_KEY   = "YOUR_TOURNAMENT_API_KEY";

//  Default region
const CFG_REGION     = Region::EUROPE_EAST;

//  Should cURL verify certificate issuer?
const CFG_VERIFY_SSL = false;


// ==============================d=d==
//  Library initialization.
// ==============================d=d==

if (CFG_API_KEY == "YOUR_API_KEY")
	die("Please change API key in the configuration file (_init.php) to your own.");

$api = new RiotAPI([
	RiotAPI::SET_KEY            => CFG_API_KEY,
	RiotAPI::SET_TOURNAMENT_KEY => CFG_TAPI_KEY,
	RiotAPI::SET_REGION         => CFG_REGION,
	RiotAPI::SET_VERIFY_SSL     => CFG_VERIFY_SSL,
]);