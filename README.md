# RiotAPI PHP7 wrapper

> Version v0.8

[![Build Status](https://travis-ci.org/dolejska-daniel/riot-api.svg?branch=master)](https://travis-ci.org/dolejska-daniel/riot-api)
[![Test Coverage](https://codeclimate.com/github/dolejska-daniel/riot-api/badges/coverage.svg)](https://codeclimate.com/github/dolejska-daniel/riot-api/coverage)
[![GitHub release](https://img.shields.io/github/release/dolejska-daniel/riot-api.svg)](https://github.com/dolejska-daniel/riot-api)
[![GitHub pre release](https://img.shields.io/github/release/dolejska-daniel/riot-api/all.svg?label=pre%20release)](https://github.com/dolejska-daniel/riot-api)
[![Packagist](https://img.shields.io/packagist/v/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)
[![Packagist](https://img.shields.io/packagist/l/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)

# Table of Contents

1. [Introduction](#introduction)
2. [Downloading](#downloading)
3. [League of Legends API](#league-of-legends-api)
	1. [Endpoint versions](#endpoint-versions)
	2. [Initializing the library](#initializing-the-library)
	3. [Usage example](#usage-example)
	4. [Resources and endpoints](#resources-and-endpoints)
		1. [Champion](#champion-)
		2. [Champion Mastery](#champion-mastery-)
		3. [League](#league-)
		4. [Masteries](#masteries-)
		5. [Match](#match-)
		6. [Runes](#runes-)
		7. [Spectator](#spectator-)
		8. [Static Data](#static-data-)
		9. [Status](#status-)
		10. [Summoner](#summoner-)
		11. [Tournament & Tournament Stub](#tournament---tournament-stub-)
	5. [Cache providers](#cache-providers)
	6. [Rate limiting](#rate-limiting)
	7. [Call caching](#call-caching)
	8. [StaticData linking](#staticdata-linking)
	9. [Extensions](#extensions)
	10. [Callback functions](#callback-functions)
	11. [CLI support](#cli-support)
4. [DataDragon API](#datadragon-api)

# Introduction

Welcome to the RiotAPI PHP7 library repo! The goal of this library is to create easy-to-use
library for anyone who might need one. This is fully object oriented API wrapper for
League of Legends' API. A small DataDragon API is included.

Here are some handy features:

- **Rate limit caching** and limit exceeding prevention.
- **Call caching**, enabling the library to re-use already fetched data within short timespan.
- **Object extensions** - you can create your own extensions for any ApiObject returned
 by the library and use custom functions defined in these extensions.
- **Interim mode** support, you are going to be able to use the API the same way
 whether your key is in `interim mode` or not (meaning you won't need to change anything
 when you jump to production).
- **Objects everywhere**! API calls return data in special objects.

I would be grateful for any feedback - so if you can give me any, just do it! Also feel free
to send pull requests if you find anything that is worth improving!

Please, read on :)

# Downloading

The easiest way to get this library is to use [Composer](https://getcomposer.org/). While
having Composer installed it takes only `composer require dolejska-daniel/riot-api` to
get the library ready!

If you are not fan of Composer, you can download [whole repository in .zip archive](https://github.com/dolejska-daniel/riot-api/archive/master.zip)
or clone the repository using Git - `git clone https://github.com/dolejska-daniel/riot-api`.
_But in this case, you will have to create your own autoload function._

# League of Legends API

## Endpoint versions

Below you can find table of implemented API endpoints and the version in which they are currently implemented.

| Endpoint         | Status |
| ---------------- | ------ |
| [Champion](#champion-) | ![Champion endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Champion Mastery](#champion-mastery-) | ![Champion Mastery endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [League](#league-) | ![League endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Masteries](#masteries-) | ![Masteries endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Match](#match-) | ![Match endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Runes](#runes-) | ![Runes endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Spectator](#spectator-) | ![Spectator endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Static Data](#static-data-) | ![Static Data endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Stats            | ![Stats endpoint implemented version](https://img.shields.io/badge/implemented_version-removed-red.svg) |
| [Status](#status-) | ![Status endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Summoner](#summoner-) | ![Summoner endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Tournament](#tournament---tournament-stub-) | ![Tournament endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Tournament Stub](#tournament---tournament-stub-) | ![Tournament Stub endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |

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
| `RiotAPI::SET_REGION` | `Region::EUROPE_EAST`, `Region::EUROPE_WEST`, `Region::NORTH_AMERICA`, `string` | ***Required.*** Used to specify, to which endpoint calls are going to be made. |
| `RiotAPI::SET_KEY` | `string` | ***Required.*** Option to specify your _API key_. |
| `RiotAPI::SET_TOURNAMENT_KEY` | `string` | Option to specify your _tournament API key_. |
| `RiotAPI::SET_VERIFY_SSL` | `bool` `default true` | Use this option to disable SSL verification. Useful when testing on localhost. Shoul not be used in production. |
| `RiotAPI::SET_KEY_INCLUDE_TYPE` | `RiotAPI::KEY_AS_QUERY_PARAM`, `RiotAPI::KEY_AS_HEADER` | This option determines how is API key going to be included in the requests (by default `RiotAPI::KEY_AS_HEADER`). |
| `RiotAPI::SET_INTERIM` | `bool` `default false` | By specifying this, you tell the library to be in `interim mode` and use interim-only endpoints (eg. tournament calls will be sent to stub endpoints). |
| `RiotAPI::SET_CACHE_RATELIMIT` | `bool` `default false` | This option tells the library to take care of not exceeding your API key's rate limit by counting the requests (you should also set desired limits using `RiotAPI::SET_RATELIMITS` or `defaults` will be used). See [rate limiting](#rate-limiting) for more information. |
| `RiotAPI::SET_RATELIMITS` | `array` | Option to specify per-key API call rate limits. See [rate limiting](#rate-limiting) for more information. |
| `RiotAPI::SET_CACHE_CALLS` | `bool` `default false` | This option tells the library to cache fetched data from API and to try to re-use already fetched data (you should also set option `RiotAPI::SET_CACHE_CALLS_LENGTH` to specify for how long should fetched data be stored in cache). See [call caching](#call-caching) for more information. |
| `RiotAPI::SET_CACHE_CALLS_LENGTH` | `int`&#124;`array` `default 60` | Option to specify how log should fetched data from API be saved in cache. See [call caching](#call-caching) for more information. |
| `RiotAPI::SET_CACHE_PROVIDER` | `RiotAPI::CACHE_PROVIDER_FILE`, `RiotAPI::CACHE_PROVIDER_MEMCACHED`, `ICacheProvider` | Using this option you can select from our cache providers or even provide your own. See [cache providers](#cache-providers) for more information. |
| `RiotAPI::SET_CACHE_PROVIDER_PARAMS` | `array` | These are parameters, that will be passed to the CacheProvider on it's initialization. See [cache providers](#cache-providers) for more information. |
| `RiotAPI::SET_EXTENSIONS` | `array` | This option contains extensions for any ApiObject. See [extensions](#extensions) for more information. |
| `RiotAPI::SET_STATICDATA_LINKING` | `array` | This option . See [StaticData linking](#staticdata-linking) for more information. |
| `RiotAPI::SET_STATICDATA_LOCALE` | `array` | This option . See [StaticData linking](#staticdata-linking) for more information. |
| `RiotAPI::SET_STATICDATA_VERSION` | `array` | This option . See [StaticData linking](#staticdata-linking) for more information. |
| `RiotAPI::SET_CALLBACKS_BEFORE` | `array` | This option . See [callback functions](#callback-functions) for more information. |
| `RiotAPI::SET_CALLBACKS_AFTER` | `array` | This option . See [callback functions](#callback-functions) for more information. |

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

You can get more details about these functions in
[resources and endpoints](#resources-and-endpoints) section below.

## Resources and endpoints

Below you will find tables containting `endpoint` functions for each `resource`. `resources`
are only important when you want to use `resource` specific [call caching](#call-caching).
Otherwise they doesn't play any significant role.

Parameters with specified default value are optional.

### Champion ![Champion endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_CHAMPION`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getChampions` | `bool $only_free_to_play = null` | [`Objects\ChampionListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionListDto.php) |
| `getChampionById` | `int $champion_id` | [`Objects\ChampionDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionDto.php) |

### Champion Mastery ![Champion Mastery endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_CHAMPIONMASTERY`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getChampionMastery` | `int $summoner_id`, `int $champion_id` | [`Objects\ChampionMasteryDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionMasteryDto.php) |
| `getChampionMasteries` | `int $summoner_id` | [`Objects\ChampionMasteryDto[]`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionMasteryDto.php) |
| `getChampionMasteryScore` | `int $summoner_id` | `int` |

### League ![League endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_LEAGUE`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getLeaguesForSummoner` | `int $summoner_id` | [`Objects\LeagueListDto[]`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LeagueListDto.php) |
| `getLeaguePositionsForSummoner` | `int $summoner_id` | [`Objects\LeaguePositionDto[]`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LeaguePositionDto.php) |
| `getLeagueChallenger` | `string $game_queue_type` | [`Objects\LeagueListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LeagueListDto.php) |
| `getLeagueMaster` | `string $game_queue_type` | [`Objects\LeagueListDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LeagueListDto.php) |

### Masteries ![Masteries endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_MASTERIES`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getMasteriesBySummoner` | `int $summoner_id` | [`Objects\MasteryPagesDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MasteryPagesDto.php) |

### Match ![Match endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_MATCH`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getMatch` | `int $match_id`, `int $for_account_id = null` | [`Objects\MatchDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchDto.php) |
| `getMatchByTournamentCode` | `int $match_id`, `string $tournament_code` | [`Objects\MatchDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchDto.php) |
| `getMatchIdsByTournamentCode` | `string $tournament_code` | `int[]` |
| `getMatchlistByAccount` | `int $account_id` | [`Objects\MatchlistDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchlistDto.php) |
| `getRecentMatchlistByAccount` | `int $account_id` | [`Objects\MatchlistDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchlistDto.php) |
| `getMatchTimeline` | `int $match_id` | [`Objects\MatchTimelineDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MatchTimelineDto.php) |

### Runes ![Runes endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_RUNES`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getRunesBySummoner` | `int $summoner_id` | [`Objects\RunePagesDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/RunePagesDto.php) |

### Spectator ![Spectator endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_SPECTATOR`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getCurrentGameInfo` | - | [`Objects\CurrentGameInfo`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/CurrentGameInfo.php) |
| `getFeaturedGames` | - | [`Objects\FeaturedGames`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/FeaturedGames.php) |

### Static Data ![Static Data endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_STATICDATA`.

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

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_STATUS`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getStatusData` | - | [`Objects\ShardStatus`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ShardStatus.php) |

### Summoner ![Summoner endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_SUMMONER`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `getSummoner` | `int $summoner_id` | [`Objects\SummonerDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/SummonerDto.php) |
| `getSummonerByName` | `string $summoner_name` | [`Objects\SummonerDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/SummonerDto.php) |
| `getSummonerByAccount` | `int $account_id` | [`Objects\SummonerDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/SummonerDto.php) |

### Tournament ![Tournament endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) & Tournament Stub ![Tournament Stub endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg)

All these functions are `endpoints` of `resource` `RiotAPI::RESOURCE_TOURNAMENT`. When using `interim mode`
`resource` `RiotAPI::RESOURCE_TOURNAMENT_STUB` will be used instead.

**Only these functions are available in** `interim mode`:
- `createTournamentCodes`
- `createTournamentProvider`
- `createTournament`
- `getTournamentLobbyEvents`

Other functions will throw [`Exceptions\RequestException`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Exceptions/RequestException.php)
when used in `interim mode`.

| Function name | Parameters | Return type |
| ------------- | ---------- | ----------- |
| `createTournamentCodes` | `int $tournament_id`, `int $count`, [`Objects\TournamentCodeParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentCodeParameters.php) | `string[]` |
| `editTournamentCode` | `string $tournament_code`, [`Objects\TournamentCodeUpdateParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentCodeUpdateParameters.php) | - |
| `getTournamentCodeData` | `string $tournament_code` | [`Objects\TournamentCodeDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentCodeDto.php) |
| `createTournamentProvider` | [`Objects\ProviderRegistrationParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ProviderRegistrationParameters.php) | `int` |
| `createTournament` | [`Objects\TournamentRegistrationParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentRegistrationParameters.php) | `int` |
| `getTournamentLobbyEvents` | `string $tournament_code` | [`Objects\LobbyEventDtoWrapper`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/LobbyEventDtoWrapper.php) |

**Using special objects in requests**:

[`Objects\TournamentCodeParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentCodeParameters.php) and [`Objects\SummonerIdParams`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/SummonerIdParams.php):

```php
//  ...
$codeParams = new Objects\TournamentCodeParameters([
	'allowedSummonerIds' => new Objects\SummonerIdParams([
		'participants' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
	]),
	'mapType'       => 'SUMMONERS_RIFT',
	'pickType'      => 'ALL_RANDOM',
	'spectatorType' => 'ALL',
	'teamSize'      => 5,
]);

$codes = $api->createTournamentCodes($tournament_id, $count, $codeParams);
```

[`Objects\TournamentCodeUpdateParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentCodeUpdateParameters.php):

```php
//  ...
$codeParams = new Objects\TournamentCodeUpdateParameters([
	'allowedParticipants' => implode(',', [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]),
	'mapType'             => 'SUMMONERS_RIFT',
	'pickType'            => 'ALL_RANDOM',
	'spectatorType'       => 'ALL',
	'teamSize'            => 5,
]);

$api->editTournamentCode($tournament_code, $codeParams);
```

[`Objects\ProviderRegistrationParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ProviderRegistrationParameters.php):

```php
//  ...
$providerParams = new Objects\ProviderRegistrationParameters([
	'region' => Region::EUROPE_EAST,
	'url'    => $callback_url,
]);

$provider_id = $api->createTournamentProvider($providerParams);
```

[`Objects\TournamentRegistrationParameters`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/TournamentRegistrationParameters.php):

```php
//  ...
$tournamentParams = new Objects\TournamentRegistrationParameters([
	'providerId' => $provider_id,
	'name'       => $tournament_name,
]);

$provider_id = $api->createTournament($tournamentParams);
```

## Cache providers

Cache providers are responsible for keeping data of [rate limiting](#rate-limiting)
and [call caching](#call-caching) within instances of the library. This feature
is automatically enabled, when any of previously mentioned features is used.

When using this feature, you can set `RiotAPI::SET_CACHE_PROVIDER` to any class,
thought it has to implement `Objects\ICacheProvider` interface. By using `RiotAPI::SET_CACHE_PROVIDER_PARAMS`
option, you can pass any variables to the cache provider.

| Settings key | Data type | Info / Possible values |
| ------------ | --------- | ---------------------- |
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

| Settings key | Data type | Info / Possible values |
| ------------ | --------- | --------------- |
| `RiotAPI::SET_CACHE_RATELIMIT` | `bool` | `true`, `false` |
| `RiotAPI::SET_RATELIMITS` | `array` | _see example below_ |

| Variable | Data type | Info / Possible values |
| -------- | --------- | --------------- |
| `$YOUR_RIOT_API_KEYx` | `string` | _your Riot API key, or tournament API key_ |
| `$TIME_INTERVALx` | `int` | `IRateLimitControl::INTERVAL_1S`, `IRateLimitControl::INTERVAL_10S`, `IRateLimitControl::INTERVAL_10M`, `IRateLimitControl::INTERVAL_1H` |
| `$MAXIMUM_NUMBER_OF_CALLSx` | `int` | _maximum number of calls_ |

**Library initialization**:
```php
use RiotAPI\RiotAPI;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_CACHE_RATELIMIT => true,
	RiotAPI::SET_RATELIMITS => [
		$YOUR_RIOT_API_KEY1 => [
			$TIME_INTERVAL1 => $MAXIMUM_NUMBER_OF_CALLS1,
			$TIME_INTERVAL2 => $MAXIMUM_NUMBER_OF_CALLS2,
		],
		$YOUR_RIOT_API_KEY2 => [
			$TIME_INTERVAL3 => $MAXIMUM_NUMBER_OF_CALLS3,
			$TIME_INTERVAL4 => $MAXIMUM_NUMBER_OF_CALLS4,
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

| Settings key | Data type | Info / Possible values |
| -------- | --------- | --------------- |
| `RiotAPI::SET_CACHE_CALLS` | `bool` | `true`, `false` |
| `RiotAPI::SET_CACHE_CALLS_LENGTH` | `int`&#124;`array` | _see example below_ |

`RiotAPI::SET_CACHE_CALLS_LENGTH` can either be `int` only - in that case, this time interval
will be set onto every `resource-endpoint` or it can be `array` specifying the time interval
separately for each `resource` - **only specified resources will be cached in this case.**

| Variable | Data type |Info / Possible values |
| -------- | --------- | --------------- |
| `$RESOURCEx` | `string` | `RiotAPI::RESOURCE_CHAMPION`, `RiotAPI::RESOURCE_CHAMPIONMASTERY`, `RiotAPI::RESOURCE_LEAGUE`, `RiotAPI::RESOURCE_STATICDATA`, `RiotAPI::RESOURCE_STATUS`, `RiotAPI::RESOURCE_MASTERIES`, `RiotAPI::RESOURCE_MATCH`, `RiotAPI::RESOURCE_RUNES`, `RiotAPI::RESOURCE_SPECTATOR`, `RiotAPI::RESOURCE_SUMMONER`, `RiotAPI::RESOURCE_TOURNAMENT`, `RiotAPI::RESOURCE_TOURNAMENT_STUB` |
| `$TIME_LIMITx` | `int` | _time limit in seconds_ |

```php
$callsLength = [
	$RESOURCE1 => $TIME_LIMIT1,
	$RESOURCE2 => $TIME_LIMIT2,
	$RESOURCE3 => $TIME_LIMIT3,
];
```

**Library initialization**:

Caching calls on all `resources` for `$TIME_LIMIT0` seconds:

```php
use RiotAPI\RiotAPI;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_CACHE_CALLS        => true,
	RiotAPI::SET_CACHE_CALLS_LENGTH => $TIME_LIMIT0,
	// ...
]);
```

Caching calls __only__ on `RiotAPI::RESOURCE_STATICDATA` `resource` for `$TIME_LIMIT1`
and `RiotAPI::RESOURCE_SUMMONER` `resource` for `$TIME_LIMIT2` seconds (calls on different
`resources` will not be cached at all):

```php
use RiotAPI\RiotAPI;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_CACHE_CALLS         => true,
	RiotAPI::SET_CACHE_CALLS_LENGTH  => [
		RiotAPI::RESOURCE_STATICDATA => $TIME_LIMIT1,
		RiotAPI::RESOURCE_SUMMONER   => $TIME_LIMIT2,
	],
	// ...
]);
```

## StaticData linking

This feature allows you to automatically link static data related to your request.
This action __is time consuming__ (works well when caching call data for
`StaticData resource`), but calls to `StaticData resource` are not counted
to your rate limit so there is no problem in using it.

| Settings key | Data type | Info / Possible values |
| -------- | --------- | --------------- |
| `RiotAPI::SET_STATICDATA_LINKING` | `bool` | `true`, `false` |
| `RiotAPI::SET_STATICDATA_LOCALE` | `string` | _Optional._ Language in which will static data be returned. Supports only languages supported by region. |
| `RiotAPI::SET_STATICDATA_VERSION` | `string` | _Optional._ Game version - when not provided newest version is used. |

Only objects shown in table below will be used for automatic static data linking.

| Linkable object | Link target |
| --------------- | ----------- 
| [`Objects\BannedChampion`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/BannedChampion.php) | `StaticChampion` |
| [`Objects\ChampionDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionDto.php) | `StaticChampion` |
| [`Objects\ChampionMasteryDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/ChampionMasteryDto.php) | `StaticChampion` |
| [`Objects\CurrentGameParticipant`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/CurrentGameParticipant.php) | `StaticChampion` |
| [`Objects\Mastery`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/Mastery.php) | `StaticMastery` |
| [`Objects\MasteryDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/MasteryDto.php) | `StaticMastery` |
| [`Objects\MatchReferenceDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/BannedChampion.php) | `StaticChampion` |
| [`Objects\Participant`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/BannedChampion.php) | `StaticChampion` |
| [`Objects\ParticipantDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/BannedChampion.php) | `StaticChampion` |
| [`Objects\Rune`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/BannedChampion.php) | `StaticRune` |
| [`Objects\RuneDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/BannedChampion.php) | `StaticRune` |
| [`Objects\RuneSlotDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/BannedChampion.php) | `StaticRune` |
| [`Objects\TeamBansDto`](https://github.com/dolejska-daniel/riot-api/blob/master/src/RiotAPI/Objects/BannedChampion.php) | `StaticChampion` |

**Library initialization**:
```php
use RiotAPI\RiotAPI;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_STATICDATA_LINKING => true,
	// ...
]);
```

And from now on, static data will be automatically linked to some specific objects
shown above. You can easily access static data properties for these objects like this:

```php
//  ...

//  this call returns Objects\ChampionDto
$champion = $api->getChampion(61);

//  accessing Objects\ChampionDto's property
echo $champion->id; // 61
echo $champion->freeToPlay; // false
echo $champion->rankedPlayEnabled; // true

//  accessing static data property by magic method
//  (this will only work when static data property name
//    you want to access is not already in use by original object)
echo $champion->name; // Orianna

//  accessing static data through special property
echo $champion->staticData->name; // Orianna
```

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

## Callback functions

Custom function callback before and after the call is made.

| Settings key | Data type | Info / Possible values |
| -------- | --------- | --------------- |
| `RiotAPI::SET_CALLBACKS_BEFORE` | `callable`&#124;`callable[]` | _see example below_ |
| `RiotAPI::SET_CALLBACKS_AFTER` | `callable`&#124;`callable[]` | _see example below_ |

**Before request callbacks:**

Every before request callback will receive these parameters:

| Parameter name | Data type | Description |
| -------------- | --------- | ----------- |
| $api           | `RiotAPI` | Instance of RiotAPI. If you wish to edit something in the instance, use `&$api` instead, in your callback function declaration. |
| $url           | `string`  | Complete URL to be called in request. |
| $requestHash   | `string`  | This hash is used when caching request. It is unique identificator _of URL_. Requests on same endpoints with same parameters will have same hash.

Before request callbacks have ability to cancel upcomming request - when `false` is returned
by _any callback_ function, exception `Exceptions\RequestException` is raised and
request is cancelled.

In case rate limiting feature is enabled and limit should be exceeded by upcomming call,
no before request callbacks will be called and `Exceptions\ServerLimitException`
exception will be raised.

**After request callback function parameters:**

Every after request callback will receive these parameters:

| Parameter name | Data type | Description |
| -------------- | --------- | ----------- |
| $api           | `RiotAPI` | Instance of RiotAPI. If you wish to edit something in the instance, use `&$api` instead, in your callback function declaration. |
| $url           | `string`  | Complete URL to be called in request. |
| $requestHash   | `string`  | This hash is used when caching request. It is unique identificator _of URL_. Requests on same endpoints with same parameters will have same hash. |
| $curlResource  | `resource` | cUrl resource used in request, can be used to get usefull request information (e.g. with `curl_getinfo` function). |

In case the call failed, exception will be raised based on HTTP code and no after request
callbacks will be called.

**Library initialization:**

```php
use RiotAPI\RiotAPI;

$api = new RiotAPI([
	// ...
	RiotAPI::SET_CALLBACKS_BEFORE => [
		function($api, $url, $requestHash) {
			// function logic
		},
		array($object, 'functionName'),
	],
	RiotAPI::SET_CALLBACKS_AFTER => function($api, $url, $requestHash, $curlResource) {
		// function logic
	},
	// ...
]);
```

## CLI support

_Planned for upcomming releases._

# DataDragon API

How easy it is to work with images? For instance, to get splash image of Orianna?

`echo DataDragonAPI::getChampionSplashO($api->getStaticChampion(61));`, that easy.

Want to know more? _TBA_

