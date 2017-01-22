<?php

/**
 * Copyright (C) 2016  Daniel Dolejška
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace RiotAPI;

use RiotAPI\Exceptions\APILimitException;
use RiotAPI\Objects;
use RiotAPI\Definitions\IPlatform;
use RiotAPI\Definitions\Platform;
use RiotAPI\Definitions\IRegion;
use RiotAPI\Definitions\Region;
use RiotAPI\Objects\ProviderRegistrationParameters;
use RiotAPI\Objects\TournamentCodeParameters;
use RiotAPI\Objects\TournamentRegistrationParameters;


/**
 *   Class RiotAPI
 *
 * @package RiotAPI
 */
class RiotAPI
{
	/** Constants for cURL requests. */
	const
		METHOD_GET      = 'GET',
		METHOD_POST     = 'POST',
		METHOD_PUT      = 'PUT',
		METHOD_DELETE   = 'DELETE';

	/** Settings constants. */
	const
		SET_REGION         = 'region',
		SET_PLATFORM       = 'platform', // Set internally by setting region
		SET_KEY            = 'api_key', // API key used by default
		SET_TOURNAMENT_KEY = 'api_key:tournament', // API key used when working with tournaments
		SET_API_BASEURL    = 'api_url';


	/**
	 * Contains library settings.
	 *
	 * @var $settings array
	 */
	protected $settings = array(
		self::SET_KEY            => null,
		self::SET_TOURNAMENT_KEY => null,
		self::SET_API_BASEURL    => '.api.pvp.net',
		self::SET_REGION         => Region::EUROPE_EAST,
		self::SET_PLATFORM       => Platform::EUROPE_EAST,
	);

	/** @var $used_key string */
	protected $used_key = self::SET_KEY;

	/** @var $endpoint string */
	protected $endpoint;

	/** @var $query_data array */
	protected $query_data = array();

	/** @var $post_data array */
	protected $post_data = array();

	/** @var $result_data array */
	protected $result_data;

	/** @var $regions IRegion */
	protected $regions;

	/** @var $platforms IPlatform */
	protected $platforms;


	/**
	 *   RiotAPI constructor.
	 *
	 * @param array     $settings
	 * @param IRegion   $custom_regionDataProvider
	 * @param IPlatform $custom_platformDataProvider
	 *
	 * @throws Exceptions\GeneralException
	 */
	public final function __construct( array $settings, IRegion $custom_regionDataProvider = null, IPlatform $custom_platformDataProvider = null )
	{
		$required_settings = [
			self::SET_KEY,
			self::SET_REGION,
		];

		//  Checks if required settings are present
		foreach ($required_settings as $key)
			if (array_search($key, array_keys($settings), true) === false)
				throw new Exceptions\GeneralException("Required settings parameter '$key' was not specified!");

		$allowed_settings = [
			self::SET_KEY,
			self::SET_REGION,
		];

		//  Assigns allowed settings
		foreach ($allowed_settings as $key)
			if (array_search($key, array_keys($settings), true) !== false)
				$this->settings[$key] = $settings[$key];

		$this->regions = $custom_regionDataProvider
			? $custom_regionDataProvider
			: new Region();

		$this->platforms = $custom_platformDataProvider
			? $custom_platformDataProvider
			: new Platform();

		$this->settings[self::SET_PLATFORM] = $this->platforms->getPlatform($settings[self::SET_REGION]);
	}


	/**
	 *   Sets new region to be used on API calls.
	 *
	 * @param string $region
	 *
	 * @return RiotAPI
	 */
	public function setRegion( string $region ): self
	{
		$this->settings[self::SET_REGION] = $region;
		return $this;
	}

	/**
	 *   Sets API key type for next API call.
	 *
	 * @param string $keyType
	 *
	 * @return RiotAPI
	 */
	protected function useKey( string $keyType ): self
	{
		$this->used_key = $keyType;
		return $this;
	}

	/**
	 *   Sets call target for script.
	 *
	 * @param $endpoint
	 *
	 * @return RiotAPI
	 */
	protected function setEndpoint( $endpoint ): self
	{
		$this->endpoint = $endpoint;
		return $this;
	}

