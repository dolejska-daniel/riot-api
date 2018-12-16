# RiotAPI PHP7 wrapper [![GitHub release](https://img.shields.io/github/release/dolejska-daniel/riot-api.svg)](https://github.com/dolejska-daniel/riot-api) [![Packagist](https://img.shields.io/packagist/v/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)

> Version v3.0.0-rc.1

[![Build Status](https://travis-ci.org/dolejska-daniel/riot-api.svg?branch=master)](https://travis-ci.org/dolejska-daniel/riot-api)
[![Test Coverage](https://codeclimate.com/github/dolejska-daniel/riot-api/badges/coverage.svg)](https://codeclimate.com/github/dolejska-daniel/riot-api/coverage)
[![Packagist](https://img.shields.io/packagist/dm/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)
[![Packagist](https://img.shields.io/packagist/l/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)
[![Support Project](https://img.shields.io/badge/support_project-PayPal-blue.svg)](https://www.paypal.me/dolejskad)


# RiotAPI v3 CHANGES WARNING!
Update [`v3.0.0-rc.1`](https://github.com/dolejska-daniel/riot-api/releases/tag/v3.0.0-rc.1) came with big changes to the library!
If you've been using this library and just updated - READ THIS.

**OLD DEPRECATED USAGE**:
```php
use RiotAPI\RiotAPI;
use RiotAPI\Exceptions\GeneralException;
use RiotAPI\Objects\ChampionInfo;

try
{
	$api = new RiotAPI([...]);
}
catch (GeneralException $e)
{
	// ...
}
```
```php
use DataDragonAPI\DataDragonAPI;
use DataDragonAPI\Exception\GeneralException;
use DataDragonAPI\Definition\Map;

DataDragonAPI::initByCdn([...]);
// ...
```

**NEW USAGE**:

Main API class `RiotAPI` has been renamed to `LeagueAPI` and has been moved to `RiotAPI\LeagueAPI` namespace.
Also all related objects (`Objects`, `Definitions`, ...) has been moved to `RiotAPI\LeagueAPI` (`RiotAPI\LeagueAPI\Objects`, `RiotAPI\LeagueAPI\Definitions` respectively...).

```php
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\Objects\ChampionInfo;

try
{
	$api = new LeagueAPI([...]);
}
catch (GeneralException $e)
{
	// ...
}
```

`DataDragonAPI` class has been moved to `RiotAPI\DataDragonAPI` namespace.
Also all related objects (`Definition` and `Exception`) has been moved to `RiotAPI\DataDragonAPI` (`RiotAPI\DataDragonAPI\Definitions` and `RiotAPI\DataDragonAPI\Exceptions` respectively...).

```php
use RiotAPI\DataDragonAPI\DataDragonAPI;
use RiotAPI\DataDragonAPI\Exceptions\GeneralException;
use RiotAPI\DataDragonAPI\Definitions\Map;

DataDragonAPI::initByCdn([...]);
// ...
```


# Table of Contents
1. [Introduction](#introduction)
2. [Downloading](#downloading)
3. [League of Legends API](#league-of-legends-api)
	1. [Resource versions](#resource-versions)
	2. [Initializing the library](#initializing-the-library)
	3. [Usage example](#usage-example)
	4. [Cache providers](#cache-providers)
	5. [Rate limiting](#rate-limiting)
	6. [Call caching](#call-caching)
	7. [Asynchronous requests](#asynchronous-requests)
	8. [StaticData endpoints](#staticdata-endpoints)
	9. [StaticData linking](#staticdata-linking)
	10. [Extensions](#extensions)
	11. [Callback functions](#callback-functions)
	12. [CLI support](#cli-support)
4. [DataDragon API](#datadragon-api)


# [Introduction](https://github.com/dolejska-daniel/riot-api/wiki/Home#introduction)
Welcome to the RiotAPI PHP7 library repo!
The goal of this library is to create easy-to-use library for anyone who might need one.
This is fully object oriented API wrapper for League of Legends' API.
A small DataDragon API is also included.

Here are some handy features:

- **[Rate limit caching](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Rate-limiting)** and limit exceeding prevention - fully automatic.
- **[Call caching](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Call-caching)** - this enables the library to re-use already fetched data within short timespan - saving time and API rate limit.
- **[StaticData endpoints](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-StaticData-endpoints)** - you can work with StaticData endpoints as if they were never deprecated.
- **[StaticData linking](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-StaticData-linking)** - library can automatically link StaticData related to your request right into the returned object.
- **[Custom callbacks](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Callback-functions)** - you can set custom function which will be called before or after the request is processed.
- **[Object extensions](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Extensions)** - you can implement own methods to the fetched API objects itself and enable yourself to use them later to ease of your work.
- **[CLI supported](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-CLI-support)**! You can use the library easily even in PHP CLI mode.
- **Interim mode** support, you are going to be able to use the API the same way whether your key is in `interim mode` or not (meaning you won't need to change anything when you jump to production).
- **Objects everywhere**! API calls return data in special objects.

Please, refer mainly to the [wiki pages](https://github.com/dolejska-daniel/riot-api/wiki).


# [Downloading](https://github.com/dolejska-daniel/riot-api/wiki/Home#downloading)
The easiest way to get this library is to use [Composer](https://getcomposer.org/).

While having Composer installed it takes only `composer require dolejska-daniel/riot-api` and `composer install` to get the library ready to roll!


# League of Legends API


## [Resource versions](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints)
Below you can find table of implemented API resources and the version in which they are currently implemented.
Please refer to [wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints) for more information about endpoints and resources.

| Resource         | Status |
| ---------------- | ------ |
| [Champion](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#champion-) | ![Champion resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Champion Mastery](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#champion-mastery-) | ![Champion Mastery resource implemented version](https://img.shields.io/badge/implemented_version-v4-brightgreen.svg) |
| [League](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#league-) | ![League resource implemented version](https://img.shields.io/badge/implemented_version-v4-brightgreen.svg) |
| Masteries | ![Masteries resource implemented version](https://img.shields.io/badge/implemented_version-deprecated-red.svg) |
| [Match](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#match-) | ![Match resource implemented version](https://img.shields.io/badge/implemented_version-v4-brightgreen.svg) |
| Runes | ![Runes resource implemented version](https://img.shields.io/badge/implemented_version-deprecated-red.svg) |
| [Spectator](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#spectator-) | ![Spectator resource implemented version](https://img.shields.io/badge/implemented_version-v4-brightgreen.svg) |
| [Static Data](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#static-data-) | ![Static Data resource implemented version](https://img.shields.io/badge/implemented_version-_working-brightgreen.svg) |
| Stats | ![Stats endpoint implemented version](https://img.shields.io/badge/implemented_version-deprecated-red.svg) |
| [Status](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#status-) | ![Status resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Summoner](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#summoner-) | ![Summoner resource implemented version](https://img.shields.io/badge/implemented_version-v4-brightgreen.svg) |
| [Third Party Code](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI%3A-Resources-and-endpoints#third-party-code-) | ![Third Party Code endpoint implemented version](https://img.shields.io/badge/implemented_version-v4-brightgreen.svg) |
| [Tournament](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#tournament---tournament-stub-) | ![Tournament resource implemented version](https://img.shields.io/badge/implemented_version-v4-brightgreen.svg) |
| [Tournament Stub](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#tournament---tournament-stub-) | ![Tournament Stub resource implemented version](https://img.shields.io/badge/implemented_version-v4-brightgreen.svg) |


## [Initializing the library](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-How-to-begin)
How to begin?

```php
//  Include all required files
require_once __DIR__  . "/vendor/autoload.php";

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;

//  Initialize the library
$api = new LeagueAPI([
	//  Your API key, you can get one at https://developer.riotgames.com/
	LeagueAPI::SET_KEY    => 'YOUR_RIOT_API_KEY',
	//  Target region (you can change it during lifetime of the library instance)
	LeagueAPI::SET_REGION => Region::EUROPE_EAST,
]);

//  And now you are ready to rock!
$ch = $api->getStaticChampion(61); // Orianna <3
```

And there is a lot more what you can set when initializing the library - mainly to enable special features or to amend behaviour of the library.
Please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-How-to-begin) for complete list of library's settings.


## [Usage example](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-How-to-begin#usage-example)
Working with LeagueAPI can not be easier, just watch how to fetch summoner information based on summoner's name:

```php
//  ...initialization...

//  this fetches the summoner data and returns SummonerDto object
$summoner = $api->getSummonerByName('I am TheKronnY');

echo $summoner->id;             //  KnNZNuEVZ5rZry3I...
echo $summoner->puuid;          //  rNmb6Rq8CQUqOHzM...
echo $summoner->name;           //  I am TheKronnY
echo $summoner->summonerLevel;  //  69

print_r($summoner->getData());  //  Or array of all the data
/* Array
 * (
 *     [id] => KnNZNuEVZ5rZry3IyWwYSVuikRe0y3qTWSkr1wxcmV5CLJ8
 *     [accountId] => tGSPHbasiCOgRM_MuovMKfXw7oh6pfXmGiPDnXcxJDohrQ
 *     [puuid] => rNmb6Rq8CQUqOHzMsFihMCUy4Pd201vDaRW9djAoJ9se7myXrDprvng9neCanq7yGNmz7B3Wri4Elw
 *     [name] => I am TheKronnY
 *     [profileIconId] => 3180
 *     [revisionDate] => 1543438015000
 *     [summonerLevel] => 69
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


## [Cache providers](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Cache-providers)
Cache providers are responsible for keeping data of [rate limiting](#rate-limiting) and [call caching](#call-caching) within instances of the library.
This feature is automatically enabled, when any of previously mentioned features is used.

When using this feature, you can set `LeagueAPI::SET_CACHE_PROVIDER` to any class, thought it has to implement `Objects\ICacheProvider` interface.
By using `LeagueAPI::SET_CACHE_PROVIDER_PARAMS` option, you can pass any variables to the cache provider.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Cache-providers).


## [Rate limiting](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Rate-limiting)
This clever feature will easily prevent exceeding your per key call limits & method limits.
In order to enable this feature, you have to set `LeagueAPI::SET_CACHE_RATELIMIT` to `true`.
Everything is completly automatic, so all you need to do is to enable this feature.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Rate-limiting).


## [Call caching](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Call-caching)
This feature can prevent unnecessary calls to API within short timespan by temporarily saving fetched data from API and using them as the result data.
In order to enable this feature, you have to set `LeagueAPI::SET_CACHE_CALLS` to `true`.
You should also provide `LeagueAPI::SET_CACHE_CALLS_LENGTH` option or else default time interval of `60 seconds` will be used.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Call-caching).


## [Asynchronous requests](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Asynchronous-requests)
This feature allows request grouping and their asynchronous sending using [Guzzle](https://github.com/guzzle/guzzle).
After request is sent and its response received, user provided callbacks are invoked with received data.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Asynchronous-requests).


## [StaticData endpoints](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-StaticData-endpoints)
These endpoints provide you with easy way to transform StaticData into object instances and easily work with them.
They are also supported in numerous DataDragonAPI functions (displaying images).

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-StaticData-endpoints).


## [StaticData linking](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-StaticData-linking)
This feature allows you to automatically link StaticData related to your request.
This action __is time consuming__ (works well when caching call data for `StaticData resource`), but calls to fetch StaticData are not counted to your API key's rate limit.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-StaticData-linking).


## [Extensions](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Extensions)
Using extensions for ApiObjects is useful tool, allowing implementation of your own methods into the ApiObjects itself.
Extensions are enabled by using settings option `LeagueAPI::SET_EXTENSIONS` when initializing the library.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Extensions).


## [Callback functions](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Callback-functions)
Allows you to provide custom functions to be called before and after the actual API request is sent.

Before callbacks have ability to cancel upcomming request - when `false` is returned by _any callback_ function, exception `Exceptions\RequestException` is raised and request is cancelled.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Callback-functions).


## [CLI support](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-CLI-support).
You can easily get API results even in CLI:

```shell
root@localhost:~/src/LeagueAPI# php7.0 LeagueAPICLI.php getChampion 61 --config ~/LeagueAPI_Config.json
```

For more information about CLI support, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-CLI-support).


# [DataDragon API](https://github.com/dolejska-daniel/riot-api/wiki/DataDragonAPI:-How-to-begin)
How easy is it to work with static images? For instance, to get loading screen art of Orianna?

**Source**:
```php
echo DataDragonAPI::getChampionLoading('Orianna');
echo DataDragonAPI::getChampionLoading('Orianna', 7);
```

**Output**:
```html
<img alt="Orianna" class="dd-icon dd-loading" src="http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Orianna_0.jpg">
<img alt="Orianna" class="dd-icon dd-loading" src="http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Orianna_7.jpg">
```

**Render**:

![Orianna](http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Orianna_0.jpg)
![Dark Star Orianna](http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Orianna_7.jpg)


...a bit of nostalgia?

**Source**:
```php
DataDragonAPI::iniByVersion('0.151.2');
echo DataDragonAPI::getItemIcon(3132);
echo DataDragonAPI::getItemIcon(3126);
echo DataDragonAPI::getItemIcon(3138);
```

**Output**:
```html
<img alt="3132" class="dd-icon dd-item" src="http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3132.png">
<img alt="3126" class="dd-icon dd-item" src="http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3126.png">
<img alt="3138" class="dd-icon dd-item" src="http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3138.png">
```

**Render**:

![Heart of Gold](http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3132.png)
![Madred's Bloodrazor](http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3126.png)
![Leviathan](http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3138.png)


...or to display icon of champion and its spells based on its object from API?

**Source**:
```php
// ...

$orianna = $api->getStaticChampion(61, true);
echo DataDragonAPI::getChampionSplashO($orianna);

foreach($orianna->spells as $spell)
    echo DataDragonAPI::getChampionSpellIconO($spell);
```

**Output**:
```html
<img alt="Orianna" class="dd-icon dd-icon-champ" src="https://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/Orianna.png">

<img alt="OrianaIzunaCommand" class="dd-icon dd-spell" src="http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/OrianaIzunaCommand.png">
<img alt="OrianaDissonanceCommand" class="dd-icon dd-spell" src="http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/OrianaDissonanceCommand.png">
<img alt="OrianaRedactCommand" class="dd-icon dd-spell" src="http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/OrianaRedactCommand.png">
<img alt="OrianaDetonateCommand" class="dd-icon dd-spell" src="http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/OrianaDetonateCommand.png">
```

**Render**:

![Orianna](https://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/Orianna.png)
![OrianaIzunaCommand](http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/OrianaIzunaCommand.png)
![OrianaDissonanceCommand](http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/OrianaDissonanceCommand.png)
![OrianaRedactCommand](http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/OrianaRedactCommand.png)
![OrianaDetonateCommand](http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/OrianaDetonateCommand.png)

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/DataDragonAPI:-How-to-begin).
