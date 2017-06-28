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

use function foo\func;
use RiotAPI\Definitions\CallCacheControl;
use RiotAPI\Definitions\FileCacheProvider;
use RiotAPI\Definitions\ICacheProvider;
use RiotAPI\Definitions\ICallCacheControl;
use RiotAPI\Definitions\IPlatform;
use RiotAPI\Definitions\MemcachedCacheProvider;
use RiotAPI\Definitions\Platform;
use RiotAPI\Definitions\IRegion;
use RiotAPI\Definitions\Region;
use RiotAPI\Definitions\IRateLimitControl;
use RiotAPI\Definitions\RateLimitControl;

use RiotAPI\Objects;
use RiotAPI\Objects\StaticData;
use RiotAPI\Objects\ProviderRegistrationParameters;
use RiotAPI\Objects\TournamentCodeParameters;
use RiotAPI\Objects\TournamentRegistrationParameters;

use RiotAPI\Exceptions\GeneralException;
use RiotAPI\Exceptions\RequestException;
use RiotAPI\Exceptions\RequestParameterException;
use RiotAPI\Exceptions\ServerException;
use RiotAPI\Exceptions\ServerLimitException;
use RiotAPI\Exceptions\SettingsException;


/**
 *   Class RiotAPI
 *
 * @package RiotAPI
 */
class RiotAPI
{
	/**
	 * Constants for cURL requests.
	 */
	const
		METHOD_GET    = 'GET',
		METHOD_POST   = 'POST',
		METHOD_PUT    = 'PUT',
		METHOD_DELETE = 'DELETE';

	/**
	 * Settings constants.
	 */
	const
		SET_REGION                = 'SET_REGION',
		SET_ORIG_REGION           = 'SET_ORIG_REGION',
		SET_PLATFORM              = 'SET_PLATFORM',              // Set internally by setting region
		SET_VERIFY_SSL            = 'SET_VERIFY_SSL',            // Specifies whether or not to verify SSL (verification often fails on localhost)
		SET_KEY                   = 'SET_KEY',                   // API key used by default
		SET_KEY_INCLUDE_TYPE      = 'SET_KEY_INCLUDE_TYPE',      // API key used by default
		SET_TOURNAMENT_KEY        = 'SET_TOURNAMENT_KEY',        // API key used when working with tournaments
		SET_INTERIM               = 'SET_INTERIM',               // Used to set whether or not is your application in Interim mode (Tournament STUB endpoints)
		SET_RATELIMITS            = 'SET_RATELIMITS',            // Specifies limits for provided API keys
		SET_CACHE_PROVIDER        = 'SET_CACHE_PROVIDER',        // Specifies CacheProvider class name
		SET_CACHE_PROVIDER_PARAMS = 'SET_CACHE_PROVIDER_PARAMS', // Specifies parameters passed to CacheProvider class when initializing
		SET_CACHE_RATELIMIT       = 'SET_CACHE_RATELIMIT',       // Used to set whether or not to saveCallData and check API key's rate limit
		SET_CACHE_CALLS           = 'SET_CACHE_CALLS',           // Used to set whether or not to temporary saveCallData API call's results
		SET_CACHE_CALLS_LENGTH    = 'SET_CACHE_CALLS_LENGTH',    // Specifies for how long are call results saved
		SET_API_BASEURL           = 'SET_API_BASEURL',
		SET_USE_DUMMY_DATA        = 'SET_USE_DUMMY_DATA',
		SET_SAVE_DUMMY_DATA       = 'SET_SAVE_DUMMY_DATA';

	/**
	 * Available API key inclusion options.
	 */
	const
		KEY_AS_QUERY_PARAM = 'keyInclude:query',
		KEY_AS_HEADER      = 'keyInclude:header';

	/**
	 * Available cache provider options.
	 */
	const
		CACHE_PROVIDER_FILE      = FileCacheProvider::class,
		CACHE_PROVIDER_MEMCACHED = MemcachedCacheProvider::class;

	/**
	 * Cache constants used to identify cache target.
	 */
	const
		CACHE_KEY_RLC   = 'rate-limit.cache',
		CACHE_KEY_CCC = 'api-calls.cache';

	/**
	 * Available API headers.
	 */
	const
		API_HEADER_API_KEY          = 'X-Riot-Token',
		API_HEADER_RATELIMIT        = 'X-Rate-Limit-Count',
		API_HEADER_RATELIMIT_TYPE   = 'X-Rate-Limit-Type',
		API_HEADER_METHOD_RATELIMIT = 'X-Method-Rate-Limit-Count',
		API_HEADER_APP_RATELIMIT    = 'X-App-Rate-Limit-Count';

	/**
	 * Constants required for tournament API calls.
	 */
	const
		TOURNAMENT_ALLOWED_PICK_TYPES = [
			'BLIND_PICK',
			'DRAFT_MODE',
			'ALL_RANDOM',
			'TOURNAMENT_DRAFT',
		],
		TOURNAMENT_ALLOWED_MAPS = [
			'SUMMONERS_RIFT',
			'TWISTED_TREELINE',
			'HOWLING_ABYSS',
		],
		TOURNAMENT_ALLOWED_SPECTATOR_TYPES = [
			'NONE',
			'LOBBYONLY',
			'ALL',
		],
		TOURNAMENT_ALLOWED_REGIONS = [
			Region::BRASIL,
			Region::EUROPE_EAST,
			Region::EUROPE_WEST,
			Region::JAPAN,
			Region::LAMERICA_SOUTH,
			Region::LAMERICA_NORTH,
			Region::NORTH_AMERICA,
			Region::OCEANIA,
			Region::RUSSIA,
			Region::TURKEY,
			Region::PBE,
		];


	/**
	 *   Contains current settings.
	 *
	 * @var $settings array
	 */
	protected $settings = array(
		self::SET_API_BASEURL      => '.api.riotgames.com',
		self::SET_VERIFY_SSL       => true,
		self::SET_KEY_INCLUDE_TYPE => self::KEY_AS_HEADER,
		self::SET_USE_DUMMY_DATA   => false,
		self::SET_SAVE_DUMMY_DATA  => false,
	);

	/** @var IRegion $regions */
	public $regions;

	/** @var IPlatform $platforms */
	public $platforms;


	/** @var ICacheProvider $cache */
	protected $cache;


	/** @var IRateLimitControl $rlc */
	protected $rlc;

	/** @var ICallCacheControl $ccc */
	protected $ccc;


	/** @var string $used_key */
	protected $used_key = self::SET_KEY;

	/** @var string $endpoint */
	protected $endpoint;


	/** @var array $query_data */
	protected $query_data = [];