	/**
	 *   Adds GET parameter to called URL.
	 *
	 * @param $name
	 * @param $value
	 *
	 * @return RiotAPI
	 */
	protected function addQuery( $name, $value ): self
	{
		if ($value !== null)
			$this->query_data[$name] = $value;

		return $this;
	}

	/**
	 *   Sets POST/PUT data.
	 *
	 * @param array|\Traversable $data
	 *
	 * @return RiotAPI
	 */
	protected function setData( $data ): self
	{
		$this->post_data = $data;
		return $this;
	}

	/**
	 *   Adds POST/PUT data to array.
	 *
	 * @param $name
	 * @param $value
	 *
	 * @return RiotAPI
	 */
	protected function addData( $name, $value ): self
	{
		$this->post_data[$name] = $value;
		return $this;
	}

	/**
	 *   Makes call to RiotAPI.
	 *
	 * @param string $override_region
	 * @param string $method
	 *
	 * @throws Exceptions\APIException
	 * @throws Exceptions\GeneralException
	 */
	final protected function makeCall( $override_region = null, $method = self::METHOD_GET )
	{
		$url_regionPart = $this->regions->getRegion($override_region ? $override_region : $this->settings[self::SET_REGION]);

		if (strpos($url_regionPart, 'http') !== false)
		{
			//  This region has it's own - custom address (either http or https)
			$url = $url_regionPart;
		}
		else
		{
			$url = "https://" . $url_regionPart . $this->settings[self::SET_API_BASEURL];
		}

		$url.= $this->endpoint . "?api_key=" . $this->settings[$this->used_key]
			. ( !empty($this->query_data) ? '&' . http_build_query($this->query_data) : '' );

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//  If you're having problems with API requests (mainly on localhost)
		//  change this cURL option to false
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if ($method == self::METHOD_GET)
		{
			curl_setopt($ch, CURLOPT_URL, $url);
		}
		elseif($method == self::METHOD_POST)
		{
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
				http_build_query($this->post_data));
		}
		elseif($method == self::METHOD_PUT)
		{
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_PUT, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
				http_build_query($this->post_data));
		}
		elseif($method == self::METHOD_DELETE)
		{
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}
		else
			throw new Exceptions\GeneralException('Invalid method selected');

		$response = curl_exec($ch);
		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($response_code == 500)
		{
			throw new Exceptions\APIException('Internal server error');
		}
		elseif ($response_code == 429)
		{
			throw new Exceptions\APIException('Rate limit exceeded');
		}
		elseif ($response_code == 403)
		{
			throw new Exceptions\APIException('Forbidden');
		}
		elseif ($response_code == 400)
		{
			throw new Exceptions\APIException('Bad request');
		}

		$this->result_data  = json_decode($response, true);
		$this->query_data   = array();
		$this->post_data    = array();
		$this->used_key     = self::SET_KEY;

		curl_close($ch);

		if (isset($this->result_data->status->message) && !empty($this->result_data->status->message))
			throw new Exceptions\APIException($this->result_data->status->message, $this->result_data->status->status_code);
	}

	/**
	 *   Returns result data from call.
	 *
	 * @return mixed
	 */
	protected function result()
	{
		return $this->result_data;
	}


	/****************************************d*d*
	 *
	 *  Available API methods
	 *
	 * @link https://developer.riotgames.com/api/methods
	 *
	 ********************************************/

	/**
	 *  Champion Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1206
	 **/
	const ENDPOINT_VERSION_CHAMPION = 'v1.2';

	/**
	 *   Retrieve all champions.
	 *
	 * @param bool|false $only_free_to_play
	 *
	 * @return Objects\ChampionListDto
	 * @link https://developer.riotgames.com/api/methods#!/1206/4678
	 */
	public function getChampions( bool $only_free_to_play = false ): Objects\ChampionListDto
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_CHAMPION . "/champion")
			->addQuery("freeToPlay", $only_free_to_play)
			->makeCall();

		return new Objects\ChampionListDto($this->result());
	}

	/**
	 *   Retrieve champion by ID.
	 *
	 * @param $champion_id
	 *
	 * @return Objects\ChampionDto
	 * @link https://developer.riotgames.com/api/methods#!/1206/4677
	 */
	public function getChampion( int $champion_id ): Objects\ChampionDto
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_CHAMPION . "/champion/{$champion_id}")
			->makeCall();

		return new Objects\ChampionDto($this->result());
	}

	/**
	 *   Champion Mastery Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1091
	 **/

	/**
	 *   Get a champion mastery by player id and champion id.
	 *
	 * @param int $summoner_id
	 * @param int $champion_id
	 *
	 * @return Objects\ChampionMasteryDto
	 * @link https://developer.riotgames.com/api/methods#!/1091/3769
	 */
	public function getChampionMastery( int $summoner_id, int $champion_id ): Objects\ChampionMasteryDto
	{
		$this->setEndpoint("/championmastery/location/{$this->settings[self::SET_PLATFORM]}/player/{$summoner_id}/champion/{$champion_id}")
			->makeCall();

		return new Objects\ChampionMasteryDto($this->result());
	}

	/**
	 *   Get all champion mastery entries sorted by number of champion points descending.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\ChampionMasteryDto[]
	 * @link https://developer.riotgames.com/api/methods#!/1091/3768
	 */
	public function getChampionMasteryList( int $summoner_id ): array
	{
		$this->setEndpoint("/championmastery/location/{$this->settings[self::SET_PLATFORM]}/player/{$summoner_id}/champions")
			->makeCall();

		$r = array();
		foreach ($this->result() as $ident => $data)
			$r[$ident] = new Objects\ChampionMasteryDto($data);

		return $r;
	}

	/**
	 *   Get a player's total champion mastery score, which is sum of individual champion mastery levels.
	 *
	 * @param int $summoner_id
	 *
	 * @return int
	 * @link https://developer.riotgames.com/api/methods#!/1091/3770
	 */
	public function getChampionMasteryScore( int $summoner_id ): int
	{
		$this->setEndpoint("/championmastery/location/{$this->settings[self::SET_PLATFORM]}/player/{$summoner_id}/score")
			->makeCall();

		return $this->result();
	}

	/**
	 *   Get specified number of top champion mastery entries sorted by number of champion points descending.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\ChampionMasteryDto[]
	 * @link https://developer.riotgames.com/api/methods#!/1091/3764
	 */
	public function getChampionMasteryTopList( int $summoner_id ): array
	{
		$this->setEndpoint("/championmastery/location/{$this->settings[self::SET_PLATFORM]}/player/{$summoner_id}/topchampions")
			->makeCall();

		$r = array();
		foreach ($this->result() as $ident => $data)
			$r[$ident] = new Objects\ChampionMasteryDto($data);

		return $r;
	}

	/**
	 *  Current Game Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/976
	 **/
	const ENDPOINT_VERSION_CURRENTGAME = 'v1.0';

	/**
	 *   Get current game information for the given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\CurrentGameInfo
	 * @link https://developer.riotgames.com/api/methods#!/976/3336
	 */
	public function getCurrentGame( int $summoner_id ): Objects\CurrentGameInfo
	{
		$this->setEndpoint("/observer-mode/rest/consumer/getSpectatorGameInfo/{$this->platforms->getPlatform($this->settings[self::SET_REGION])}/{$summoner_id}")
			->makeCall();

		return new Objects\CurrentGameInfo($this->result());
	}

	/**
	 *   Featured Games Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/977
	 **/
	const ENDPOINT_VERSION_FEATUREDGAMES = 'v1.0';

	/**
	 *   Get list of featured games.
	 *
	 * @return Objects\FeaturedGames
	 * @link https://developer.riotgames.com/api/methods#!/977/3337
	 */
	public function getFeaturedGames(): Objects\FeaturedGames
	{
		$this->setEndpoint("/observer-mode/rest/featured")
			->makeCall();

		return new Objects\FeaturedGames($this->result());
	}

	/**
	 *  Game Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1207
	 **/
	const ENDPOINT_VERSION_GAME = 'v1.3';

	/**
	 *   Get recent games by summoner ID.
	 *
	 * @param $summoner_id
	 *
	 * @return Objects\RecentGamesDto
	 * @link https://developer.riotgames.com/api/methods#!/1207/4679
	 */
	public function getRecentGames( int $summoner_id ): Objects\RecentGamesDto
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_GAME . "/game/by-summoner/{$summoner_id}/recent")
			->makeCall();

		return new Objects\RecentGamesDto($this->result());
	}

	/**
	 *  League Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1215
	 **/
	const ENDPOINT_VERSION_LEAGUE = 'v2.5';

	/**
	 *   Get leagues mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param int|array $summoner_ids
	 *
	 * @return Objects\LeagueDto[]
	 * @throws APILimitException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1215/4701
	 */
	public function getLeagueMappingBySummoner( $summoner_ids ): array
	{
		if (is_array($summoner_ids))
		{
			if (count($summoner_ids) > 10)
				throw new APILimitException("Maximum allowed summoner id count is 10.");

			$summoner_ids = implode(',', $summoner_ids);
		}

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_LEAGUE . "/league/by-summoner/{$summoner_ids}")
			->makeCall();

		$r = array();
		foreach ($this->result() as $summoner_id => $leaguesData)
		{
			$leagues = array();
			foreach ($leaguesData as $ident => $data)
				$leagues[$ident] = new Objects\LeagueDto($data);

			$r[$summoner_id] = $leagues;
		}

		return $r;
	}

	/**
	 *   Get league entries mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param int|array $summoner_ids
	 *
	 * @return Objects\LeagueDto[]
	 * @throws APILimitException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1215/4705
	 */
	public function getLeagueEntryBySummoner( $summoner_ids ): array
	{
		if (is_array($summoner_ids))
		{
			if (count($summoner_ids) > 10)
				throw new APILimitException("Maximum allowed summoner id count is 10.");

			$summoner_ids = implode(',', $summoner_ids);
		}

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_LEAGUE . "/league/by-summoner/{$summoner_ids}/entry")
			->makeCall();

		$r = array();
		foreach ($this->result() as $summoner_id => $leaguesData)
		{
			$leagues = array();
			foreach ($leaguesData as $ident => $data)
				$leagues[$ident] = new Objects\LeagueDto($data);

			$r[$summoner_id] = $leagues;
		}

		return $r;
	}

	/**
	 *   Get challenger tier leagues.
	 *
	 * @param string $game_queue_type
	 *
	 * @return Objects\LeagueDto
	 * @link https://developer.riotgames.com/api/methods#!/1215/4704
	 */
	public function getLeagueMappingChallenger( string $game_queue_type ): Objects\LeagueDto
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_LEAGUE . "/league/challenger")
			->addQuery("type", $game_queue_type)
			->makeCall();

		return new Objects\LeagueDto($this->result());
	}

	/**
	 *   Get master tier leagues.
	 *
	 * @param string $game_queue_type
	 *
	 * @return Objects\LeagueDto
	 * @link https://developer.riotgames.com/api/methods#!/1215/4706
	 */
	public function getLeagueMappingMaster( string $game_queue_type ): Objects\LeagueDto
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_LEAGUE . "/league/master")
			->addQuery("type", $game_queue_type)
			->makeCall();

		return new Objects\LeagueDto($this->result());
	}

	/**
	 *   LoL Static Data Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1055
	 **/
	const ENDPOINT_VERSION_STATICDATA = 'v1.2';

	/**
	 *   Retrieves champion list
	 *
	 * @param bool|false $locale
	 * @param bool|false $version
	 * @param bool|false $data_by_id
	 * @param bool|false $champ_data
	 *
	 * @return mixed
	 */
	public function getStaticChampions( $locale = false, $version = false, $data_by_id = false, $champ_data = false )
	{
		if (!is_array($champ_data))
			$champ_data = [$champ_data];
		$champ_data = implode(',', $champ_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/champion")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("dataById", $data_by_id)
			->addQuery("champData", $champ_data)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves a champion by its ID
	 *
	 * @param            $champion_id
	 * @param bool|false $locale
	 * @param bool|false $version
	 * @param bool|false $champ_data
	 *
	 * @return mixed
	 */
	public function getStaticChampion( $champion_id, $locale = false, $version = false, $champ_data = false )
	{
		if (!is_array($champ_data))
			$champ_data = [$champ_data];
		$champ_data = implode(',', $champ_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/champion/{$champion_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("champData", $champ_data)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves item list
	 *
	 * @param bool|false $locale
	 * @param bool|false $version
	 * @param bool|false $item_list_data
	 *
	 * @return mixed
	 */
	public function getStaticItems( $locale = false, $version = false, $item_list_data = false )
	{
		if (!is_array($item_list_data))
			$item_list_data = [$item_list_data];
		$item_list_data = implode(',', $item_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/item")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("itemListData", $item_list_data)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves item by its unique ID
	 *
	 * @param            $item_id
	 * @param bool|false $locale
	 * @param bool|false $version
	 * @param bool|false $item_list_data
	 *
	 * @return mixed
	 */
	public function getStaticItem( $item_id, $locale = false, $version = false, $item_list_data = false )
	{
		if (!is_array($item_list_data))
			$item_list_data = [$item_list_data];
		$item_list_data = implode(',', $item_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/item/{$item_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("itemListData", $item_list_data)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieve language strings data
	 *
	 * @param bool|false $locale
	 * @param bool|false $version
	 *
	 * @return mixed
	 */
	public function getLanguageStrings( $locale = false, $version = false )
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/language-strings")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieve supported languages data
	 *
	 * @return mixed
	 */
	public function getLanguages()
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/languages")
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieve map data
	 *
	 * @param bool|false $locale
	 * @param bool|false $version
	 *
	 * @return mixed
	 */
	public function getMaps( $locale = false, $version = false )
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/map")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves mastery list
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $mastery_list_data
	 *
	 * @return mixed
	 */
	public function getMasteries( string $locale = null, string $version = null, $mastery_list_data = null )
	{
		if (!is_array($mastery_list_data))
			$mastery_list_data = [$mastery_list_data];
		$mastery_list_data = implode(',', $mastery_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/mastery")
			->addQuery("locale", $locale ?: false)
			->addQuery("version", $version ?: false)
			->addQuery("masteryListData", $mastery_list_data ?: false)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves mastery item by its unique ID
	 *
	 * @param int          $mastery_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $mastery_list_data
	 *
	 * @return mixed
	 * @link https://developer.riotgames.com/api/methods#!/1055/3626
	 */
	public function getMastery( int $mastery_id, string $locale = null, string $version = null, $mastery_list_data = null )
	{
		if (!is_array($mastery_list_data))
			$mastery_list_data = [$mastery_list_data];
		$mastery_list_data = implode(',', $mastery_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/mastery/{$mastery_id}")
			->addQuery("locale", $locale ?: false)
			->addQuery("version", $version ?: false)
			->addQuery("masteryListData", $mastery_list_data ?: false)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieve realm data
	 *
	 * @return mixed
	 * @link https://developer.riotgames.com/api/methods#!/1055/3632
	 */
	public function getRealm()
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/realm")
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves rune list
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $rune_list_data
	 *
	 * @return mixed
	 * @link https://developer.riotgames.com/api/methods#!/1055/3623
	 */
	public function getRunes( string $locale = null, string $version = null, $rune_list_data = null )
	{
		if (!is_array($rune_list_data))
			$rune_list_data = [$rune_list_data];
		$rune_list_data = implode(',', $rune_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/rune")
			->addQuery("locale", $locale ?: false)
			->addQuery("version", $version ?: false)
			->addQuery("runeListData", $rune_list_data ?: false)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves rune by its unique ID
	 *
	 * @param int          $rune_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $rune_list_data
	 *
	 * @return mixed
	 * @link https://developer.riotgames.com/api/methods#!/1055/3629
	 */
	public function getRune( int $rune_id, string $locale = null, string $version = null, $rune_list_data = null )
	{
		if (!is_array($rune_list_data))
			$rune_list_data = [$rune_list_data];
		$rune_list_data = implode(',', $rune_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/rune/{$rune_id}")
			->addQuery("locale", $locale ?: false)
			->addQuery("version", $version ?: false)
			->addQuery("runeListData", $rune_list_data ?: false)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves summoner spell list
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param bool         $data_by_id
	 * @param string|array $spell_data
	 *
	 * @return mixed
	 * @link https://developer.riotgames.com/api/methods#!/1055/3634
	 */
	public function getSummonerSpells( string $locale = null, string $version = null, bool $data_by_id = false, $spell_data = null )
	{
		if (!is_array($spell_data))
			$spell_data = [$spell_data];
		$spell_data = implode(',', $spell_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/summoner-spell")
			->addQuery("locale", $locale ?: false)
			->addQuery("version", $version ?: false)
			->addQuery("dataById", $data_by_id ?: false)
			->addQuery("spellData", $spell_data ?: false)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieves summoner spell by its unique ID
	 *
	 * @param int    $summoner_spell_id
	 * @param string $locale
	 * @param string $version
	 * @param false  $spell_data
	 *
	 * @return mixed
	 * @link https://developer.riotgames.com/api/methods#!/1055/3628
	 */
	public function getSummonerSpell( int $summoner_spell_id, string $locale = null, string $version = null, $spell_data = null )
	{
		if (!is_array($spell_data))
			$spell_data = [$spell_data];
		$spell_data = implode(',', $spell_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/summoner-spell/{$summoner_spell_id}")
			->addQuery("locale", $locale ?: false)
			->addQuery("version", $version ?: false)
			->addQuery("spellData", $spell_data ?: false)
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   Retrieve version data
	 *
	 * @return mixed
	 * @link https://developer.riotgames.com/api/methods#!/1055/3630
	 */
	public function getVersions()
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/versions")
			->makeCall(Region::GLOBAL);

		return $this->result();
	}

	/**
	 *   LoL Status Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1085
	 **/
	const ENDPOINT_VERSION_STATUS = 'v1';

	/**
	 *   Get shard list.
	 *
	 * @return Objects\Shard[]
	 * @link https://developer.riotgames.com/api/methods#!/1085/3740
	 */
	public function getShards(): array
	{
		$this->setEndpoint("/lol/status/" . self::ENDPOINT_VERSION_STATUS . "/shards")
			->makeCall();

		$r = array();
		foreach ($this->result() as $ident => $data)
			$r[$ident] = new Objects\Shard($data);

		return $r;
	}

	/**
	 *   Get shard status, returns the data available on the status.leagueoflegends.com website for the given region.
	 *
	 * @param string $region
	 *
	 * @return Objects\ShardStatus
	 * @link https://developer.riotgames.com/api/methods#!/1085/3739
	 */
	public function getShardInfo( string $region ): Objects\ShardStatus
	{
		$this->setEndpoint("/lol/status/" . self::ENDPOINT_VERSION_STATUS . "/shard")
			->makeCall($region);

		return new Objects\ShardStatus($this->result());
	}

	/**
	 *   Match Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1224
	 **/
	const ENDPOINT_VERSION_MATCH = 'v2.2';

	/**
	 *   Retrieve match by match ID.
	 *
	 * @param int        $match_id
	 * @param bool|false $include_timeline
	 *
	 * @return Objects\MatchDetail
	 * @link https://developer.riotgames.com/api/methods#!/1224/4756
	 */
	public function getMatch( int $match_id, bool $include_timeline = false ): Objects\MatchDetail
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_MATCH . "/match/{$match_id}")
			->addQuery('includeTimeline', $include_timeline)
			->makeCall();

		return new Objects\MatchDetail($this->result());
	}

	public function getTournamentMatch( int $match_id, string $tournament_code, bool $include_timeline = false )
	{
		return false;

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_MATCH . "/match/for-tournament/{$match_id}")
			->addQuery('tournamentCode', $tournament_code)
			->addQuery('includeTimeline', $include_timeline)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall();

		return $this->result();
	}

	public function getTournamentMatchIds( string $tournament_code )
	{
		return false;

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_MATCH . "/match/by-tournament/{$tournament_code}/ids")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall();

		return $this->result();
	}

	/**
	 *   Matchlist Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1223
	 **/
	const ENDPOINT_VERSION_MATCHLIST = 'v2.2';

	/**
	 *   Retrieve match list by summoner ID.
	 *
	 * @param int        $summoner_id
	 * @param array|null $champion_ids
	 * @param array|null $ranked_queues
	 * @param array|null $seasons
	 * @param int|null   $begin_time
	 * @param int|null   $end_time
	 * @param int|null   $begin_index
	 * @param int|null   $end_index
	 *
	 * @return Objects\MatchList
	 * @link https://developer.riotgames.com/api/methods#!/1223/4754
	 */
	public function getMatchlist( int $summoner_id, array $champion_ids = null, array $ranked_queues = null, array $seasons = null, int $begin_time = null, int $end_time = null, int $begin_index = null, int $end_index = null ): Objects\MatchList
	{
		if (!is_null($champion_ids))
			$champion_ids = implode(',', $champion_ids);

		if (!is_null($ranked_queues))
			$ranked_queues = implode(',', $ranked_queues);

		if (!is_null($seasons))
			$seasons = implode(',', $seasons);

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_MATCHLIST . "/matchlist/by-summoner/{$summoner_id}")
			->addQuery('championIds', $champion_ids)
			->addQuery('rankedQueues', $ranked_queues)
			->addQuery('seasons', $seasons)
			->addQuery('beginTime', $begin_time)
			->addQuery('endTime', $end_time)
			->addQuery('beginIndex', $begin_index)
			->addQuery('endIndex', $end_index)
			->makeCall();

		return new Objects\MatchList($this->result());
	}

	/**
	 *  Stats Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1209
	 **/
	const ENDPOINT_VERSION_STATS = 'v1.3';

	/**
	 *   Get ranked stats by summoner ID.
	 *
	 * @param int         $summoner_id
	 * @param string|null $season
	 *
	 * @return Objects\RankedStatsDto
	 * @link https://developer.riotgames.com/api/methods#!/1209/4686
	 */
	public function getRankedStats( int $summoner_id, string $season = null ): Objects\RankedStatsDto
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATS . "/stats/by-summoner/{$summoner_id}/ranked")
			->addQuery('season', $season)
			->makeCall();

		return new Objects\RankedStatsDto($this->result());
	}

	/**
	 *   Get player stats summaries by summoner ID.
	 *
	 * @param int         $summoner_id
	 * @param string|null $season
	 *
	 * @return Objects\PlayerStatsSummaryListDto
	 * @link https://developer.riotgames.com/api/methods#!/1209/4687
	 */
	public function getSummaryStats( int $summoner_id, string $season = null ): Objects\PlayerStatsSummaryListDto
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATS . "/stats/by-summoner/{$summoner_id}/summary")
			->addQuery('season', $season)
			->makeCall();

		return new Objects\PlayerStatsSummaryListDto($this->result());
	}

	/**
	 *   Summoner Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1221
	 **/
	const ENDPOINT_VERSION_SUMMONER = 'v1.4';

	/**
	 *   Get summoner objects mapped by standardized summoner name for a given list of summoner names.
	 *
	 * @param string|array $summoner_names
	 *
	 * @return Objects\SummonerDto[]
	 * @throws APILimitException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1221/4746
	 */
	public function getSummonerByName( $summoner_names ): array
	{
		if (is_array($summoner_names))
		{
			if (count($summoner_names) > 40)
				throw new APILimitException("Maximum allowed summoner name count is 40.");

			$summoner_names = implode(',', $summoner_names);
		}

		//  Remove all spaces
		$summoner_names = preg_replace('/[\s-]+/', '', strtolower($summoner_names));

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/by-name/{$summoner_names}")
			->makeCall();

		$r = array();
		foreach ($this->result() as $summoner_name => $data)
			$r[$summoner_name] = new Objects\SummonerDto($data);

		return $r;
	}

	/**
	 *   Get summoner objects mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param int|array $summoner_ids
	 *
	 * @return Objects\SummonerDto[]
	 * @throws APILimitException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1221/4745
	 */
	public function getSummoner( $summoner_ids ): array
	{
		if (is_array($summoner_ids))
		{
			if (count($summoner_ids) > 40)
				throw new APILimitException("Maximum allowed summoner id count is 40.");

			$summoner_ids = implode(',', $summoner_ids);
		}

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/{$summoner_ids}")
			->makeCall();

		$r = array();
		foreach ($this->result() as $summoner_id => $data)
			$r[$summoner_id] = new Objects\SummonerDto($data);

		return $r;
	}

	/**
	 *   Get mastery pages mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param int|array $summoner_ids
	 *
	 * @return Objects\MasteryPagesDto[]
	 * @throws APILimitException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1208/4683
	 */
	public function getSummonerMasteries( $summoner_ids ): array
	{
		if (is_array($summoner_ids))
		{
			if (count($summoner_ids) > 40)
				throw new APILimitException("Maximum allowed summoner id count is 40.");

			$summoner_ids = implode(',', $summoner_ids);
		}

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/{$summoner_ids}/masteries")
			->makeCall();

		$r = array();
		foreach ($this->result() as $summoner_id => $data)
			$r[$summoner_id] = new Objects\MasteryPagesDto($data);

		return $r;
	}

	/**
	 *   Get summoner names mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param int|array $summoner_ids
	 *
	 * @return array
	 * @throws APILimitException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1208/4685
	 */
	public function getSummonerName( $summoner_ids ): array
	{
		if (is_array($summoner_ids))
		{
			if (count($summoner_ids) > 40)
				throw new APILimitException("Maximum allowed summoner id count is 40.");

			$summoner_ids = implode(',', $summoner_ids);
		}

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/{$summoner_ids}/name")
			->makeCall();

		return $this->result();
	}

	/**
	 *   Get rune pages mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param int|array $summoner_ids
	 *
	 * @return Objects\RunePagesDto[]
	 * @throws APILimitException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1208/4682
	 */
	public function getSummonerRunes( $summoner_ids ): array
	{
		if (is_array($summoner_ids))
		{
			if (count($summoner_ids) > 40)
				throw new APILimitException("Maximum allowed summoner id count is 40.");

			$summoner_ids = implode(',', $summoner_ids);
		}

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/{$summoner_ids}/runes")
			->makeCall();

		$r = array();
		foreach ($this->result() as $summoner_id => $data)
			$r[$summoner_id] = new Objects\RunePagesDto($data);

		return $r;
	}


	/**
	 *   Tournament Provider Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1090/3760
	 **/
	const ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB = 'v1';

	/**
	 *   Create a mock tournament code for the given tournament.
	 *
	 * @param int                      $tournament_id
	 * @param int                      $count
	 * @param TournamentCodeParameters $parameters
	 *
	 * @return string[]
	 * @link https://developer.riotgames.com/api/methods#!/1090/3760
	 */
	public function createTournamentCodes_STUB( int $tournament_id, int $count, TournamentCodeParameters $parameters ): array
	{
		$this->setEndpoint("/tournament/stub/" . self::ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB . "/code")
			->addQuery('tournamentId', $tournament_id)
			->addQuery('count', $count)
			->setData($parameters)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall( Region::GLOBAL, self::METHOD_POST );

		return $this->result();
	}

	/**
	 *   Creates a mock tournament provider and returns its ID.
	 *
	 * @param ProviderRegistrationParameters $parameters
	 *
	 * @return int
	 * @link https://developer.riotgames.com/api/methods#!/1090/3762
	 */
	public function createTournamentProvider_STUB( ProviderRegistrationParameters $parameters ): int
	{
		$this->setEndpoint("/tournament/stub/" . self::ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB . "/provider")
			->setData($parameters)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall( Region::GLOBAL, self::METHOD_POST );

		return $this->result();
	}

	/**
	 *   Creates a mock tournament and returns its ID.
	 *
	 * @param TournamentRegistrationParameters $parameters
	 *
	 * @return int
	 * @link https://developer.riotgames.com/api/methods#!/1090/3763
	 */
	public function createTournament_STUB( TournamentRegistrationParameters $parameters ): int
	{
		$this->setEndpoint("/tournament/stub/" . self::ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB . "/tournament")
			->setData($parameters)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall( Region::GLOBAL, self::METHOD_POST );

		return $this->result();
	}

	/**
	 *   Gets a mock list of lobby events by tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\LobbyEventDTOWrapper
	 * @link https://developer.riotgames.com/api/methods#!/1090/3761
	 */
	public function getTournamentLobbyEvents_STUB( string $tournament_code ): Objects\LobbyEventDTOWrapper
	{
		$this->setEndpoint("/tournament/stub/" . self::ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB . "/lobby/events/by-code/{$tournament_code}")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall( Region::GLOBAL );

		return new Objects\LobbyEventDTOWrapper($this->result());
	}
}