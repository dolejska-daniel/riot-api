<?php

//==============================dd==
//  Autoload & use statements
//==============================dd==

require __DIR__ . "/../../vendor/autoload.php";

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;


//==============================dd==
//  Default configuration
//==============================dd==

//  Your normal API key
const CFG_API_KEY    = "YOUR_API_KEY";

//  Your Tournament enabled API key, if you've got one
const CFG_TAPI_KEY   = "YOUR_TOURNAMENT_API_KEY";

//  Default region
const CFG_REGION     = Region::EUROPE_EAST;

//  Should cURL verify certificate issuer?
const CFG_VERIFY_SSL = false;


//==============================dd==
//  Library initialization.
//==============================dd==

if (CFG_API_KEY == "YOUR_API_KEY")
	die("Please change API key in the configuration file (examples/LeagueAPI/_init.php) to your own.");

$api = new LeagueAPI([
	LeagueAPI::SET_KEY              => CFG_API_KEY,
	LeagueAPI::SET_TOURNAMENT_KEY   => CFG_TAPI_KEY,
	LeagueAPI::SET_REGION           => CFG_REGION,
	LeagueAPI::SET_VERIFY_SSL       => CFG_VERIFY_SSL,
	LeagueAPI::SET_DATADRAGON_INIT  => true,
	LeagueAPI::SET_INTERIM          => true,
	LeagueAPI::SET_CACHE_RATELIMIT  => true,
	LeagueAPI::SET_CACHE_CALLS      => true,
]);