	/** @var array $post_data */
	protected $post_data = [];

	/** @var array $result_data */
	protected $result_data;

	/** @var string $result_data */
	protected $result_data_raw;

	/** @var array $result_headers */
	protected $result_headers;

	/** @var callable[] $beforeCall */
	protected $beforeCall = [];

	/** @var callable[] $afterCall */
	protected $afterCall = [];


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
				throw new SettingsException("Required settings parameter '$key' is missing!");

		//  List of allowed setting keys
		$allowed_settings = array_merge([
			self::SET_VERIFY_SSL,
			self::SET_KEY_INCLUDE_TYPE,
			self::SET_TOURNAMENT_KEY,
			self::SET_INTERIM,
			self::SET_CACHE_PROVIDER,
			self::SET_CACHE_PROVIDER_PARAMS,
			self::SET_CACHE_RATELIMIT,
			self::SET_CACHE_CALLS,
			self::SET_CACHE_CALLS_LENGTH,
			self::SET_RATELIMITS,
			self::SET_USE_DUMMY_DATA,
		], $required_settings);

		if (isset($settings[self::SET_KEY_INCLUDE_TYPE])
			&& in_array($settings[self::SET_KEY_INCLUDE_TYPE], [ self::KEY_AS_HEADER, self::KEY_AS_QUERY_PARAM ], true) == false)
		{
			throw new SettingsException("Value of settings parameter '" . self::SET_KEY_INCLUDE_TYPE . "' is not valid.");
		}

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

		//  Some caching will be made, let's set up cache provider
		if ($this->getSetting(self::SET_CACHE_CALLS) || $this->getSetting(self::SET_CACHE_RATELIMIT))
			$this->_setupCacheProvider();

		//  Ratelimits are going to be cached
		if ($this->getSetting(self::SET_CACHE_RATELIMIT))
			$this->_setupCacheRateLimits();

		//  Call data are going to be cached
		if ($this->getSetting(self::SET_CACHE_CALLS))
			$this->_setupCacheCalls();

		//  Set up before calls callbacks
		$this->_setupBeforeCalls();

		//  Set up afterl calls callbacks
		$this->_setupAfterCalls();

