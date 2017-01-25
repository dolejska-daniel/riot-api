# RiotAPI PHP7 wrapper

> Version 0.1

[![build status](https://gitlab.dolejska.me/dolejskad/riot-api/badges/master/build.svg)](https://gitlab.dolejska.me/dolejskad/riot-api/commits/master)
[![coverage report](https://gitlab.dolejska.me/dolejskad/riot-api/badges/master/coverage.svg)](https://gitlab.dolejska.me/dolejskad/riot-api/commits/master)

Welcome to the RiotAPI PHP7 library repo! The goal of this library is to create easy-to-use
library for anyone who might need one.

I would be grateful for any feedback, so if you can give me any, let's do it! Feel free
to send pull requests if you find anything that is worth improving!

Please, read on :)

_MORE TBA_

## League of Legends API

This is fully object oriented API wrapper for League of Legends. Here are some features:

- Rate limit caching and exceeding prevention
- Objects everywhere! API calls return data in special objects

How to begin?

```php
use RiotAPI\Definition;
use RiotAPI\RiotAPI;

//  Include all required files
require_once "./vendor/autoload.php";

//  Initialize the library
$api = new RiotAPI([
	//  Your API key, you can get one at https://developer.riotgames.com/
	RiotAPI::SET_KEY             => 'YOUR_RIOT_API_KEY',
	//  Target region (you can change it during lifetime of the library)
	RiotAPI::SET_REGION          => Definition\Region::EUROPE_EAST,
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

//  And now you are ready to go!
```

And there is a lot more, you can set when initializing the library, here is a complete list:

- `RiotAPI::SET_REGION` -- this specifies on which region endpoints we are working
  - `Definition\Region::EUROPE_EAST`
  - `Definition\Region::EUROPE_WEST`
  - `Definition\Region::NORTH_AMERICA`
  - `Definition\Region::BRASIL`
  - `Definition\Region::RUSSIA`
  - …
- `RiotAPI::SET_KEY` -- this will set the default API key for all the calls
- `RiotAPI::SET_TOURNAMENT_KEY` -- with this, you can specify your tournament-endpoints-only API key
- `RiotAPI::SET_TOURNAMENT_INTERIM` -- by specifying this, you tell the library to use STUB endpoints (Tournament Applications in Interim state)
- `RiotAPI::SET_CACHE_RATELIMIT` -- this tells the library to take care of not exceeding your API key's rate limit
- `RiotAPI::SET_CACHE_CALLS` -- _not yet implemented_
- `RiotAPI::SET_CACHE_CALLS_LENGTH` -- _not yet implemented_
- `RiotAPI::SET_CACHE_PROVIDER` -- this option will allow you to either select from provided CacheProviders or to select your own
  - `RiotAPI::CACHE_PROVIDER_FILE`
  - `RiotAPI::CACHE_PROVIDER_MEMCACHED`
- `RiotAPI::SET_CACHE_PROVIDER_PARAMS` -- these are parameters, that will be passed to the CacheProvider on initialization

Working with RiotAPI can not be easier, just watch:
```php
//  Fetches the summoner data (returns list of SummonerDto objects)
$summoners = $api->getSummonerByName('I am TheKronnY');
//  Let's get the first one (the only one there is)
$summoner = reset($summoners);

echo $summoner->id;             //  Outputs: 30904166
echo $summoner->name;           //  Outputs: I am TheKronnY
echo $summoner->summonerLevel;  //  Outputs: 30

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

### Known problems
- Non-STUB TournamentProvider endpoint functions are not yet implemented
- No request caching

### API Methods

Below you can find list of methods by endpoints with usage examples.

#### Champion

Available methods:
- getChampions
- getChampion

#### ChampionMastery

Available methods:
- getChampionMastery
- getChampionMasteryList
- getChampionMasteryScore
- getChampionMasteryTopList

#### CurrentGame

Available methods:
- getCurrentGame

#### FeaturedGames

Available methods:
- getFeaturedGames

#### Game

Available methods:
- getRecentGames

#### League

Available methods:
- getLeagueMappingBySummoners
- getLeagueMappingBySummoner
- getLeagueEntryBySummoners
- getLeagueEntryBySummoner
- getLeagueMappingChallenger
- getLeagueMappingMaster

#### StaticData

Available methods:
- getStaticChampions
- getStaticChampion
- getStaticItems
- getStaticItem
- getLanguageStrings
- getLanguages
- getMaps
- getMasteries
- getMastery
- getRealm
- getRunes
- getRune
- getSummonerSpells
- getSummonerSpell
- getVersions

#### Status

Available methods:
- getShards
- getShardStatus

#### Match

Available methods:
- getMatch
- _getTournamentMatch_
- _getTournamentMatchIds_

#### MatchList

Available methods:
- getMatchlist

#### Stats

Available methods:
- getRankedStats
- getSummaryStats

#### Summoner

Available methods:
- getSummonersByName
- getSummonerByName
- getSummoners
- getSummoner
- getSummonersMasteries
- getSummonerMasteries
- getSummonersNames
- getSummonerName
- getSummonersRunes
- getSummonerRunes

#### TournamentProvider

Available methods:
- _createTournamentCodes_
- _createTournamentProvider_
- _createTournament_
- _getTournamentLobbyEvents_

#### TournamentSTUB

Available methods:
- createTournamentCodes_STUB
- createTournamentProvider_STUB
- createTournament_STUB
- getTournamentLobbyEvents_STUB

## DataDragon API
_TBA_