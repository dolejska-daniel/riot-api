# RiotAPI PHP7 wrapper

> Version v0.6

[![Build Status](https://travis-ci.org/dolejska-daniel/riot-api.svg?branch=master)](https://travis-ci.org/dolejska-daniel/riot-api)
[![Test Coverage](https://codeclimate.com/github/dolejska-daniel/riot-api/badges/coverage.svg)](https://codeclimate.com/github/dolejska-daniel/riot-api/coverage)
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
	4. [Endpoints and functions](#endpoints-and-functions)
		1. [Champion](#champion)
		2. [Champion Mastery](#champion-mastery)
		3. [League](#league)
		4. [Masteries](#masteries)
		5. [Match](#match)
		6. [Runes](#runes)
		7. [Spectator](#spectator)
		8. [Static Data](#static-data)
		9. [Status](#status)
		10. [Summoner](#summoner)
		11. [Tournament & Tournament Stub](#tournament-tournament-stub)
	5. [Cache providers](#cache-providers)
	6. [Rate limiting](#rate-limiting)
	7. [Call caching](#call-caching)
	8. [StaticData linking](#staticdata-linking)
	9. [Extensions](#extensions)
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
- **Object extensions** - you can create your own extensions for any ApiObject and use custom
functions defined in these extensions.

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

use RiotAPI\RiotAPI;
use RiotAPI\Definitions\Region;

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

And there is a lot more what you can set when initializing the library, here is a complete list:

| Library settings key | Value | Description |
| -------------------- | ----- | ----------- |
| `RiotAPI::SET_REGION` | `Region::EUROPE_EAST`, `Region::EUROPE_WEST`, `Region::NORTH_AMERICA`, … | ***Required.*** Used to specify, to which endpoint calls are going to be made. |
| `RiotAPI::SET_KEY` | `string` | ***Required.*** Option to specify your _API key_. |
| `RiotAPI::SET_TOURNAMENT_KEY` | `string` | Option to specify your _tournament API key_. |
| `RiotAPI::SET_VERIFY_SSL` | `bool` `default true` | Use this option to disable SSL verification. Useful when testing on localhost. Shoul not be used in production. |
| `RiotAPI::SET_KEY_INCLUDE_TYPE` | `RiotAPI::KEY_AS_QUERY_PARAM`, `RiotAPI::KEY_AS_HEADER` | This option determines how is API key going to be included in the requests (by default `RiotAPI::KEY_AS_HEADER`). |
| `RiotAPI::SET_INTERIM` | `bool` `default false` | By specifying this, you tell the library to use interim-only endpoints (eg. tournament calls will be sent to stub endpoints). |
| `RiotAPI::SET_CACHE_RATELIMIT` | `bool` `default false` | This option tells the library to take care of not exceeding your API key's rate limit by counting the requests (you should also set desired limits using `RiotAPI::SET_RATELIMITS` or `defaults` will be used). |
| `RiotAPI::SET_RATELIMITS` | `array` | Option to specify per-key API call rate limits. |
| `RiotAPI::SET_CACHE_CALLS` | `bool` `default false` | This option tells the library to cache fetched data from API and to try to re-use already fetched data (you should also set option `RiotAPI::SET_CACHE_CALLS_LENGTH` to specify for how long should fetched data be stored in cache). |
| `RiotAPI::SET_CACHE_CALLS_LENGTH` | `int` `default 60` | Option to specify how log should fetched data from API be saved in cache. |
| `RiotAPI::SET_CACHE_PROVIDER` | `RiotAPI::CACHE_PROVIDER_FILE`, `RiotAPI::CACHE_PROVIDER_MEMCACHED`, `ICacheProvider` | Using this option you can select from our cache providers or even provide your own.  See [cache providers](#cache-providers) for more information. |
| `RiotAPI::SET_CACHE_PROVIDER_PARAMS` | `array` | These are parameters, that will be passed to the CacheProvider on it's initialization. |
| `RiotAPI::SET_EXTENSIONS` | `array` | This option contains extensions for any ApiObject. See [extensions](#extensions) for more information. |

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

You can get more details about endpoints and functions in
[endpoints and functions](#endpoints-and-functions) section.

## Endpoints and functions

### Champion ![Champion endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getChampions` | `bool $only_free_to_play = null` | [`Objects\ChampionListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionListDto.php) |
| `getChampionById` | `int $champion_id` | [`Objects\ChampionDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionDto.php) |

### Champion Mastery ![Champion Mastery endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getChampionMastery` | `int $summoner_id`, `int $champion_id` | [`Objects\ChampionMasteryDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionMasteryDto.php) |
| `getChampionMasteries` | `int $summoner_id` | [`Objects\ChampionMasteryDto[]`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionMasteryDto.php) |
| `getChampionMasteryScore` | `int $summoner_id` | `int` |

### League ![League endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getLeaguesForSummoner` | `int $summoner_id` | [`Objects\LeagueListDto[]`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LeagueListDto.php) |
| `getLeaguePositionsForSummoner` | `int $summoner_id` | [`Objects\LeaguePositionDto[]`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LeaguePositionDto.php) |
| `getLeagueChallenger` | `string $game_queue_type` | [`Objects\LeagueListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LeagueListDto.php) |
| `getLeagueMaster` | `string $game_queue_type` | [`Objects\LeagueListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LeagueListDto.php) |

### Masteries ![Masteries endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getMasteriesBySummoner` | `int $summoner_id` | [`Objects\MasteryPagesDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MasteryPagesDto.php) |

### Match ![Match endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getMatch` | `int $match_id` | [`Objects\MatchDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchDto.php) |
| `getMatchByTournamentCode` | `int $match_id`, `string $tournament_code` | [`Objects\MatchDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchDto.php) |
| `getMatchIdsByTournamentCode` | `string $tournament_code` | `int[]` |
| `getMatchlistByAccount` | `int $account_id` | [`Objects\MatchlistDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchlistDto.php) |
| `getRecentMatchlistByAccount` | `int $account_id` | [`Objects\MatchlistDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchlistDto.php) |
| `getMatchTimeline` | `int $match_id` | [`Objects\MatchTimelineDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchTimelineDto.php) |

### Runes ![Runes endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getRunesBySummoner` | `int $summoner_id` | [`Objects\RunePagesDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/RunePagesDto.php) |

### Spectator ![Spectator endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getCurrentGameInfo` | - | [`Objects\CurrentGameInfo`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/CurrentGameInfo.php) |
| `getFeaturedGames` | - | [`Objects\FeaturedGames`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/FeaturedGames.php) |

### Static Data ![Static Data endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getStaticChampions` | `string $locale = null`, `string $version = null`, `bool $data_by_id = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticChampionListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticChampionListDto.php) |
| `getStaticChampion` | `int $champion_id`, `string $locale = null`, `string $version = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticChampionDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticChampionDto.php) |
| `getStaticItems` | `string $locale = null`, `string $version = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticItemListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticItemListDto.php) |
| `getStaticItem` | `bool $item_id`, `string $locale = null`, `string $version = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticItemDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticItemDto.php) |
| `getStaticLanguageStrings` | `string $locale = null`, `string $version = null` | [`StaticData\StaticLanguageStringsDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticLanguageStringsDto.php) |
| `getStaticLanguages` | - | `string[]` |
| `getStaticMaps` | `string $locale = null`, `string $version = null` | [`StaticData\StaticMapDataDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticMapDataDto.php) |
| `getStaticMasteries` | `string $locale = null`, `string $version = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticMasteryListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticMasteryListDto.php) |
| `getStaticMastery` | `bool $mastery_id = null`, `string $locale = null`, `string $version = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticMasteryDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticMasteryDto.php) |
| `getStaticProfileIcons` | `string $locale = null`, `string $version = null` | [`StaticData\StaticProfileIconDataDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticProfileIconDataDto.php) |
| `getStaticRealm` | - | [`StaticData\StaticRealmDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticRealmDto.php) |
| `getStaticRunes` | `string $locale = null`, `string $version = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticRuneListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticRuneListDto.php) |
| `getStaticRune` | `int $rune_id`, `string $locale = null`, `string $version = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticRuneDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticRuneDto.php) |
| `getStaticSummonerSpells` | `string $locale = null`, `string $version = null`, `bool $data_by_id = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticSummonerSpellListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticSummonerSpellListDto.php) |
| `getStaticSummonerSpell` | `int $summoner_spell_id`, `string $locale = null`, `string $version = null`, `string`&#124;`string[] $tags = null` | [`StaticData\StaticSummonerSpellDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/StaticData/StaticSummonerSpellDto.php) |
| `getStaticVersions` | - | `string[]` |

### Status ![Status endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getStatusData` | - | [`Objects\ShardStatus`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ShardStatus.php) |

### Summoner ![Summoner endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getSummoner` | `int $summoner_id` | [`Objects\SummonerDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/SummonerDto.php) |
| `getSummonerByName` | `string $summoner_name` | [`Objects\SummonerDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/SummonerDto.php) |
| `getSummonerByAccount` | `int $account_id` | [`Objects\SummonerDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/SummonerDto.php) |

### Tournament ![Tournament endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) & Tournament Stub ![Tournament Stub endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `createTournamentCodes` | `int $tournament_id`, `int $count`, [`Objects\TournamentCodeParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentCodeParameters.php) `$parameters` | `string[]` |
| `editTournamentCode` | `string $tournament_code`, [`Objects\TournamentCodeUpdateParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentCodeUpdateParameters.php) `$parameters` | - |
| `getTournamentCodeData` | `string $tournament_code` | [`Objects\TournamentCodeDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentCodeDto.php) |
| `createTournamentProvider` | [`Objects\ProviderRegistrationParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ProviderRegistrationParameters.php) `$parameters` | `int` |
| `createTournament` | [`Objects\TournamentRegistrationParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentRegistrationParameters.php) `$parameters` | `int` |
| `getTournamentLobbyEvents` | `string $tournament_code` | [`Objects\LobbyEventDtoWrapper`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LobbyEventDtoWrapper.php) |

## Cache providers

Cache providers are responsible for keeping data of [rate limiting](#rate-limiting)
and [call caching](#call-caching) within instances of the library. This feature
is automatically enabled, when any of previously mentioned features is used.

When using this feature, you can set `RiotAPI::SET_CACHE_PROVIDER` to any class,
thought it has to implement `Objects\ICacheProvider` interface. By using `RiotAPI::SET_CACHE_PROVIDER_PARAMS`
option, you can pass any variables to the cache provider.

| Variable | Data type | Possible values |
| -------- | --------- | --------------- |
| `RiotAPI::SET_CACHE_PROVIDER` | `ICacheProvider` | `RiotAPI::CACHE_PROVIDER_FILE`, `RiotAPI::CACHE_PROVIDER_MEMCACHED`, `ICacheProvider` |
| `RiotAPI::SET_CACHE_PROVIDER_PARAMS` | `array` | _see example below_ |

**Library initialization**:
```php
use RiotAPI\RiotAPI;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_CACHE_PROVIDER        => $CACHE_PROVIDER_CLASS,
	RiotAPI::SET_CACHE_PROVIDER_PARAMS => [
		$PARAM1,
		$PARAM2,
		$PARAM3,
	],
	// ...
]);
```

**Built-in providers**:

__`RiotAPI::CACHE_PROVIDER_FILE` parameters__:

| Parameter name | Data type | Defaults | Description |
| -------------- | --------- | -------- | ----------- |
| `directory path` | `string` | `__DIR__ . '/cache/'` | this path will be used to store cache files |

__`RiotAPI::CACHE_PROVIDER_MEMCACHED` parameters__:

| Parameter name | Data type | Defaults | Description |
| -------------- | --------- | -------- | ----------- |
| `server list` | `array` | `[ '127.0.0.1', 11211 ]` | this array contains memcached servers to connect to |

`server list` example:
```php
$serverList = [
	[ $SERVER_IP1, $SERVER_PORT1 ],
	[ $SERVER_IP2, $SERVER_PORT2 ],
	[ $SERVER_IP3, $SERVER_PORT3 ],
];
```

## Rate limiting

This clever feature will easily prevent exceeding your pre-set call limits. In order
to enable this feature, you have to set `RiotAPI::SET_CACHE_RATELIMIT` to `true`. If you
won't provide `RiotAPI::SET_RATELIMITS` as well, then default development ratelimits will
be used (10/10s, 500/600s).

| Variable | Data type | Possible values |
| -------- | --------- | --------------- |
| `RiotAPI::SET_CACHE_RATELIMIT` | `bool` | `true`, `false` |
| `RiotAPI::SET_RATELIMITS` | `array` | _see example below_ |
| `TIME_INTERVAL` | `int` | `IRateLimitControl::INTERVAL_1S`, `IRateLimitControl::INTERVAL_10S`, `IRateLimitControl::INTERVAL_10M`, `IRateLimitControl::INTERVAL_1H` |
| `MAXIMUM_NUMBER_OF_CALLS` | `int` | _maximum number of calls_ |

**Library initialization**:
```php
use RiotAPI\RiotAPI;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_CACHE_RATELIMIT => true,
	RiotAPI::SET_RATELIMITS => [
		$YOUR_RIOT_API_KEY => [
			$TIME_INTERVAL => $MAXIMUM_NUMBER_OF_CALLS,
			$TIME_INTERVAL => $MAXIMUM_NUMBER_OF_CALLS,
		],
	],
	// ...
]);
```

## Call caching

This feature can prevent unnecessary calls to API within short timespan
by temporarily saving fetched data from API and using them as the result data.
In order to enable this feature, you have to set `RiotAPI::SET_CACHE_CALLS` to `true`.
You should also provide `RiotAPI::SET_CACHE_CALLS_LENGTH` option or else default
time interval of `60 seconds` will be used.

| Variable | Data type | Possible values |
| -------- | --------- | --------------- |
| `RiotAPI::SET_CACHE_CALLS` | `bool` | `true`, `false` |
| `RiotAPI::SET_CACHE_CALLS_LENGTH` | `int` | _time interval in seconds_ |

**Library initialization**:
```php
use RiotAPI\RiotAPI;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_CACHE_CALLS        => true,
	RiotAPI::SET_CACHE_CALLS_LENGTH => $TIME_INTERVAL,
	// ...
]);
```

## StaticData linking

_Planned for upcomming versions._

## Extensions

Using extensions for ApiObjects is useful tool, allowing implementation
of your own methods into the ApiObjects itself. Extensions are enabled by
using settings option `RiotAPI::SET_EXTENSIONS` when initializing the library.

Any extending class must implement `Objects\IApiObjectExtension`. Only class names
are provided, no instances required. Extension will be initialized (instantiated)
when object is being initialized.

**Library initialization**:
```php
use RiotAPI\RiotAPI;
use RiotAPI\Objects;
use RiotAPI\Extensions;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_EXTENSIONS => [
		Objects\MasteryPagesDto::class => Extensions\MasteryPagesDtoExtension::class,
		//  API_OBJECT_TO_BE_EXTENDED  => EXTENDING_CLASS,
	],
	// ...
]);
```

And from now on, on any `MasteryPagesDto` object returned to you by the API, you can
use methods declared in your extension class. Just like this:

```php
$masteryPages = $api->getMasteriesBySummoner(30904166);

if ($masteryPages->pageExists('MasterPageName'))
	echo "Mastery page exists.";
	
if ($page = $masteryPages->getPageByName('MasterPageName'))
{
	echo $page->id;
	echo $page->name;
}
```

On initialization the extension class is provided with `Objects\IApiObject` reference
(in example case above it would be `Objects\MasteryPagesDto`) and `RiotAPI` instance
reference. 

# DataDragon API

How easy it is to work with images? For instance, to get splash image of Orianna?

`echo DataDragonAPI::getChampionSplashO($api->getStaticChampion(61));`, that easy.

Want to know more? _TBA_