		//  Sets platform based on current region
		$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($this->getSetting(self::SET_REGION)));
	}

	protected function _setupCacheProvider()
	{
		//  If something should be cached
		if ($this->isSettingSet(self::SET_CACHE_PROVIDER) == false
			|| ($this->getSetting(self::SET_CACHE_PROVIDER) == self::CACHE_PROVIDER_FILE && $this->isSettingSet(self::SET_CACHE_PROVIDER_PARAMS) == false))
		{
			//  Set default cache provider if not already set
			$this->setSettings([
				self::SET_CACHE_PROVIDER        => self::CACHE_PROVIDER_FILE,
				self::SET_CACHE_PROVIDER_PARAMS => [
					__DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR,
				],
			]);
		}

		try
		{
			//  Creates reflection of specified cache provider (can be user-made)
			$cacheProvider = new \ReflectionClass($this->getSetting(self::SET_CACHE_PROVIDER));
			//  Checks if this cache provider implements required interface
			if ($cacheProvider->implementsInterface(ICacheProvider::class) == false)
				throw new SettingsException("Provided CacheProvider does not implement ICacheProvider interface.");

			//  Gets default parameters
			$params = $this->getSetting(self::SET_CACHE_PROVIDER_PARAMS, []);
			//  and created new instance of this cache provider
			$this->cache = $cacheProvider->newInstanceArgs($params);
		}
		catch (\ReflectionException $ex)
		{
			//  probably problem when instantiating the class
			throw new SettingsException("Failed to initialize CacheProvider class: " . $ex->getMessage() . ".", 0, $ex);
		}
		catch (\Exception $ex)
		{
			//  something went wrong when initializing the class - invalid settings, etc.
			throw new SettingsException("CacheProvider class failed to be initialized: " . $ex->getMessage() . ".", 0, $ex);
		}

		//  Loads existing cache or creates new storages
		$this->loadCache();
	}

	public function _setupCacheRateLimits()
	{
		//  Gets ratelimit settings or sets defaults
		$rateLimits = $this->getSetting(self::SET_RATELIMITS, [
			$this->getSetting(self::SET_KEY) => [
				IRateLimitControl::INTERVAL_10S => 10,
				IRateLimitControl::INTERVAL_10M => 500,
			],
		]);

		//  If ratelimit settings is not set by user and tournament key is set,
		//  default ratelimits are also set for this key
		if ($this->isSettingSet(self::SET_RATELIMITS) == false
			&& $this->isSettingSet(self::SET_TOURNAMENT_KEY))
		{
			$rateLimits[$this->getSetting(self::SET_TOURNAMENT_KEY)] = [
				IRateLimitControl::INTERVAL_10S => 10,
				IRateLimitControl::INTERVAL_10M => 500,
			];
		}

		//  Checks ratelimits and sets them
		foreach ($rateLimits as $api_key => $limits)
		{
			if (!is_array($limits))
				throw new SettingsException("Rate limit settings are not in valid format.");

			foreach ($limits as $interval => $limit)
				if (!is_integer($interval) || !is_integer($limit))
					throw new SettingsException("Rate limit settings are not in valid format.");

			$this->rlc->setLimits($api_key, $limits);
		}
	}

	public function _setupCacheCalls()
	{
		if ($this->isSettingSet(self::SET_CACHE_CALLS_LENGTH) == false)
			$this->setSetting(self::SET_CACHE_CALLS_LENGTH, 60);
	}

	protected function _setupBeforeCalls()
	{
		//  API rate limit check before call is made
		$this->beforeCall[] = function () {
			if ($this->getSetting(self::SET_CACHE_RATELIMIT) && $this->rlc != false)
				if (!$this->rlc->canCall($this->getSetting($this->used_key), $this->getSetting(self::SET_REGION)))
					throw new ServerLimitException('API call rate limit would be exceeded by this call.');
		};
	}

	protected function _setupAfterCalls()
	{
		//  Register, that call has been made if RateLimit cache is enabled
		$this->afterCall[] = function ( $url, $requestHash ) {
			if ($this->getSetting(self::SET_CACHE_RATELIMIT) && $this->rlc != false && isset($this->result_headers[self::API_HEADER_APP_RATELIMIT]))
				$this->rlc->registerCall($this->getSetting($this->used_key), $this->getSetting(self::SET_REGION), $this->result_headers[self::API_HEADER_APP_RATELIMIT]);
		};

		//  Save result data, if CallCache is enabled
		$this->afterCall[] = function ( $url, $requestHash ) {
			if ($this->getSetting(self::SET_CACHE_CALLS) && $this->ccc != false)
				$this->ccc->saveCallData($requestHash, $this->result_data_raw, $this->getSetting(self::SET_CACHE_CALLS_LENGTH, 60));
		};

		//  Save result data as new DummyData if enabled and if data does not already exist
		$this->afterCall[] = function ( $url, $requestHash ) {
			if ($this->getSetting(self::SET_SAVE_DUMMY_DATA, false) && file_exists($this->getDummyDataFileName()) == false)
				$this->_saveDummyData($this->result_headers, $this->result_data_raw, 200);
		};


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
			//  ratelimit cache enabled, try to load already existing object
			$rlc = $this->cache->load(self::CACHE_KEY_RLC);
			if ($rlc == false)
				//  nothing loaded, creating new instance
				$rlc = new RateLimitControl($this->regions);

			$this->rlc = $rlc;
		}

		if ($this->getSetting(self::SET_CACHE_CALLS, false))
		{
			//  call cache enabled, try to load already existing object
			$callCache = $this->cache->load(self::CACHE_KEY_CCC);
			if ($callCache == false)
				//  nothing loaded, creating new instance
				$callCache = new CallCacheControl();

			$this->ccc = $callCache;
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
			//  save RateLimitControl
			$this->cache->save(self::CACHE_KEY_RLC, $this->rlc, 3600);
		}

		if ($this->getSetting(self::SET_CACHE_CALLS, false))
		{
			//  save CallCacheControl
			$this->cache->save(self::CACHE_KEY_CCC, $this->ccc, $this->getSetting(self::SET_CACHE_CALLS_LENGTH, 60));
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
		$region = strtolower($region);
		$this->setSetting(self::SET_REGION, $region);
		$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($region));
		return $this;
	}

	/**
	 *   Sets temporary region to be used on API calls. Saves current region.
	 *
	 * @param string $tempRegion
	 *
	 * @return RiotAPI
	 */
	public function setTemporaryRegion( string $tempRegion ): self
	{
		$tempRegion = strtolower($tempRegion);
		$this->setSetting(self::SET_ORIG_REGION, $this->getSetting(self::SET_REGION));
		$this->setSetting(self::SET_REGION, $tempRegion);
		$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($tempRegion));
		return $this;
	}

	/**
	 *   Unets temporary region and returns original region.
	 *
	 * @return RiotAPI
	 */
	public function unsetTemporaryRegion(): self
	{
		$region = $this->getSetting(self::SET_ORIG_REGION);
		$this->setSetting(self::SET_REGION, $region);
		$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($region));
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
		{
			if (is_array($value))
				$value = implode("&{$name}=", $value);

			$this->query_data[$name] = $value;
		}

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
	 * @param string|null $overrideRegion
	 * @param string      $method
	 *
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @internal
	 */
	protected function makeCall( string $overrideRegion = null, string $method = self::METHOD_GET )
	{
		if ($overrideRegion)
			$this->setTemporaryRegion($overrideRegion);

		$this->_beforeCall();

		$url = $this->_getCallUrl($curlHeaders);
		$requestHash = md5($url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->getSetting(self::SET_VERIFY_SSL));

		if ($method == self::METHOD_GET)
		{
			curl_setopt($ch, CURLOPT_URL, $url);
		}
		elseif ($method == self::METHOD_POST)
		{
			$curlHeaders[] = 'Content-Type: application/json';
			$curlHeaders[] = 'Connection: Keep-Alive';

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
				$this->post_data);
		}
		elseif ($method == self::METHOD_PUT)
		{
			$curlHeaders[] = 'Content-Type: application/json';
			$curlHeaders[] = 'Connection: Keep-Alive';

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS,
				$this->post_data);
		}
		else
			throw new RequestException('Invalid method selected.');

		curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);

		if ($this->getSetting(self::SET_USE_DUMMY_DATA, false))
		{
			//  DummyData are going to be used
			try
			{
				$this->_loadDummyData($headers, $response, $response_code);
			}
			catch (RequestException $ex)
			{
				if ($this->getSetting(self::SET_SAVE_DUMMY_DATA, false) == false)
					throw new RequestException("No DummyData available for call.");
			}
		}

		if (isset($response) == false)
		{
			if ($this->getSetting(self::SET_CACHE_CALLS) && $this->ccc != false && $this->ccc->isCallCached($requestHash))
			{
				$response = $this->ccc->loadCallData($requestHash);
				$response_code = 200;
				$headers = [];
			}
			else
			{
				$raw_data = curl_exec($ch);
				$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

				$headers = $this->parseHeaders(substr($raw_data, 0, $header_size));
				$response = substr($raw_data, $header_size);
				$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			}
		}

		if ($overrideRegion)
			$this->unsetTemporaryRegion();

		if (($curl_errno = curl_errno($ch)) !== 0)
		{
			$curl_error = curl_error($ch);
			$curl_error_info = "";
			if ($curl_errno == 60)
			{
				$curl_error_info = " (if you are on localhost, try setting the RiotAPI::SET_VERIFY_SSL to false)";
			}

			throw new RequestException('cURL error ocurred: ' . $curl_error . $curl_error_info, $curl_errno);
		}

		$this->result_data_raw = $response;
		$this->result_data     = json_decode($response, true);
		$this->result_headers  = $headers;

		$errMessage = "";
		if (!is_null($this->result_data) && isset($this->result_data->status) && isset($this->result_data->status->message))
			$errMessage = " " . $this->result_data->status->message;
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
			throw new ServerLimitException('RiotAPI: Rate limit for this API key was exceeded.' . $errMessage);
		}
		elseif ($response_code == 415)
		{
			throw new RequestException('Request: Unsupported media type.' . $errMessage);
		}
		elseif ($response_code == 404)
		{
			throw new RequestException('Request: Not found.' . $errMessage);
		}
		elseif ($response_code == 403)
		{
			throw new RequestException('Request: Forbidden.' . $errMessage);
		}
		elseif ($response_code == 401)
		{
			throw new RequestException('Request: Unauthorized.' . $errMessage);
		}
		elseif ($response_code == 400)
		{
			throw new RequestException('Request: Invalid request.' . $errMessage);
		}
		elseif ($response_code > 400)
		{
			throw new RequestException("RiotAPI: Unknown error occured. ($response_code)" . $errMessage);
		}

		$this->_afterCall($url, $requestHash);

		$this->query_data     = array();
		$this->post_data      = null;
		$this->used_key       = self::SET_KEY;

		curl_close($ch);
	}

	protected function _loadDummyData( &$headers, &$response, &$response_code )
	{
		$data = @file_get_contents($this->getDummyDataFileName());
		$data = unserialize($data);

		if (!$data || empty($data))
			throw new RequestException("DummyData file failed to be opened.");

		$headers = $data['headers'];
		$response = $data['response'];
		$response_code = $data['code'];
	}

	protected function _saveDummyData( $headers, $response, int $response_code )
	{
		file_put_contents($this->getDummyDataFileName(), serialize([
			'headers'  => $headers,
			'response' => $response,
			'code'     => $response_code,
		]));
	}

	protected function _beforeCall()
	{
		foreach ($this->beforeCall as $function)
		{
			if ($function() === false)
			{
				throw new RequestException("Request terminated by beforeCall function.");
			}
		}
	}

	protected function _afterCall( string $url, string $requestHash )
	{
		foreach ($this->afterCall as $function)
		{
			$function($url, $requestHash);
		}
	}

	protected function _getCallUrl( &$curlHeaders = [] ): string
	{
		$curlHeaders = [];
		//  Platform against which will call be made
		$url_platformPart = $this->platforms->getPlatformName($this->getSetting(self::SET_REGION));

		//  API base url
		$url_basePart = $this->getSetting(self::SET_API_BASEURL);

		//  Query parameters
		$url_queryPart = [];
		foreach ($this->query_data as $item => $value)
			$url_queryPart[] = "$item=$value";
		$url_queryPart = implode('&', $url_queryPart);

		//  API key
		$url_keyPart = "";
		if ($this->getSetting(self::SET_KEY_INCLUDE_TYPE) === self::KEY_AS_QUERY_PARAM)
		{
			//  API key is to be included as query parameter
			$url_keyPart = "?api_key=" . $this->getSetting($this->used_key) . (!empty($this->query_data) ? '&' : '');
		}
		elseif ($this->getSetting(self::SET_KEY_INCLUDE_TYPE) === self::KEY_AS_HEADER)
		{
			//  API key is to be included as request header
			$curlHeaders[] = self::API_HEADER_API_KEY . ': ' . $this->getSetting($this->used_key);
			$url_keyPart = (!empty($this->query_data) ? '?' : '');
		}

		return "https://" . $url_platformPart . $url_basePart . $this->endpoint . $url_keyPart . $url_queryPart;
	}

	protected function getDummyDataFileName(): string
	{
		$endp = str_replace([ '/', '.' ], [ '-', '' ], substr($this->endpoint, 1));
		$quer = str_replace([ '&', '=' ], [ '_', '-' ], http_build_query($this->query_data));
		if (strlen($quer))
			$quer = "_" . $quer;

		return __DIR__ . "/../../tests/DummyData/$endp$quer.json";
	}

	public static function parseHeaders( $requestHeaders ): array
	{
		$r = array();
		foreach (explode(PHP_EOL, $requestHeaders) as $line)
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
	 *  Champion Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#champion-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_CHAMPION = 'v3';

	/**
	 *   Retrieve all champions.
	 *
	 * @param bool|false $only_free_to_play
	 *
	 * @return Objects\ChampionListDto
	 * @link https://developer.riotgames.com/api-methods/#champion-v3/GET_getChampions
	 */
	public function getChampions( bool $only_free_to_play = false ): Objects\ChampionListDto
	{
		$this->setEndpoint("/lol/platform/" . self::ENDPOINT_VERSION_CHAMPION . "/champions")
			->addQuery("freeToPlay", $only_free_to_play ? 'true' : 'false')
			->makeCall();

		return new Objects\ChampionListDto($this->getResult());
	}

	/**
	 *   Retrieve champion by ID.
	 *
	 * @param int $champion_id
	 *
	 * @return Objects\ChampionDto
	 * @link https://developer.riotgames.com/api-methods/#champion-v3/GET_getChampionsById
	 */
	public function getChampionById( int $champion_id ): Objects\ChampionDto
	{
		$this->setEndpoint("/lol/platform/" . self::ENDPOINT_VERSION_CHAMPION . "/champions/{$champion_id}")
			->makeCall();

		return new Objects\ChampionDto($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Champion Mastery Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#champion-mastery-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_CHAMPIONMASTERY = 'v3';

	/**
	 *   Get a champion mastery by player id and champion id. Response code 204 means
	 * there were no masteries found for given player id or player id and champion id
	 * combination. (RPC)
	 *
	 * @param int $summoner_id
	 * @param int $champion_id
	 *
	 * @return Objects\ChampionMasteryDto
	 * @link https://developer.riotgames.com/api-methods/#champion-mastery-v3/GET_getChampionMastery
	 */
	public function getChampionMastery( int $summoner_id, int $champion_id ): Objects\ChampionMasteryDto
	{
		$this->setEndpoint("/lol/champion-mastery/" . self::ENDPOINT_VERSION_CHAMPION . "/champion-masteries/by-summoner/{$summoner_id}/by-champion/{$champion_id}")
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
	 * @link https://developer.riotgames.com/api-methods/#champion-mastery-v3/GET_getAllChampionMasteries
	 */
	public function getChampionMasteries( int $summoner_id ): array
	{
		$this->setEndpoint("/lol/champion-mastery/" . self::ENDPOINT_VERSION_CHAMPION . "/champion-masteries/by-summoner/{$summoner_id}")
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
	 * @link https://developer.riotgames.com/api-methods/#champion-mastery-v3/GET_getChampionMasteryScore
	 */
	public function getChampionMasteryScore( int $summoner_id ): int
	{
		$this->setEndpoint("/lol/champion-mastery/" . self::ENDPOINT_VERSION_CHAMPION . "/scores/by-summoner/{$summoner_id}")
			->makeCall();

		return $this->getResult();
	}


	/****************************************d*d*
	 *
	 *  Spectator Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#spectator-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_SPECTATOR = 'v3';

	/**
	 *   Get current game information for the given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\CurrentGameInfo
	 * @throws RequestException
	 *
	 * @link https://developer.riotgames.com/api-methods/#spectator-v3/GET_getCurrentGameInfoBySummoner
	 */
	public function getCurrentGameInfo( int $summoner_id ): Objects\CurrentGameInfo
	{
		$this->setEndpoint("/lol/spectator/v3/active-games/by-summoner/{$summoner_id}")
			->makeCall();

		return new Objects\CurrentGameInfo($this->getResult());
	}

	/**
	 *   Get list of featured games.
	 *
	 * @return Objects\FeaturedGames
	 * @link https://developer.riotgames.com/api-methods/#spectator-v3/GET_getFeaturedGames
	 */
	public function getFeaturedGames(): Objects\FeaturedGames
	{
		$this->setEndpoint("/lol/spectator/v3/featured-games")
			->makeCall();

		return new Objects\FeaturedGames($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  League Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#league-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_LEAGUE = 'v3';

	/**
	 *   Get leagues mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\LeagueListDto[]
	 * @link https://developer.riotgames.com/api-methods/#league-v3/GET_getAllLeaguesForSummoner
	 */
	public function getLeaguesForSummoner( int $summoner_id ): array
	{
		$this->setEndpoint("/lol/league/" . self::ENDPOINT_VERSION_LEAGUE . "/leagues/by-summoner/{$summoner_id}")
			->makeCall();

		$r = [];
		foreach ($this->getResult() as $leagueListDtoData)
			$r[] = new Objects\LeagueListDto($leagueListDtoData);

		return $r;
	}

	/**
	 *   Get leagues mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\LeaguePositionDto[]
	 * @link https://developer.riotgames.com/api-methods/#league-v3/GET_getAllLeaguePositionsForSummoner
	 */
	public function getLeaguePositionsForSummoner( int $summoner_id ): array
	{
		$this->setEndpoint("/lol/league/" . self::ENDPOINT_VERSION_LEAGUE . "/positions/by-summoner/{$summoner_id}")
			->makeCall();

		$r = [];
		foreach ($this->getResult() as $leagueListDtoData)
			$r[] = new Objects\LeaguePositionDto($leagueListDtoData);

		return $r;
	}

	/**
	 *   Get challenger tier leagues.
	 *
	 * @param string $game_queue_type
	 *
	 * @return Objects\LeagueListDto
	 * @link https://developer.riotgames.com/api-methods/#league-v3/GET_getChallengerLeague
	 */
	public function getLeagueChallenger( string $game_queue_type ): Objects\LeagueListDto
	{
		$this->setEndpoint("/lol/league/" . self::ENDPOINT_VERSION_LEAGUE . "/challengerleagues/by-queue/{$game_queue_type}")
			->makeCall();

		return new Objects\LeagueListDto($this->getResult());
	}

	/**
	 *   Get master tier leagues.
	 *
	 * @param string $game_queue_type
	 *
	 * @return Objects\LeagueListDto
	 * @link https://developer.riotgames.com/api-methods/#league-v3/GET_getMasterLeague
	 */
	public function getLeagueMaster( string $game_queue_type ): Objects\LeagueListDto
	{
		$this->setEndpoint("/lol/league/" . self::ENDPOINT_VERSION_LEAGUE . "/masterleagues/by-queue/{$game_queue_type}")
			->makeCall();

		return new Objects\LeagueListDto($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Static Data Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_STATICDATA = 'v3';

	/**
	 *   Retrieves champion list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param bool         $data_by_id
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticChampionListDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getChampionList
	 */
	public function getStaticChampions( string $locale = null, string $version = null, bool $data_by_id = null, $tags = null ): StaticData\StaticChampionListDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/champions")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("dataById", $data_by_id ? 'true' : 'false')
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticChampionListDto($this->getResult());
	}

	/**
	 *   Retrieves a champion by its ID.
	 *
	 * @param int          $champion_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticChampionDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getChampionById
	 */
	public function getStaticChampion( int $champion_id, string $locale = null, string $version = null, $tags = null ): StaticData\StaticChampionDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/champions/{$champion_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticChampionDto($this->getResult());
	}

	/**
	 *   Retrieves item list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticItemListDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getItemList
	 */
	public function getStaticItems( string $locale = null, string $version = null, $tags = null ): StaticData\StaticItemListDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/items")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticItemListDto($this->getResult());
	}

	/**
	 *   Retrieves item by its unique ID.
	 *
	 * @param int          $item_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticItemDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getItemById
	 */
	public function getStaticItem( int $item_id, string $locale = null, string $version = null, $tags = null ): StaticData\StaticItemDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/items/{$item_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticItemDto($this->getResult());
	}

	/**
	 *   Retrieve language strings data.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticLanguageStringsDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getLanguageStrings
	 */
	public function getStaticLanguageStrings( string $locale = null, string $version = null ): StaticData\StaticLanguageStringsDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/language-strings")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->makeCall();

		return new StaticData\StaticLanguageStringsDto($this->getResult());
	}

	/**
	 *   Retrieve supported languages data.
	 *
	 * @return array
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getLanguages
	 */
	public function getStaticLanguages(): array
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/languages")
			->makeCall();

		return $this->getResult();
	}

	/**
	 *   Retrieve map data.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticMapDataDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getMapData
	 */
	public function getStaticMaps( string $locale = null, string $version = null ): StaticData\StaticMapDataDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/maps")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->makeCall();

		return new StaticData\StaticMapDataDto($this->getResult());
	}

	/**
	 *   Retrieves mastery list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticMasteryListDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getMasteryList
	 */
	public function getStaticMasteries( string $locale = null, string $version = null, $tags = null ): StaticData\StaticMasteryListDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/masteries")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticMasteryListDto($this->getResult());
	}

	/**
	 *   Retrieves mastery by its unique ID.
	 *
	 * @param int          $mastery_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticMasteryDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getMasteryById
	 */
	public function getStaticMastery( int $mastery_id, string $locale = null, string $version = null, $tags = null ): StaticData\StaticMasteryDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/masteries/{$mastery_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticMasteryDto($this->getResult());
	}

	/**
	 *   Retrieve profile icon list.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticProfileIconDataDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getProfileIcons
	 */
	public function getStaticProfileIcons( string $locale = null, string $version = null ): StaticData\StaticProfileIconDataDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/profile-icons")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->makeCall();

		return new StaticData\StaticProfileIconDataDto($this->getResult());
	}

	/**
	 *   Retrieve realm data. (Region versions)
	 *
	 * @return StaticData\StaticRealmDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getRealm
	 */
	public function getStaticRealm(): StaticData\StaticRealmDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/realms")
			->makeCall();

		return new StaticData\StaticRealmDto($this->getResult());
	}

	/**
	 *   Retrieves rune list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticRuneListDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getRuneList
	 */
	public function getStaticRunes( string $locale = null, string $version = null, $tags = null ): StaticData\StaticRuneListDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/runes")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticRuneListDto($this->getResult());
	}

	/**
	 *   Retrieves rune by its unique ID.
	 *
	 * @param int          $rune_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticRuneDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getRuneById
	 */
	public function getStaticRune( int $rune_id, string $locale = null, string $version = null, $tags = null ): StaticData\StaticRuneDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/runes/{$rune_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticRuneDto($this->getResult());
	}

	/**
	 *   Retrieves summoner spell list.
	 *
	 * @param string       $locale
	 * @param string       $version
	 * @param bool         $data_by_id
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticSummonerSpellListDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getSummonerSpellList
	 */
	public function getStaticSummonerSpells( string $locale = null, string $version = null, bool $data_by_id = false, $tags = null ): StaticData\StaticSummonerSpellListDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/summoner-spells")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("dataById", $data_by_id ? 'true' : 'false')
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticSummonerSpellListDto($this->getResult());
	}

	/**
	 *   Retrieves summoner spell by its unique ID.
	 *
	 * @param int          $summoner_spell_id
	 * @param string       $locale
	 * @param string       $version
	 * @param string|array $tags
	 *
	 * @return StaticData\StaticSummonerSpellDto
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getSummonerSpellById
	 */
	public function getStaticSummonerSpell( int $summoner_spell_id, string $locale = null, string $version = null, $tags = null ): StaticData\StaticSummonerSpellDto
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/summoner-spells/{$summoner_spell_id}")
			->addQuery("locale", $locale)
			->addQuery("version", $version)
			->addQuery("tags", $tags)
			->makeCall();

		return new StaticData\StaticSummonerSpellDto($this->getResult());
	}

	/**
	 *   Retrieve version data.
	 *
	 * @return array
	 * @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getVersions
	 */
	public function getStaticVersions(): array
	{
		$this->setEndpoint("/lol/static-data/" . self::ENDPOINT_VERSION_STATICDATA . "/versions")
			->makeCall();

		return $this->getResult();
	}


	/****************************************d*d*
	 *
	 *  Status Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#lol-status-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_STATUS = 'v3';

	/**
	 *   Get status data - shard list.
	 *
	 * @return Objects\ShardStatus
	 * @link https://developer.riotgames.com/api-methods/#lol-status-v3/GET_getShardData
	 */
	public function getStatusData(): Objects\ShardStatus
	{
		$this->setEndpoint("/lol/status/" . self::ENDPOINT_VERSION_STATUS . "/shard-data")
			->makeCall();

		return new Objects\ShardStatus($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Match Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#match-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_MATCH = 'v3';

	/**
	 *   Retrieve match by match ID.
	 *
	 * @param int $match_id
	 *
	 * @return Objects\MatchDto
	 * @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatch
	 */
	public function getMatch( int $match_id ): Objects\MatchDto
	{
		$this->setEndpoint("/lol/match/" . self::ENDPOINT_VERSION_MATCH . "/matches/{$match_id}")
			->makeCall();

		return new Objects\MatchDto($this->getResult());
	}

	/**
	 *   Retrieve match by match ID and tournament code.
	 *
	 * @param int    $match_id
	 * @param string $tournament_code
	 *
	 * @return Objects\MatchDto
	 * @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchByTournamentCode
	 */
	public function getMatchByTournamentCode( int $match_id, string $tournament_code ): Objects\MatchDto
	{
		$this->setEndpoint("/lol/match/" . self::ENDPOINT_VERSION_MATCH . "/matches/{$match_id}/by-tournament-code/{$tournament_code}")
			->makeCall();

		return new Objects\MatchDto($this->getResult());
	}

	/**
	 *   Retrieve list of match IDs by tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return array
	 * @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchIdsByTournamentCode
	 */
	public function getMatchIdsByTournamentCode( string $tournament_code ): array
	{
		$this->setEndpoint("/lol/match/" . self::ENDPOINT_VERSION_MATCH . "/matches/by-tournament-code/{$tournament_code}/ids")
			->makeCall();

		return $this->getResult();
	}

	/**
	 *   Retrieve matchlist by account ID.
	 *
	 * @param int       $account_id
	 * @param int|array $queue
	 * @param int|array $season
	 * @param int|array $champion
	 * @param int       $beginTime
	 * @param int       $endTime
	 * @param int       $beginIndex
	 * @param int       $endIndex
	 *
	 * @return Objects\MatchlistDto
	 * @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchlist
	 */
	public function getMatchlistByAccount( int $account_id, $queue = null, $season = null, $champion = null, int $beginTime = null, int $endTime = null, int $beginIndex = null, int $endIndex = null ): Objects\MatchlistDto
	{
		$this->setEndpoint("/lol/match/" . self::ENDPOINT_VERSION_MATCH . "/matchlists/by-account/{$account_id}")
			->addQuery('queue', $queue)
			->addQuery('season', $season)
			->addQuery('champion', $champion)
			->addQuery('beginTime', $beginTime)
			->addQuery('endTime', $endTime)
			->addQuery('beginIndex', $beginIndex)
			->addQuery('endIndex', $endIndex)
			->makeCall();

		return new Objects\MatchlistDto($this->getResult());
	}

	/**
	 *   Retrieve recent matchlist by account ID. (20 latest games)
	 *
	 * @param int $account_id
	 *
	 * @return Objects\MatchlistDto
	 * @link https://developer.riotgames.com/api-methods/#match-v3/GET_getRecentMatchlist
	 */
	public function getRecentMatchlistByAccount( int $account_id ): Objects\MatchlistDto
	{
		$this->setEndpoint("/lol/match/" . self::ENDPOINT_VERSION_MATCH . "/matchlists/by-account/{$account_id}/recent")
			->makeCall();

		return new Objects\MatchlistDto($this->getResult());
	}

	/**
	 *   Retrieve matchlsit by account ID.
	 *
	 * @param int $match_id
	 *
	 * @return Objects\MatchTimelineDto
	 * @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchTimeline
	 */
	public function getMatchTimeline( int $match_id ): Objects\MatchTimelineDto
	{
		$this->setEndpoint("/lol/match/" . self::ENDPOINT_VERSION_MATCH . "/timelines/by-match/{$match_id}")
			->makeCall();

		return new Objects\MatchTimelineDto($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Summoner Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#summoner-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_SUMMONER = 'v3';

	/**
	 *   Get single summoner object for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\SummonerDto
	 * @link https://developer.riotgames.com/api-methods/#summoner-v3/GET_getBySummonerId
	 */
	public function getSummoner( int $summoner_id ): Objects\SummonerDto
	{
		$this->setEndpoint("/lol/summoner/" . self::ENDPOINT_VERSION_SUMMONER . "/summoners/{$summoner_id}")
			->makeCall();

		return new Objects\SummonerDto($this->getResult());
	}

	/**
	 *   Get summoner name for a given summoner name.
	 *
	 * @param string $summoner_name
	 *
	 * @return Objects\SummonerDto
	 * @link https://developer.riotgames.com/api-methods/#summoner-v3/GET_getBySummonerName
	 */
	public function getSummonerByName( string $summoner_name ): Objects\SummonerDto
	{
		$summoner_name = str_replace(' ', '', $summoner_name);

		$this->setEndpoint("/lol/summoner/" . self::ENDPOINT_VERSION_SUMMONER . "/summoners/by-name/{$summoner_name}")
			->makeCall();

		return new Objects\SummonerDto($this->getResult());
	}

	/**
	 *   Get single summoner object for a given summoner's account ID.
	 *
	 * @param int $account_id
	 *
	 * @return Objects\SummonerDto
	 * @link https://developer.riotgames.com/api-methods/#summoner-v3/GET_getByAccountId
	 */
	public function getSummonerByAccount( int $account_id ): Objects\SummonerDto
	{
		$this->setEndpoint("/lol/summoner/" . self::ENDPOINT_VERSION_SUMMONER . "/summoners/by-account/{$account_id}")
			->makeCall();

		return new Objects\SummonerDto($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Masteries Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#masteries-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_MASTERIES = 'v3';

	/**
	 *   Get mastery pages for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\MasteryPagesDto
	 * @link https://developer.riotgames.com/api-methods/#masteries-v3/GET_getMasteryPagesBySummonerId
	 */
	public function getMasteriesBySummoner( int $summoner_id ): Objects\MasteryPagesDto
	{
		$this->setEndpoint("/lol/platform/" . self::ENDPOINT_VERSION_MASTERIES . "/masteries/by-summoner/{$summoner_id}")
			->makeCall();

		return new Objects\MasteryPagesDto($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Runes Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#runes-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_RUNES = 'v3';

	/**
	 *   Get rune pages for a given summoner ID.
	 *
	 * @param int $summoner_id
	 *
	 * @return Objects\RunePagesDto
	 * @link https://developer.riotgames.com/api-methods/#runes-v3/GET_getRunePagesBySummonerId
	 */
	public function getRunesBySummoner( int $summoner_id ): Objects\RunePagesDto
	{
		$this->setEndpoint("/lol/platform/" . self::ENDPOINT_VERSION_RUNES . "/runes/by-summoner/{$summoner_id}")
			->makeCall();

		return new Objects\RunePagesDto($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Tournament Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_TOURNAMENT = 'v3';

	/**
	 *   Creates set of tournament codes for given tournament.
	 *
	 * @param int                      $tournament_id
	 * @param int                      $count
	 * @param TournamentCodeParameters $parameters
	 *
	 * @return array
	 * @throws RequestParameterException
	 * @link https://developer.riotgames.com/api-methods/#tournament-v3/POST_createTournamentCode
	 */
	public function createTournamentCodes( int $tournament_id, int $count, TournamentCodeParameters $parameters ): array
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->createTournamentCodes_STUB($tournament_id, $count, $parameters);

		if ($parameters->teamSize <= 0)
			throw new RequestParameterException('Team size (teamSize) must be greater than or equal to 1.');

		if ($parameters->teamSize >= 6)
			throw new RequestParameterException('Team size (teamSize) must be less than or equal to 5.');

		if (empty($parameters->allowedSummonerIds->participants))
			throw new RequestParameterException('List of participants (allowedSummonerIds->participants) may not be empty. If you wish to allow anyone, fill it with 0, 1, 2, 3, etc.');

		if ($parameters->teamSize * 2 > count($parameters->allowedSummonerIds->participants))
			throw new RequestParameterException('Not enough players to fill teams (more participants required).');

		if (in_array($parameters->pickType, self::TOURNAMENT_ALLOWED_PICK_TYPES, true) == false)
			throw new RequestParameterException('Value of pick type (pickType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_PICK_TYPES));

		if (in_array($parameters->mapType, self::TOURNAMENT_ALLOWED_MAPS, true) == false)
			throw new RequestParameterException('Value of map type (mapType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_MAPS));

		if (in_array($parameters->spectatorType, self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES, true) == false)
			throw new RequestParameterException('Value of spectator type (spectatorType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES));

		$data = json_encode($parameters);

		$this->setEndpoint("/lol/tournament/" . self::ENDPOINT_VERSION_TOURNAMENT . "/codes")
			->addQuery('tournamentId', $tournament_id)
			->addQuery('count', $count)
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Updates tournament code's settings.
	 *
	 * @param string                                 $tournament_code
	 * @param Objects\TournamentCodeUpdateParameters $parameters
	 *
	 * @return Objects\LobbyEventDTOWrapper
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @link https://developer.riotgames.com/api-methods/#tournament-v3/PUT_updateCode
	 */
	public function editTournamentCode( string $tournament_code, Objects\TournamentCodeUpdateParameters $parameters )
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			throw new RequestException('This endpoint is not available in interim mode.');

		if (in_array($parameters->pickType, self::TOURNAMENT_ALLOWED_PICK_TYPES, true) == false)
			throw new RequestParameterException('Value of pick type (pickType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_PICK_TYPES));

		if (in_array($parameters->mapType, self::TOURNAMENT_ALLOWED_MAPS, true) == false)
			throw new RequestParameterException('Value of map type (mapType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_MAPS));

		if (in_array($parameters->spectatorType, self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES, true) == false)
			throw new RequestParameterException('Value of spectator type (spectatorType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES));

		$data = json_encode($parameters);

		$this->setEndpoint("/lol/tournament/" . self::ENDPOINT_VERSION_TOURNAMENT . "/codes/{$tournament_code}")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL, self::METHOD_PUT);

		return $this->getResult();
	}

	/**
	 *   Retrieves tournament code settings for given tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\TournamentCodeDto
	 * @throws RequestException
	 * @link https://developer.riotgames.com/api-methods/#tournament-v3/GET_getTournamentCode
	 */
	public function getTournamentCodeData( string $tournament_code ): Objects\TournamentCodeDto
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			throw new RequestException('This endpoint is not available in interim mode.');

		$this->setEndpoint("/lol/tournament/" . self::ENDPOINT_VERSION_TOURNAMENT . "/codes/{$tournament_code}")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL);

		return new Objects\TournamentCodeDto($this->getResult());
	}

	/**
	 *   Creates a tournament provider and returns its ID.
	 *
	 * @param ProviderRegistrationParameters $parameters
	 *
	 * @return int
	 * @throws RequestParameterException
	 * @link https://developer.riotgames.com/api-methods/#tournament-v3/POST_registerProviderData
	 */
	public function createTournamentProvider( ProviderRegistrationParameters $parameters ): int
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->createTournamentProvider_STUB($parameters);

		if (empty($parameters->url))
			throw new RequestParameterException('Callback URL (url) may not be empty.');

		if (in_array(strtolower($parameters->region), self::TOURNAMENT_ALLOWED_REGIONS, true) == false)
			throw new RequestParameterException('Value of region (region) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_REGIONS));

		$parameters->region = strtoupper($parameters->region);

		$data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

		$this->setEndpoint("/lol/tournament/" . self::ENDPOINT_VERSION_TOURNAMENT . "/providers")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Creates a tournament and returns its ID.
	 *
	 * @param TournamentRegistrationParameters $parameters
	 *
	 * @return int
	 * @throws RequestParameterException
	 * @link https://developer.riotgames.com/api-methods/#tournament-v3/POST_registerTournament
	 */
	public function createTournament( TournamentRegistrationParameters $parameters ): int
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->createTournament_STUB($parameters);

		if (empty($parameters->name))
			throw new RequestParameterException('Tournament name (name) may not be empty.');

		if ($parameters->providerId <= 0)
			throw new RequestParameterException('ProviderID (providerId) must be greater than or equal to 1.');

		$data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

		$this->setEndpoint("/lol/tournament/" . self::ENDPOINT_VERSION_TOURNAMENT . "/tournaments")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Gets a list of lobby events by tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\LobbyEventDTOWrapper
	 * @throws GeneralException
	 * @link https://developer.riotgames.com/api-methods/#tournament-v3/GET_getLobbyEventsByCode
	 */
	public function getTournamentLobbyEvents( string $tournament_code ): Objects\LobbyEventDTOWrapper
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->getTournamentLobbyEvents_STUB($tournament_code);

		$this->setEndpoint("/lol/tournament/" . self::ENDPOINT_VERSION_TOURNAMENT . "/lobby-events/by-code/{$tournament_code}")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL);

		return new Objects\LobbyEventDtoWrapper($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Tournament Stub Endpoint Methods
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v3
	 *
	 ********************************************/
	const ENDPOINT_VERSION_TOURNAMENT_STUB = 'v3';

	/**
	 *   Create a mock tournament code for the given tournament.
	 *
	 * @param int                      $tournament_id
	 * @param int                      $count
	 * @param TournamentCodeParameters $parameters
	 *
	 * @return array
	 * @throws RequestParameterException
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v3/POST_createTournamentCode
	 *
	 * @internal
	 */
	public function createTournamentCodes_STUB( int $tournament_id, int $count, TournamentCodeParameters $parameters ): array
	{
		if ($parameters->teamSize <= 0)
			throw new RequestParameterException('Team size (teamSize) must be greater than or equal to 1.');

		if ($parameters->teamSize >= 6)
			throw new RequestParameterException('Team size (teamSize) must be less than or equal to 5.');

		if (empty($parameters->allowedSummonerIds->participants))
			throw new RequestParameterException('List of participants (allowedSummonerIds->participants) may not be empty. If you wish to allow anyone, fill it with 0, 1, 2, 3, etc.');

		if ($parameters->teamSize * 2 > count($parameters->allowedSummonerIds->participants))
			throw new RequestParameterException('Not enough players to fill teams (more participants required).');

		if (in_array($parameters->pickType, self::TOURNAMENT_ALLOWED_PICK_TYPES, true) == false)
			throw new RequestParameterException('Value of pick type (pickType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_PICK_TYPES));

		if (in_array($parameters->mapType, self::TOURNAMENT_ALLOWED_MAPS, true) == false)
			throw new RequestParameterException('Value of map type (mapType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_MAPS));

		if (in_array($parameters->spectatorType, self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES, true) == false)
			throw new RequestParameterException('Value of spectator type (spectatorType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES));

		$data = json_encode($parameters);

		$this->setEndpoint("/lol/tournament-stub/" . self::ENDPOINT_VERSION_TOURNAMENT_STUB . "/codes")
			->addQuery('tournamentId', $tournament_id)
			->addQuery('count', $count)
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Creates a mock tournament provider and returns its ID.
	 *
	 * @param ProviderRegistrationParameters $parameters
	 *
	 * @return int
	 * @throws RequestParameterException
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v3/POST_registerProviderData
	 *
	 * @internal
	 */
	public function createTournamentProvider_STUB( ProviderRegistrationParameters $parameters ): int
	{
		if (empty($parameters->url))
			throw new RequestParameterException('Callback URL (url) may not be empty.');

		if (in_array(strtolower($parameters->region), self::TOURNAMENT_ALLOWED_REGIONS, true) == false)
			throw new RequestParameterException('Value of region (region) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_REGIONS));

		$parameters->region = strtoupper($parameters->region);

		$data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

		$this->setEndpoint("/lol/tournament-stub/" . self::ENDPOINT_VERSION_TOURNAMENT_STUB . "/providers")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Creates a mock tournament and returns its ID.
	 *
	 * @param TournamentRegistrationParameters $parameters
	 *
	 * @return int
	 * @throws RequestParameterException
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v3/POST_registerTournament
	 *
	 * @internal
	 */
	public function createTournament_STUB( TournamentRegistrationParameters $parameters ): int
	{
		if (empty($parameters->name))
			throw new RequestParameterException('Tournament name (name) may not be empty.');

		if ($parameters->providerId <= 0)
			throw new RequestParameterException('ProviderID (providerId) must be greater than or equal to 1.');

		$data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

		$this->setEndpoint("/lol/tournament-stub/" . self::ENDPOINT_VERSION_TOURNAMENT_STUB . "/tournaments")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Gets a mock list of lobby events by tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\LobbyEventDtoWrapper
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v3/GET_getLobbyEventsByCode
	 *
	 * @internal
	 */
	public function getTournamentLobbyEvents_STUB( string $tournament_code ): Objects\LobbyEventDtoWrapper
	{
		$this->setEndpoint("/lol/tournament-stub/" . self::ENDPOINT_VERSION_TOURNAMENT_STUB . "/lobby-events/by-code/{$tournament_code}")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::GLOBAL);

		return new Objects\LobbyEventDtoWrapper($this->getResult());
	}


	/****************************************d*d*
	 *
	 *  Endpoint for testing purposes
	 *
	 ********************************************/

	/**
	 * @param             $specs
	 * @param string|null $region
	 * @param string|null $method
	 *
	 * @return mixed
	 * @internal
	 */
	public function makeTestEndpointCall( $specs, string $region = null, string $method = null )
	{
		$this->setEndpoint("/lol/test-endpoint/v0/" . $specs)
			->makeCall($region ?: null, $method ?: self::METHOD_GET);

		//return $this->getResult();
	}
}