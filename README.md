# RiotAPI PHP7 wrapper

Welcome to the RiotAPI PHP7 library repo! The goal of this library is to create easy-to-use
library for anyone who might need one.

I would be grateful for any feedback, so if you can give me any, let's do it! Feel free
to send pull requests if you find anything that is worth improving!

Please, read on :)

_MORE TBA_

## League of Legends API
This is fully object oriented API wrapper for League of Legends. Here are some features:

- Rate limit caching and exceeding prevention
- Objects!

How to begin?

```php
use RiotAPI\Definitions;
use RiotAPI\RiotAPI;

//  Include all required files
require_once "./src/RiotAPI/loader.php";

//  Initialize the library
$api = new RiotAPI([
	//  Your API key, you can get one at https://developer.riotgames.com/
	RiotAPI::SET_KEY             => 'YOUR_RIOT_API_KEY',
	//  Target region (you can change it during lifetime of the library)
	RiotAPI::SET_REGION          => Definitions\Region::EUROPE_EAST,
	//  Whether or not to cache keys' rate limits and prevent exceeding the rate limit
	RiotAPI::SET_CACHE_RATELIMIT => true,
]);

//  And now you are ready to go!
```

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
- Calls on static data endpoint returns only JSON decoded response (no custom object)
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
- getLeagueMappingBySummoner
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
- getShardInfo

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
- getSummonerByName
- getSummoner
- getSummonerMasteries
- getSummonerName
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