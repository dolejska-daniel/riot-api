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

use RiotAPI\Definition\FileCacheProvider;
use RiotAPI\Definition\ICacheProvider;
use RiotAPI\Definition\IPlatform;
use RiotAPI\Definition\MemcachedCacheProvider;
use RiotAPI\Definition\Platform;
use RiotAPI\Definition\IRegion;
use RiotAPI\Definition\Region;
use RiotAPI\Definition\IRateLimitControl;
use RiotAPI\Definition\RateLimitControl;

use RiotAPI\Objects;
use RiotAPI\Objects\ProviderRegistrationParameters;
use RiotAPI\Objects\TournamentCodeParameters;
use RiotAPI\Objects\TournamentRegistrationParameters;
use RiotAPI\Objects\StaticData;

use RiotAPI\Exception\GeneralException;
use RiotAPI\Exception\RequestException;
use RiotAPI\Exception\RequestParameterException;
use RiotAPI\Exception\ServerException;
use RiotAPI\Exception\ServerLimitException;
use RiotAPI\Exception\SettingsException;


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
		SET_REGION                = 'SET_REGION',
		SET_PLATFORM              = 'SET_PLATFORM',              // Set internally by setting region
		SET_KEY                   = 'SET_KEY',                   // API key used by default
		SET_TOURNAMENT_KEY        = 'SET_TOURNAMENT_KEY',        // API key used when working with tournaments
		SET_TOURNAMENT_INTERIM    = 'SET_TOURNAMENT_INTERIM',    // Used to set whether your application is in Interim mode (Tournament STUB endpoints) or not,
		SET_CACHE_PROVIDER        = 'SET_CACHE_PROVIDER',        // Specifies CacheProvider class name
		SET_CACHE_PROVIDER_PARAMS = 'SET_CACHE_PROVIDER_PARAMS', // Specifies parameters passed to CacheProvider class when initializing
		SET_CACHE_RATELIMIT       = 'SET_CACHE_RATELIMIT',       // Used to set whether or not to save and check API key's rate limit
		SET_RATELIMITS            = 'SET_RATELIMITS',            // Specifies limits for provided API keys
		SET_CACHE_CALLS           = 'SET_CACHE_CALLS',           // Used to set whether or not to temporary save API call's results
		SET_CACHE_CALLS_LENGTH    = 'SET_CACHE_CALLS_LENGTH',    // Specifies for how long are call results saved
		SET_API_BASEURL           = 'SET_API_BASEURL',
		SET_USE_DUMMY_DATA        = 'SET_USE_DUMMY_DATA';

	const
		CACHE_PROVIDER_FILE      = FileCacheProvider::class,
		CACHE_PROVIDER_MEMCACHED = MemcachedCacheProvider::class;

	const
		CACHE_KEY_RLC = 'rate-limit.cache';

	const
		API_RATELIMIT_HEADER = 'X-Rate-Limit-Count';


	/**
	 *   Contains library settings.
	 *
	 * @var $settings array
	 */
	protected $settings = array(
		self::SET_API_BASEURL     => '.api.pvp.net',
	);

	/** @var IRegion $regions */
	public $regions;

	/** @var IPlatform $platforms */
	public $platforms;

	/** @var ICacheProvider $cache */
	protected $cache;

	/** @var IRateLimitControl $rate_limit_control */
	protected $rate_limit_control;


	/** @var string $used_key */
	protected $used_key = self::SET_KEY;

	/** @var string $endpoint */
	protected $endpoint;


	/** @var array $query_data */
	protected $query_data = array();

	/** @var array $post_data */
	protected $post_data;

	/** @var array $result_data */
	protected $result_data;


	/**
	 *   RiotAPI constructor.
	 *
	 * @param array     $settings
	 * @param IRegion   $custom_regionDataProvider
	 * @param IPlatform $custom_platformDataProvider
	 *
	 * @throws SettingsException
	 */
	public function __construct( array $settings, IRegion $custom_regionDataProvider = null, IPlatform $custom_platformDataProvider = null )
	{
		//  List of required setting keys
		$required_settings = [
			self::SET_KEY,
			self::SET_REGION,
		];

		//  Checks if required settings are present
		foreach ($required_settings as $key)
			if (array_search($key, array_keys($settings), true) === false)
				throw new SettingsException("Required settings parameter '$key' was not specified!");

		//  List of allowed setting keys
		$allowed_settings = array_merge([
			self::SET_TOURNAMENT_KEY,
			self::SET_TOURNAMENT_INTERIM,
			self::SET_CACHE_PROVIDER,
			self::SET_CACHE_PROVIDER_PARAMS,
			self::SET_CACHE_RATELIMIT,
			self::SET_RATELIMITS,
			self::SET_USE_DUMMY_DATA,
		], $required_settings);

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

		if ($this->getSetting(self::SET_CACHE_CALLS)
			|| $this->getSetting(self::SET_CACHE_RATELIMIT))
		{
			if ($this->isSettingSet(self::SET_CACHE_PROVIDER) == false)
			{
				//  Set default cache provider if not already set
				$this->setSettings([
					self::SET_CACHE_PROVIDER        => self::CACHE_PROVIDER_FILE,
					self::SET_CACHE_PROVIDER_PARAMS => [
						__DIR__ . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR,
					]
				]);
			}

			try
			{
				$cacheProvider = new \ReflectionClass($this->getSetting(self::SET_CACHE_PROVIDER));
				if ($cacheProvider->implementsInterface(ICacheProvider::class) == false)
					throw new SettingsException("Provided CacheProvider does not implement ICacheProvider interface.");

				$this->cache = $cacheProvider->newInstanceArgs($this->getSetting(self::SET_CACHE_PROVIDER_PARAMS, null));
			}
			catch (\ReflectionException $ex)
			{
				throw new SettingsException("Failed to initialize CacheProvider class: " . $ex->getMessage() . ".", 0, $ex);
			}
			catch (SettingsException $ex)
			{
				throw new SettingsException("CacheProvider class failed to be initialized: " . $ex->getMessage(), 0, $ex);
			}

			//  Caching API's rate limit headers
			$this->loadCache();

			$rateLimits = $this->getSetting(self::SET_RATELIMITS, [
				$this->getSetting(self::SET_KEY) => [
					IRateLimitControl::INTERVAL_10S => 10,
					IRateLimitControl::INTERVAL_10M => 500,
				],
			]);
			if (!$this->isSettingSet(self::SET_RATELIMITS) && $this->isSettingSet(self::SET_TOURNAMENT_KEY))
			{
				$rateLimits[$this->getSetting(self::SET_TOURNAMENT_KEY)] = [
					IRateLimitControl::INTERVAL_10S => 10,
					IRateLimitControl::INTERVAL_10M => 500,
				];
			}
			foreach ($rateLimits as $api_key => $limits)
			{
				if (!is_array($limits))
					throw new SettingsException("Rate limit settings are not in valid format.");

				$this->rate_limit_control->setLimits($api_key, $limits);
			}
		}

		$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($settings[self::SET_REGION]));
	}

	/**
	 *   RiotAPI destructor.
	 *
	 * Saves cache files (if needed) before destroying the object.
	 */
	public function __destruct()
	{
		$this->saveCache();
	}

	/**
	 *   Loads required cache objects
	 *
	 * @internal
	 */
	protected function loadCache()
	{
		if ($this->getSetting(self::SET_CACHE_RATELIMIT, false))
		{
			$rlc = $this->cache->load(self::CACHE_KEY_RLC);
			if (!$rlc)
				$rlc = new RateLimitControl($this->regions);

			$this->rate_limit_control = $rlc;
		}
	}

	/**
	 *   Saves required cache objects.
	 *
	 * @throws GeneralException
	 *
	 * @internal
	 */
	protected function saveCache()
	{
		if ($this->getSetting(self::SET_CACHE_RATELIMIT, false))
		{
			$this->cache->save(self::CACHE_KEY_RLC, $this->rate_limit_control, 600);
		}
	}

	/**
	 *   Returns vaue of requested key from settings.
	 *
	 * @param string     $name
	 * @param mixed|null $defaultValue
	 *
	 * @return mixed
	 */
	public function getSetting( string $name, $defaultValue = null )
	{
		return $this->isSettingSet($name)
			? $this->settings[$name]
			: $defaultValue;
	}

	/**
	 *   Sets new value for specified key in settings.
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return RiotAPI
	 *
	 */
	public function setSetting( string $name, $value ): self
	{
		$this->settings[$name] = $value;
		return $this;
	}

	/**
	 *   Sets new values for specified set of keys in settings.
	 *
	 * @param array $values
	 *
	 * @return RiotAPI
	 */
	public function setSettings( array $values ): self
	{
		foreach ($values as $name => $value)
			$this->setSetting($name, $value);
		return $this;
	}

	/**
	 *   Checks if specified settings key is set.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isSettingSet( string $name ): bool
	{
		return isset($this->settings[$name]) && !is_null($this->settings[$name]);
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
		return $this->setSetting(self::SET_REGION, $region);
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
	 * @param string $endpoint
	 *
	 * @return RiotAPI
	 */
	protected function setEndpoint( string $endpoint ): self
	{
		$this->endpoint = $endpoint;
		return $this;
	}

	/**
	 *   Adds GET parameter to called URL.
	 *
	 * @param string      $name
	 * @param string|null $value
	 *
	 * @return RiotAPI
	 */
	protected function addQuery( string $name, $value ): self
	{
		if (!is_null($value))
			$this->query_data[$name] = $value;

		return $this;
	}

	/**
	 *   Sets POST/PUT data.
	 *
	 * @param string $data
	 *
	 * @return RiotAPI
	 */
	protected function setData( string $data ): self
	{
		$this->post_data = $data;
		return $this;
	}

	/**
	 *   Makes call to RiotAPI.
	 *
	 * @param string $override_region
	 * @param string $method
	 *
	 * @throws GeneralException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @internal
	 */
	protected function makeCall( string $override_region = null, string $method = self::METHOD_GET )
	{
		if ($this->getSetting(self::SET_CACHE_RATELIMIT) && $this->rate_limit_control != false)
			if (!$this->rate_limit_control->canCall($this->getSetting($this->used_key), $override_region ? $override_region : $this->getSetting(self::SET_REGION)))
				throw new ServerLimitException('API call rate limit would be exceeded by this call.');

		$url_regionPart = $this->regions->getRegionName($override_region ? $override_region : $this->getSetting(self::SET_REGION));

		if (strpos($url_regionPart, 'http') !== false)
		{
			//  This region has it's own - custom address (either http or https)
			$url = $url_regionPart;
		}
		else
		{
			$url = "https://" . $url_regionPart . $this->getSetting(self::SET_API_BASEURL);
		}

		$url.= $this->endpoint . "?api_key=" . $this->getSetting($this->used_key)
			. ( !empty($this->query_data) ? '&' . http_build_query($this->query_data) : '' );

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);

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
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Connection: Keep-Alive'
			));
			curl_setopt($ch, CURLOPT_POSTFIELDS,
				$this->post_data);
		}
		else
			throw new RequestException('Invalid method selected.');

		if ($this->getSetting(self::SET_USE_DUMMY_DATA, false))
		{
			$endp = str_replace(['/', '.'], ['-', ''], substr($this->endpoint, 1));
			$quer = str_replace(['&', '='], ['_', '-'], http_build_query($this->query_data));
			if (strlen($quer))
				$quer = "_" . $quer;

			$dummyDataFilename = __DIR__ . "/../../tests/DummyData/$endp$quer.json";
			$data = @file_get_contents($dummyDataFilename);
			if (!$data)
				throw new GeneralException("No DummyData available for call. ($endp$quer)");

			$data = unserialize($data);
			$headers = $data['headers'];
			$response = $data['response'];
			$response_code = $data['code'];
		}
		else
		{
			$raw_data = curl_exec($ch);
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

			$headers = $this->parseHeaders(substr($raw_data, 0, $header_size));
			$response = substr($raw_data, $header_size);
			$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		}

		if ($response_code == 503)
		{
			throw new ServerException('RiotAPI: Service is unavailable.');
		}
		elseif ($response_code == 500)
		{
			throw new ServerException('RiotAPI: Internal server error occured.');
		}
		elseif ($response_code == 429)
		{
			throw new ServerLimitException('RiotAPI: Rate limit for this API key was exceeded.');
		}
		elseif ($response_code == 415)
		{
			throw new RequestException('Request: Unsupported media type.');
		}
		elseif ($response_code == 404)
		{
			throw new RequestException('Request: Not found.');
		}
		elseif ($response_code == 403)
		{
			throw new RequestException('Request: Forbidden.');
		}
		elseif ($response_code == 401)
		{
			throw new RequestException('Request: Unauthorized.');
		}
		elseif ($response_code == 400)
		{
			throw new RequestException('Request: Bad request - probably invalid argument format.');
		}
		elseif ($response_code > 400)
		{
			print_r($response);
			throw new ServerException("RiotAPI: Unknown error occured. ($response_code)");
		}

		if ($this->getSetting(self::SET_CACHE_RATELIMIT) && $this->rate_limit_control != false && isset($headers[self::API_RATELIMIT_HEADER]))
			$this->rate_limit_control->registerCall($this->getSetting($this->used_key), $url_regionPart, $headers[self::API_RATELIMIT_HEADER]);

		$this->result_data  = json_decode($response, true);

		/*
		$endp = str_replace(['/', '.'], ['-', ''], substr($this->endpoint, 1));
		$quer = str_replace(['&', '='], ['_', '-'], http_build_query($this->query_data));
		if (strlen($quer))
			$quer = "_" . $quer;

		$dummyDataFilename = __DIR__ . "/../../tests/DummyData/$endp$quer.json";
		if (!is_file($dummyDataFilename))
			file_put_contents($dummyDataFilename, serialize([
				'response' => $response,
				'headers'  => $headers,
				'code'     => 200,
			]));
		*/

		$this->query_data   = array();
		$this->post_data    = null;
		$this->used_key     = self::SET_KEY;

		curl_close($ch);
	}

	public static function parseHeaders( $requestHeaders )
	{
		$r = array();
		foreach (explode("\r\n", $requestHeaders) as $line)
		{
			if (strpos($line, ':'))
			{
				$e = explode(": ", $line);
				$r[$e[0]] = @$e[1];
			}
			elseif (strlen($line))
				$r[] = $line;
		}
		return $r;
	}

	/**
	 *   Returns raw getResult data from the last call.
	 *
	 * @return mixed
	 */
	public function getResult()
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

		return new Objects\ChampionListDto($this->getResult());
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

		return new Objects\ChampionDto($this->getResult());
	}

	/**
	 *   Champion Mastery Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1091
	 **/

	/**
	 *   Get a champion mastery by player id and champion id. Response code 204 means
	 * there were no masteries found for given player id or player id and champion id
	 * combination. (RPC)
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

		return new Objects\ChampionMasteryDto($this->getResult());
	}

	/**
	 *   Get all champion mastery entries sorted by number of champion points descending
	 * (RPC)
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
		foreach ($this->getResult() as $ident => $data)
			$r[$ident] = new Objects\ChampionMasteryDto($data);

		return $r;
	}

	/**
	 *   Get a player's total champion mastery score, which is sum of individual champion
	 * mastery levels (RPC)
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

		return $this->getResult();
	}

	/**
	 *   Get specified number of top champion mastery entries sorted by number of
	 * champion points descending (RPC)
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
		foreach ($this->getResult() as $ident => $data)
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
	 * @throws RequestException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/976/3336
	 */
	public function getCurrentGame( int $summoner_id ): Objects\CurrentGameInfo
	{
		$this->setEndpoint("/observer-mode/rest/consumer/getSpectatorGameInfo/{$this->platforms->getPlatformName($this->settings[self::SET_REGION])}/{$summoner_id}")
			->makeCall();

		return new Objects\CurrentGameInfo($this->getResult());
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

		return new Objects\FeaturedGames($this->getResult());
	}

	/**
	 *  Game Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1207
	 * @deprecated
	 **/
	const ENDPOINT_VERSION_GAME = 'v1.3';

	/**
	 *   Get recent games by summoner ID.
	 *
	 * @param $summoner_id
	 *
	 * @return Objects\RecentGamesDto
	 * @link https://developer.riotgames.com/api/methods#!/1207/4679
	 *
	 * @deprecated
	 */
	public function getRecentGames( int $summoner_id ): Objects\RecentGamesDto
	{
		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_GAME . "/game/by-summoner/{$summoner_id}/recent")
			->makeCall();

		return new Objects\RecentGamesDto($this->getResult());
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
	 * @param array $summoner_ids
	 *
	 * @return array
	 * @throws RequestParameterException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1215/4701
	 */
	public function getLeagueMappingBySummoners( array $summoner_ids ): array
	{
		if (count($summoner_ids) > 10)
			throw new RequestParameterException("Maximum allowed summoner ID count is 10.");
		$summoner_ids = implode(',', $summoner_ids);

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_LEAGUE . "/league/by-summoner/{$summoner_ids}")
			->makeCall();

		$r = array();
		foreach ($this->getResult() as $summoner_id => $leaguesData)
		{
			$leagues = array();
			foreach ($leaguesData as $ident => $data)
				$leagues[$ident] = new Objects\LeagueDto($data);

			$r[$summoner_id] = $leagues;
		}

		return $r;
	}

	/**
	 *   Get leagues for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\LeagueDto[]
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1215/4701
	 */
	public function getLeagueMappingBySummoner( int $summoner_id ): array
	{
		$list = $this->getLeagueMappingBySummoners([$summoner_id]);
		return reset($list);
	}

	/**
	 *   Get league entries mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param array $summoner_ids
	 *
	 * @return array
	 * @throws RequestParameterException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1215/4705
	 */
	public function getLeagueEntryBySummoners( array $summoner_ids ): array
	{
		if (count($summoner_ids) > 10)
			throw new RequestParameterException("Maximum allowed summoner ID count is 10.");
		$summoner_ids = implode(',', $summoner_ids);

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_LEAGUE . "/league/by-summoner/{$summoner_ids}/entry")
			->makeCall();

		$r = array();
		foreach ($this->getResult() as $summoner_id => $leaguesData)
		{
			$leagues = array();
			foreach ($leaguesData as $ident => $data)
				$leagues[$ident] = new Objects\LeagueDto($data);

			$r[$summoner_id] = $leagues;
		}

		return $r;
	}

	/**
	 *   Get league entries for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\LeagueDto[]
	 */
	public function getLeagueEntryBySummoner( int $summoner_id ): array
	{
		$list = $this->getLeagueEntryBySummoners([$summoner_id]);
		return reset($list);
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

		return new Objects\LeagueDto($this->getResult());
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

		return new Objects\LeagueDto($this->getResult());
	}

	/**
	 *   LoL Static Data Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1055
	 **/
	const ENDPOINT_VERSION_STATICDATA = 'v1.2';

	/**
	 *   Retrieves champion list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param bool         $data_by_id
	 * @param string|array $champ_data
	 *
	 * @return StaticData\SChampionListDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3633
	 */
	public function getStaticChampions( string $locale = null, string $version = null, bool $data_by_id = null, $champ_data = null ): StaticData\SChampionListDto
	{
		if (is_array($champ_data))
			$champ_data = implode(',', $champ_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/champion")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("dataById", $data_by_id)
			->addQuery("champData", $champ_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SChampionListDto($this->getResult());
	}

	/**
	 *   Retrieves a champion by its ID.
	 *
	 * @param int          $champion_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $champ_data
	 *
	 * @return StaticData\SChampionDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3622
	 */
	public function getStaticChampion( int $champion_id, string $locale = null, string $version = null, $champ_data = null ): StaticData\SChampionDto
	{
		if (is_array($champ_data))
			$champ_data = implode(',', $champ_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/champion/{$champion_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("champData", $champ_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SChampionDto($this->getResult());
	}

	/**
	 *   Retrieves item list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $item_list_data
	 *
	 * @return StaticData\SItemListDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3621
	 */
	public function getStaticItems( string $locale = null, string $version = null, $item_list_data = null ): StaticData\SItemListDto
	{
		if (is_array($item_list_data))
			$item_list_data = implode(',', $item_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/item")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("itemListData", $item_list_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SItemListDto($this->getResult());
	}

	/**
	 *   Retrieves item by its unique ID.
	 *
	 * @param int          $item_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $item_list_data
	 *
	 * @return StaticData\SItemDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3627
	 */
	public function getStaticItem( int $item_id, string $locale = null, string $version = null, $item_list_data = null ): StaticData\SItemDto
	{
		if (is_array($item_list_data))
			$item_list_data = implode(',', $item_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/item/{$item_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("itemListData", $item_list_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SItemDto($this->getResult());
	}

	/**
	 *   Retrieve language strings data.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\SLanguageStringsDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3624
	 */
	public function getStaticLanguageStrings( string $locale = null, string $version = null ): StaticData\SLanguageStringsDto
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/language-strings")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->makeCall(Region::GLOBAL);

		return new StaticData\SLanguageStringsDto($this->getResult());
	}

	/**
	 *   Retrieve supported languages data.
	 *
	 * @return array
	 * @link https://developer.riotgames.com/api/methods#!/1055/3631
	 */
	public function getStaticLanguages(): array
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/languages")
			->makeCall(Region::GLOBAL);

		return $this->getResult();
	}

	/**
	 *   Retrieve map data.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\SMapDataDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3635
	 */
	public function getStaticMaps( string $locale = null, string $version = null ): StaticData\SMapDataDto
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/map")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->makeCall(Region::GLOBAL);

		return new StaticData\SMapDataDto($this->getResult());
	}

	/**
	 *   Retrieves mastery list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $mastery_list_data
	 *
	 * @return StaticData\SMasteryListDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3625
	 */
	public function getStaticMasteries( string $locale = null, string $version = null, $mastery_list_data = null ): StaticData\SMasteryListDto
	{
		if (is_array($mastery_list_data))
			$mastery_list_data = implode(',', $mastery_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/mastery")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("masteryListData", $mastery_list_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SMasteryListDto($this->getResult());
	}

	/**
	 *   Retrieves mastery by its unique ID.
	 *
	 * @param int          $mastery_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $mastery_list_data
	 *
	 * @return StaticData\SMasteryDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3626
	 */
	public function getStaticMastery( int $mastery_id, string $locale = null, string $version = null, $mastery_list_data = null ): StaticData\SMasteryDto
	{
		if (is_array($mastery_list_data))
			$mastery_list_data = implode(',', $mastery_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/mastery/{$mastery_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("masteryListData", $mastery_list_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SMasteryDto($this->getResult());
	}

	/**
	 *   Retrieve realm data. (Region versions)
	 *
	 * @return StaticData\SRealmDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3632
	 */
	public function getStaticRealm(): StaticData\SRealmDto
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/realm")
			->makeCall(Region::GLOBAL);

		return new StaticData\SRealmDto($this->getResult());
	}

	/**
	 *   Retrieves rune list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $rune_list_data
	 *
	 * @return StaticData\SRuneListDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3623
	 */
	public function getStaticRunes( string $locale = null, string $version = null, $rune_list_data = null ): StaticData\SRuneListDto
	{
		if (is_array($rune_list_data))
			$rune_list_data = implode(',', $rune_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/rune")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("runeListData", $rune_list_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SRuneListDto($this->getResult());
	}

	/**
	 *   Retrieves rune by its unique ID.
	 *
	 * @param int          $rune_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $rune_list_data
	 *
	 * @return StaticData\SRuneDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3629
	 */
	public function getStaticRune( int $rune_id, string $locale = null, string $version = null, $rune_list_data = null ): StaticData\SRuneDto
	{
		if (is_array($rune_list_data))
			$rune_list_data = implode(',', $rune_list_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/rune/{$rune_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("runeListData", $rune_list_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SRuneDto($this->getResult());
	}

	/**
	 *   Retrieves summoner spell list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param bool         $data_by_id
	 * @param string|array $spell_data
	 *
	 * @return StaticData\SSummonerSpellListDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3634
	 */
	public function getStaticSummonerSpells( string $locale = null, string $version = null, bool $data_by_id = false, $spell_data = null ): StaticData\SSummonerSpellListDto
	{
		if (is_array($spell_data))
			$spell_data = implode(',', $spell_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/summoner-spell")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("dataById", $data_by_id)
			->addQuery("spellData", $spell_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SSummonerSpellListDto($this->getResult());
	}

	/**
	 *   Retrieves summoner spell by its unique ID.
	 *
	 * @param int          $summoner_spell_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $spell_data
	 *
	 * @return StaticData\SSummonerSpellDto
	 * @link https://developer.riotgames.com/api/methods#!/1055/3628
	 */
	public function getStaticSummonerSpell( int $summoner_spell_id, string $locale = null, string $version = null, $spell_data = null ): StaticData\SSummonerSpellDto
	{
		if (is_array($spell_data))
			$spell_data = implode(',', $spell_data);

		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/summoner-spell/{$summoner_spell_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("spellData", $spell_data)
			->makeCall(Region::GLOBAL);

		return new StaticData\SSummonerSpellDto($this->getResult());
	}

	/**
	 *   Retrieve version data.
	 *
	 * @return array
	 * @link https://developer.riotgames.com/api/methods#!/1055/3630
	 */
	public function getStaticVersions(): array
	{
		$this->setEndpoint("/api/lol/static-data/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_STATICDATA . "/versions")
			->makeCall(Region::GLOBAL);

		return $this->getResult();
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
		foreach ($this->getResult() as $ident => $data)
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
	public function getShardStatus( string $region ): Objects\ShardStatus
	{
		$this->setEndpoint("/lol/status/" . self::ENDPOINT_VERSION_STATUS . "/shard")
			->makeCall($region);

		return new Objects\ShardStatus($this->getResult());
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

		return new Objects\MatchDetail($this->getResult());
	}

	public function getTournamentMatch( int $match_id, string $tournament_code, bool $include_timeline = false )
	{
		throw new GeneralException('Not yet implemented.');

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_MATCH . "/match/for-tournament/{$match_id}")
			->addQuery('tournamentCode', $tournament_code)
			->addQuery('includeTimeline', $include_timeline)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall();

		return $this->getResult();
	}

	public function getTournamentMatchIds( string $tournament_code )
	{
		throw new GeneralException('Not yet implemented.');

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_MATCH . "/match/by-tournament/{$tournament_code}/ids")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall();

		return $this->getResult();
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

		return new Objects\MatchList($this->getResult());
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

		return new Objects\RankedStatsDto($this->getResult());
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

		return new Objects\PlayerStatsSummaryListDto($this->getResult());
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
	 * @throws RequestParameterException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1221/4746
	 */
	public function getSummonersByName( array $summoner_names ): array
	{
		if (count($summoner_names) > 40)
			throw new RequestParameterException("Maximum allowed summoner name count is 40.");
		$summoner_names = implode(',', $summoner_names);

		//  Remove all spaces
		$summoner_names = preg_replace('/[\s-]+/', '', strtolower($summoner_names));

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/by-name/{$summoner_names}")
			->makeCall();

		$r = array();
		foreach ($this->getResult() as $summoner_name => $data)
			$r[$summoner_name] = new Objects\SummonerDto($data);

		return $r;
	}

	/**
	 *   Get signle summoner object for a given summoner name.
	 *
	 * @param string $summoner_name
	 *
	 * @return Objects\SummonerDto
	 * @throws RequestParameterException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1221/4746
	 */
	public function getSummonerByName( string $summoner_name ): Objects\SummonerDto
	{
		if (strpos($summoner_name, ',') !== false)
			throw new RequestParameterException("Summoner name list is not allowed by this function, please use 'getSummonersByName' function.");

		$list = $this->getSummonersByName([$summoner_name]);
		return reset($list);
	}

	/**
	 *   Get summoner objects mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param array $summoner_ids
	 *
	 * @return Objects\SummonerDto[]
	 * @throws RequestParameterException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1221/4745
	 */
	public function getSummoners( array $summoner_ids ): array
	{
		if (count($summoner_ids) > 40)
			throw new RequestParameterException("Maximum allowed summoner ID count is 40.");
		$summoner_ids = implode(',', $summoner_ids);

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/{$summoner_ids}")
			->makeCall();

		$r = array();
		foreach ($this->getResult() as $summoner_id => $data)
			$r[$summoner_id] = new Objects\SummonerDto($data);

		return $r;
	}

	/**
	 *   Get single summoner object for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\SummonerDto
	 * @link https://developer.riotgames.com/api/methods#!/1221/4745
	 */
	public function getSummoner( int $summoner_id ): Objects\SummonerDto
	{
		$list = $this->getSummoners([$summoner_id]);
		return reset($list);
	}

	/**
	 *   Get mastery pages mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param array $summoner_ids
	 *
	 * @return Objects\MasteryPagesDto[]
	 * @throws RequestParameterException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1208/4683
	 */
	public function getSummonersMasteries( array $summoner_ids ): array
	{
		if (count($summoner_ids) > 40)
			throw new RequestParameterException("Maximum allowed summoner ID count is 40.");
		$summoner_ids = implode(',', $summoner_ids);

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/{$summoner_ids}/masteries")
			->makeCall();

		$r = array();
		foreach ($this->getResult() as $summoner_id => $data)
			$r[$summoner_id] = new Objects\MasteryPagesDto($data);

		return $r;
	}

	/**
	 *   Get mastery pages for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\MasteryPagesDto
	 * @link https://developer.riotgames.com/api/methods#!/1208/4683
	 */
	public function getSummonerMasteries( int $summoner_id ): Objects\MasteryPagesDto
	{
		$list = $this->getSummonersMasteries([$summoner_id]);
		return reset($list);
	}

	/**
	 *   Get summoner names mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param array $summoner_ids
	 *
	 * @return array
	 * @throws RequestParameterException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1208/4685
	 */
	public function getSummonersNames( array $summoner_ids ): array
	{
		if (count($summoner_ids) > 40)
			throw new RequestParameterException("Maximum allowed summoner ID count is 40.");
		$summoner_ids = implode(',', $summoner_ids);

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/{$summoner_ids}/name")
			->makeCall();

		return $this->getResult();
	}

	/**
	 *   Get summoner name for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return string
	 * @link https://developer.riotgames.com/api/methods#!/1208/4685
	 */
	public function getSummonerName( int $summoner_id ): string
	{
		$list = $this->getSummonersNames([$summoner_id]);
		return reset($list);
	}

	/**
	 *   Get rune pages mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param array $summoner_ids
	 *
	 * @return Objects\RunePagesDto[]
	 * @throws RequestParameterException
	 *
	 * @link https://developer.riotgames.com/api/methods#!/1208/4682
	 */
	public function getSummonersRunes( array $summoner_ids ): array
	{
		if (count($summoner_ids) > 40)
			throw new RequestParameterException("Maximum allowed summoner ID count is 40.");
		$summoner_ids = implode(',', $summoner_ids);

		$this->setEndpoint("/api/lol/{$this->settings[self::SET_REGION]}/" . self::ENDPOINT_VERSION_SUMMONER . "/summoner/{$summoner_ids}/runes")
			->makeCall();

		$r = array();
		foreach ($this->getResult() as $summoner_id => $data)
			$r[$summoner_id] = new Objects\RunePagesDto($data);

		return $r;
	}

	/**
	 *   Get rune pages for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\RunePagesDto
	 * @link https://developer.riotgames.com/api/methods#!/1208/4682
	 */
	public function getSummonerRunes( int $summoner_id ): Objects\RunePagesDto
	{
		$list = $this->getSummonersRunes([$summoner_id]);
		return reset($list);
	}


	/**
	 *   Tournament Provider Endpoint Methods
	 *
	 * @link
	 **/
	const ENDPOINT_VERSION_TOURNAMENTPROVIDER = 'v1';

	/**
	 * @param int                      $tournament_id
	 * @param int                      $count
	 * @param TournamentCodeParameters $parameters
	 *
	 * @return array
	 * @throws GeneralException
	 */
	public function createTournamentCodes( int $tournament_id, int $count, TournamentCodeParameters $parameters ): array
	{
		if ($this->getSetting(self::SET_TOURNAMENT_INTERIM, true))
			return $this->createTournamentCodes_STUB($tournament_id, $count, $parameters);

		throw new GeneralException('Not yet implemented.');
	}

	/**
	 * @param ProviderRegistrationParameters $parameters
	 *
	 * @return int
	 * @throws GeneralException
	 */
	public function createTournamentProvider( ProviderRegistrationParameters $parameters ): int
	{
		if ($this->getSetting(self::SET_TOURNAMENT_INTERIM, true))
			return $this->createTournamentProvider_STUB($parameters);

		throw new GeneralException('Not yet implemented.');
	}

	/**
	 * @param TournamentRegistrationParameters $parameters
	 *
	 * @return int
	 * @throws GeneralException
	 */
	public function createTournament( TournamentRegistrationParameters $parameters ): int
	{
		if ($this->getSetting(self::SET_TOURNAMENT_INTERIM, true))
			return $this->createTournament_STUB($parameters);

		throw new GeneralException('Not yet implemented.');
	}

	/**
	 * @param string $tournament_code
	 *
	 * @return Objects\LobbyEventDTOWrapper
	 * @throws GeneralException
	 */
	public function getTournamentLobbyEvents( string $tournament_code ): Objects\LobbyEventDTOWrapper
	{
		if ($this->getSetting(self::SET_TOURNAMENT_INTERIM, true))
			return $this->getTournamentLobbyEvents_STUB($tournament_code);

		throw new GeneralException('Not yet implemented.');
	}


	/**
	 *   Tournament Provider STUB Endpoint Methods
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
	 *
	 * @internal
	 */
	public function createTournamentCodes_STUB( int $tournament_id, int $count, TournamentCodeParameters $parameters ): array
	{
		$this->setEndpoint("/tournament/stub/" . self::ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB . "/code")
			->addQuery('tournamentId', $tournament_id)
			->addQuery('count', $count)
			->setData($parameters)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall( Region::GLOBAL, self::METHOD_POST );

		return $this->getResult();
	}

	/**
	 *   Creates a mock tournament provider and returns its ID.
	 *
	 * @param ProviderRegistrationParameters $parameters
	 *
	 * @return int
	 * @link https://developer.riotgames.com/api/methods#!/1090/3762
	 *
	 * @internal
	 */
	public function createTournamentProvider_STUB( ProviderRegistrationParameters $parameters ): int
	{
		$this->setEndpoint("/tournament/stub/" . self::ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB . "/provider")
			->setData($parameters)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall( Region::GLOBAL, self::METHOD_POST );

		return $this->getResult();
	}

	/**
	 *   Creates a mock tournament and returns its ID.
	 *
	 * @param TournamentRegistrationParameters $parameters
	 *
	 * @return int
	 * @link https://developer.riotgames.com/api/methods#!/1090/3763
	 *
	 * @internal
	 */
	public function createTournament_STUB( TournamentRegistrationParameters $parameters ): int
	{
		$this->setEndpoint("/tournament/stub/" . self::ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB . "/tournament")
			->setData($parameters)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall( Region::GLOBAL, self::METHOD_POST );

		return $this->getResult();
	}

	/**
	 *   Gets a mock list of lobby events by tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\LobbyEventDTOWrapper
	 * @link https://developer.riotgames.com/api/methods#!/1090/3761
	 *
	 * @internal
	 */
	public function getTournamentLobbyEvents_STUB( string $tournament_code ): Objects\LobbyEventDTOWrapper
	{
		$this->setEndpoint("/tournament/stub/" . self::ENDPOINT_VERSION_TOURNAMENTPROVIDER_STUB . "/lobby/events/by-code/{$tournament_code}")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall( Region::GLOBAL );

		return new Objects\LobbyEventDTOWrapper($this->getResult());
	}
}