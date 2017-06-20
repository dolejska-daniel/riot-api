# RiotAPI PHP7 wrapper

> Version v0.3

[![build status](https://gitlab.dolejska.me/dolejskad/riot-api/badges/master/build.svg)](https://gitlab.dolejska.me/dolejskad/riot-api/commits/master)
[![coverage report](https://gitlab.dolejska.me/dolejskad/riot-api/badges/master/coverage.svg)](https://gitlab.dolejska.me/dolejskad/riot-api/commits/master)
[![GitHub release](https://img.shields.io/github/release/dolejska-daniel/riot-api.svg)](https://github.com/dolejska-daniel/riot-api)
[![GitHub pre release](https://img.shields.io/github/release/dolejska-daniel/riot-api/all.svg?label=pre%20release)](https://github.com/dolejska-daniel/riot-api)
[![Packagist](https://img.shields.io/packagist/v/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)
[![Packagist](https://img.shields.io/packagist/l/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)

# Table of Contents

1. [Introduction](#introduction)
2. [League of Legends API](#league-of-legends-api)
	1. [Endpoint versions](#endpoint-versions)
	2. [Initializing the library](#initializing-the-library)
	3. [Usage example](#usage-example)
3. [DataDragon API](#datadragon-api)

# Introduction

Welcome to the RiotAPI PHP7 library repo! The goal of this library is to create easy-to-use
library for anyone who might need one. This is fully object oriented API wrapper for
League of Legends' API. A small DataDragon API is included.

Here are some handy features:

- **Rate limit caching** and limit exceeding prevention.
- **Call caching**, enabling the library to re-use already fetched data within short timespan
- **Objects everywhere**! API calls return data in special objects.
- **Interim mode** support, you are going to be able to use the API the same way
whether your key is in interim mode or not (meaning you won't need to change anything
when you jump to production).

I would be grateful for any feedback - so if you can give me any, just do it! Also feel free
to send pull requests if you find anything that is worth improving!

Please, read on :)

# League of Legends API

## Endpoint versions

Below you can find table of implemented API endpoints and the version in which they are currently implemented.

| Endpoint         | Status |
| ---------------- | ------ |
| Champion         | ![Champion endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Champion Mastery | ![Champion Mastery endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| League           | ![League endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Masteries        | ![Masteries endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Match            | ![Match endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Runes            | ![Runes endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Spectator        | ![Spectator endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Static Data      | ![Static Data endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Stats            | ![Stats endpoint implemented version](https://img.shields.io/badge/implemented_version-removed-red.svg) |
| Status           | ![Status endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Summoner         | ![Summoner endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Tournament       | ![Tournament endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Tournament Stub  | ![Tournament Stub endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |

## Initializing the library

How to begin?

```php
//  Include all required files
require_once __DIR__  . "/vendor/autoload.php";

use RiotAPI\Definition\Region;
use RiotAPI\RiotAPI;

//  Initialize the library
$api = new RiotAPI([
	//  Your API key, you can get one at https://developer.riotgames.com/
	RiotAPI::SET_KEY    => 'YOUR_RIOT_API_KEY',
	//  Target region (you can change it during lifetime of the library instance)
	RiotAPI::SET_REGION => Region::EUROPE_EAST,
]);

//  And now you are ready to rock!
$ch = $api->getChampion(61); // Orianna <3
```

But you can do more than that - and it stays easy. 

```php
//  Initialize the library with more settings
$api = new RiotAPI([
	//  Your API key, you can get one at https://developer.riotgames.com/
	RiotAPI::SET_KEY             => 'YOUR_RIOT_API_KEY',
	
	//  Your Tournament API key, you can get one at https://developer.riotgames.com/ by submitting your application
	RiotAPI::SET_TOURNAMENT_KEY  => 'YOUR_RIOT_API_KEY',
	
	//  This will come in handy while building the app in the interim mode
	RiotAPI::SET_INTERIM         => true,
	
	//  Target region (you can change it during lifetime of the library)
	RiotAPI::SET_REGION          => Region::EUROPE_EAST,
	
	//  Whether or not to cache keys' rate limits and prevent exceeding the rate limit
	RiotAPI::SET_CACHE_RATELIMIT => true,
	
	//  Per-key specified limits, always in format $timeInterval => $callLimit
	RiotAPI::SET_RATELIMITS      => [
		'YOUR_RIOT_API_KEY' => [
			IRateLimitControl::INTERVAL_10S => 10,   // 10 calls per 10 seconds maximum
			IRateLimitControl::INTERVAL_10M => 500,  // 500 calls per 10 minutes maximum
			IRateLimitControl::INTERVAL_1H  => 3000, // 3000 calls per 1 hour maximum
		],
	],
]);
```

And there is a lot more what you can set when initializing the library, here is a complete list:

| Library settings key | Value | Description |
| -------------------- | ----- | ----------- |
| `RiotAPI::SET_REGION` | `Region::EUROPE_EAST`, `Region::EUROPE_WEST`, `Region::NORTH_AMERICA`, … | ***Required.*** Used to specify, to which endpoint calls are going to be made. |
| `RiotAPI::SET_KEY` | `string` | ***Required.*** Option to specify your _API key_. |
| `RiotAPI::SET_TOURNAMENT_KEY` | `string` | Option to specify your _tournament API key_. |
| `RiotAPI::SET_VERIFY_SSL` | `bool` | Use this option to disable SSL verification. Useful when testing on localhost. Shoul not be used in production. |
| `RiotAPI::SET_KEY_INCLUDE_TYPE` | `RiotAPI::KEY_AS_QUERY_PARAM`, `RiotAPI::KEY_AS_HEADER` | This option determines how is API key going to be included in the requests (by default `RiotAPI::KEY_AS_HEADER`). |
| `RiotAPI::SET_INTERIM` | `bool` | By specifying this, you tell the library to use interim-only endpoints (eg. tournament calls will be sent to stub endpoints). |
| `RiotAPI::SET_CACHE_RATELIMIT` | `bool` | This option tells the library to take care of not exceeding your API key's rate limit by counting the requests (you should also set desired limits using `RiotAPI::SET_RATELIMITS` or `defaults` will be used). |
| `RiotAPI::SET_RATELIMITS` | `array` | Option to specify per-key API call rate limits. |
| `RiotAPI::SET_CACHE_CALLS` | `bool` | This option tells the library to cache fetched data from API and to try to re-use already fetched data (you should also set option `RiotAPI::SET_CACHE_CALLS_LENGTH` to specify for how long should fetched data be stored in cache). |
| `RiotAPI::SET_CACHE_CALLS_LENGTH` | `int` | Option to specify how log should fetched data from API be saved in cache. |
| `RiotAPI::SET_CACHE_PROVIDER` | `RiotAPI::CACHE_PROVIDER_FILE`, `RiotAPI::CACHE_PROVIDER_MEMCACHED`, `ICacheProvider` | Using this option you can select from our cache providers or event provide your own. It must implement `ICacheProvider` interface. |
| `RiotAPI::SET_CACHE_PROVIDER_PARAMS` | `array` | These are parameters, that will be passed to the CacheProvider on it's initialization. |

## Usage example

Working with RiotAPI can not be easier, just watch how to fetch summoner information
based on summoner's name:

```php
//  ...initialization...

//  this fetches the summoner data and returns SummonerDto object
$summoner = $api->getSummonerByName('I am TheKronnY');

echo $summoner->id;             //  30904166
echo $summoner->name;           //  I am TheKronnY
echo $summoner->summonerLevel;  //  30

print_r($summoner->getData());  //  Or array of all the data
/* Array
 * (
 *    [id] => 30904166
 *    [name] => I am TheKronnY
 *    [profileIconId] => 540
 *    [summonerLevel] => 30
 *    [revisionDate] => 1484850969000
 * )
 */
```

..or how to fetch a static champion data?

```php
//  ...initialization...

//  this fetches the champion data and returns StaticChampionDto object
$champion = $api->getStaticChampion(61);

echo $champion->name;  //  Orianna
echo $champion->title; //  the Lady of Clockwork

print_r($champion->getData());  //  Or array of all the data
/* Array
 * (
 *    [id] => 61
 *    [name] => "Orianna"
 *    [key] => "Orianna"
 *    [title] => "the Lady of Clockwork"
 * )
 */
```

## DataDragon API

How easy it is to work with images? For instance, to get splash image of Orianna?

`echo DataDragonAPI::getChampionSplashO($api->getStaticChampion(61));`, that easy.

Want to know more? _TBA_

