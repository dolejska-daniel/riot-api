# RiotAPI PHP7 wrapper

> Version v2.0.0-rc.4

[![Build Status](https://travis-ci.org/dolejska-daniel/riot-api.svg?branch=master)](https://travis-ci.org/dolejska-daniel/riot-api)
[![Test Coverage](https://codeclimate.com/github/dolejska-daniel/riot-api/badges/coverage.svg)](https://codeclimate.com/github/dolejska-daniel/riot-api/coverage)
[![GitHub release](https://img.shields.io/github/release/dolejska-daniel/riot-api.svg)](https://github.com/dolejska-daniel/riot-api)
[![GitHub pre release](https://img.shields.io/github/release/dolejska-daniel/riot-api/all.svg?label=pre%20release)](https://github.com/dolejska-daniel/riot-api)
[![Packagist](https://img.shields.io/packagist/v/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)
[![Packagist](https://img.shields.io/packagist/dm/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)
[![Packagist](https://img.shields.io/packagist/l/dolejska-daniel/riot-api.svg)](https://packagist.org/packages/dolejska-daniel/riot-api)

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
	7. [StaticData linking](#staticdata-linking)
	8. [Extensions](#extensions)
	9. [Callback functions](#callback-functions)
	10. [CLI support](#cli-support)
4. [DataDragon API](#datadragon-api)

# [Introduction](https://github.com/dolejska-daniel/riot-api/wiki/Home#introduction)

Welcome to the RiotAPI PHP7 library repo! The goal of this library is to create easy-to-use
library for anyone who might need one. This is fully object oriented API wrapper for
League of Legends' API. A small DataDragon API is also included.

Here are some handy features:

- **Rate limit caching** and limit exceeding prevention - fully automatic.
- **Call caching** - this enables the library to re-use already fetched data within short
timespan - saving time and API rate limit.
- **StaticData linking** - library can automatically link Static Data related to your
request right into the returned object.
- **Custom callbacks** - you can set custom function which will be called before
or after the request is processed.
- **Object extensions** - you can implement own methods to the fetched API objects itself
and enable yourself to use them later to ease of your work.
- **Interim mode** support, you are going to be able to use the API the same way
 whether your key is in `interim mode` or not (meaning you won't need to change anything
 when you jump to production).
- **CLI supported**! You can use the library easily even in PHP CLI mode.
- **Objects everywhere**! API calls return data in special objects.

Please, refer mainly to the [wiki pages](https://github.com/dolejska-daniel/riot-api/wiki).

# [Downloading](https://github.com/dolejska-daniel/riot-api/wiki/Home#downloading)

The easiest way to get this library is to use [Composer](https://getcomposer.org/). While
having Composer installed it takes only `composer require dolejska-daniel/riot-api` to
get the library ready!

If you are not fan of Composer, you can download [whole repository in .zip archive](https://github.com/dolejska-daniel/riot-api/archive/master.zip)
or clone the repository using Git - `git clone https://github.com/dolejska-daniel/riot-api`.
_But in this case, you will have to create your own autoload function._

# League of Legends API

## [Resource versions](](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints))

Below you can find table of implemented API resources and the version in which they are
currently implemented. Please refer to [wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints)
for more information about endpoints and resources.

| Resource         | Status |
| ---------------- | ------ |
| [Champion](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#champion-) | ![Champion resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Champion Mastery](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#champion-mastery-) | ![Champion Mastery resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [League](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#league-) | ![League resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Masteries | ![Masteries resource implemented version](https://img.shields.io/badge/implemented_version-deprecated-red.svg) |
| [Match](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#match-) | ![Match resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Runes | ![Runes resource implemented version](https://img.shields.io/badge/implemented_version-deprecated-red.svg) |
| [Spectator](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#spectator-) | ![Spectator resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Static Data](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#static-data-) | ![Static Data resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| Stats | ![Stats endpoint implemented version](https://img.shields.io/badge/implemented_version-deprecated-red.svg) |
| [Status](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#status-) | ![Status resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Summoner](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#summoner-) | ![Summoner resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Third Party Code](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI%3A-Resources-and-endpoints#third-party-code-) | ![Third Party Code endpoint implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Tournament](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#tournament---tournament-stub-) | ![Tournament resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |
| [Tournament Stub](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Resources-and-endpoints#tournament---tournament-stub-) | ![Tournament Stub resource implemented version](https://img.shields.io/badge/implemented_version-v3-brightgreen.svg) |

## [Initializing the library](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-How-to-begin)

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
$ch = $api->getStaticChampion(61); // Orianna <3
```

And there is a lot more what you can set when initializing the library - mainly to enable
special features or to amend behaviour of the library. Please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-How-to-begin)
for complete list of library's settings.

## [Usage example](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-How-to-begin#usage-example)

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

## [Cache providers](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Cache-providers)

Cache providers are responsible for keeping data of [rate limiting](#rate-limiting)
and [call caching](#call-caching) within instances of the library. This feature
is automatically enabled, when any of previously mentioned features is used.

When using this feature, you can set `RiotAPI::SET_CACHE_PROVIDER` to any class,
thought it has to implement `Objects\ICacheProvider` interface.
By using `RiotAPI::SET_CACHE_PROVIDER_PARAMS` option, you can pass any
variables to the cache provider.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Cache-providers).

## [Rate limiting](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Rate-limiting)

This clever feature will easily prevent exceeding your per key 
call limits & method limits. In order to enable this feature, you have to set
`RiotAPI::SET_CACHE_RATELIMIT` to `true`. Everything is completly automatic,
so all you need to do is to enable this feature.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Rate-limiting).

## [Call caching](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Call-caching)

This feature can prevent unnecessary calls to API within short timespan
by temporarily saving fetched data from API and using them as the result data.
In order to enable this feature, you have to set `RiotAPI::SET_CACHE_CALLS` to `true`.
You should also provide `RiotAPI::SET_CACHE_CALLS_LENGTH` option or else default
time interval of `60 seconds` will be used.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Call-caching).

## [StaticData linking](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-StaticData-linking)

This feature allows you to automatically link static data related to your request.
This action __is time consuming__ (works well when caching call data for
`StaticData resource`), but calls to `StaticData resource` are not counted
to your rate limit so there is no problem in using it.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-StaticData-linking).

## [Extensions](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Extensions)

Using extensions for ApiObjects is useful tool, allowing implementation
of your own methods into the ApiObjects itself. Extensions are enabled by
using settings option `RiotAPI::SET_EXTENSIONS` when initializing the library.

Any extending class must implement `Objects\IApiObjectExtension`. Only class names
are provided, no instances required. Extension will be initialized (instantiated)
when object is being initialized.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Extensions).

## [Callback functions](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Callback-functions)

Custom function callback before and after the call is made.

Before request callbacks have ability to cancel upcomming request - when `false` is returned
by _any callback_ function, exception `Exceptions\RequestException` is raised and
request is cancelled.

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-Callback-functions).

## [CLI support](https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-CLI-support).

You can easily get API results even in CLI:

```shell
root@localhost:~/src/RiotAPI# php7.0 RiotAPICLI.php getChampions --config ~/RiotAPI_Config.json
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
<img src="http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Orianna_0.jpg" class="dd-icon dd-loading" alt="Orianna">
<img src="http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Orianna_7.jpg" class="dd-icon dd-loading" alt="Orianna">
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
<img src="http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3132.png" class="dd-icon dd-item" alt="3132">
<img src="http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3126.png" class="dd-icon dd-item" alt="3126">
<img src="http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3138.png" class="dd-icon dd-item" alt="3138">
```

**Render**:

![Heart of Gold](http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3132.png)
![Madred's Bloodrazor](http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3126.png)
![Leviathan](http://ddragon.leagueoflegends.com/cdn/0.151.2/img/item/3138.png)

For more, please see [the wiki pages](https://github.com/dolejska-daniel/riot-api/wiki/DataDragonAPI:-How-to-begin).