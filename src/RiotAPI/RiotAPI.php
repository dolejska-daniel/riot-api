<?php

/**
 * Copyright (C) 2016-2018  Daniel Dolejška
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
use RiotAPI\Objects\IApiObjectExtension;
use RiotAPI\Objects\StaticData;
use RiotAPI\Objects\ProviderRegistrationParameters;
use RiotAPI\Objects\TournamentCodeParameters;
use RiotAPI\Objects\TournamentCodeUpdateParameters;
use RiotAPI\Objects\TournamentRegistrationParameters;

use RiotAPI\Exceptions\GeneralException;
use RiotAPI\Exceptions\RequestException;
use RiotAPI\Exceptions\RequestParameterException;
use RiotAPI\Exceptions\ServerException;
use RiotAPI\Exceptions\ServerLimitException;
use RiotAPI\Exceptions\SettingsException;


use DataDragonAPI\DataDragonAPI;
use DataDragonAPI\Exception as DataDragonException;


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
		SET_PLATFORM              = 'SET_PLATFORM',              /** Set internally by setting region **/
		SET_VERIFY_SSL            = 'SET_VERIFY_SSL',            /** Specifies whether or not to verify SSL (verification often fails on localhost) **/
		SET_KEY                   = 'SET_KEY',                   /** API key used by default **/
		SET_KEY_INCLUDE_TYPE      = 'SET_KEY_INCLUDE_TYPE',      /** API key request include type (header, query) **/
		SET_TOURNAMENT_KEY        = 'SET_TOURNAMENT_KEY',        /** API key used when working with tournaments **/
		SET_INTERIM               = 'SET_INTERIM',               /** Used to set whether or not is your application in Interim mode (Tournament STUB endpoints) **/
		SET_CACHE_PROVIDER        = 'SET_CACHE_PROVIDER',        /** Specifies CacheProvider class name **/
		SET_CACHE_PROVIDER_PARAMS = 'SET_CACHE_PROVIDER_PARAMS', /** Specifies parameters passed to CacheProvider class when initializing **/
		SET_CACHE_RATELIMIT       = 'SET_CACHE_RATELIMIT',       /** Used to set whether or not to saveCallData and check API key's rate limit **/
		SET_CACHE_CALLS           = 'SET_CACHE_CALLS',           /** Used to set whether or not to temporary saveCallData API call's results **/
		SET_CACHE_CALLS_LENGTH    = 'SET_CACHE_CALLS_LENGTH',    /** Specifies for how long are call results saved **/
		SET_EXTENSIONS            = 'SET_EXTENSIONS',            /** Specifies ApiObject's extensions **/
		SET_DATADRAGON_INIT       = 'SET_DATADRAGON_INIT',       /** Specifies whether or not should DataDragonAPI be initialized by this library **/
		SET_DATADRAGON_PARAMS     = 'SET_DATADRAGON_PARAMS',     /** Specifies parameters passed to DataDragonAPI when initialized **/
		SET_STATICDATA_LINKING    = 'SET_STATICDATA_LINKING',
		SET_STATICDATA_LOCALE     = 'SET_STATICDATA_LOCALE',
		SET_STATICDATA_VERSION    = 'SET_STATICDATA_VERSION',
		SET_STATICDATA_TAGS       = 'SET_STATICDATA_TAGS',
		SET_CALLBACKS_BEFORE      = 'SET_CALLBACKS_BEFORE',
		SET_CALLBACKS_AFTER       = 'SET_CALLBACKS_AFTER',
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
		CACHE_KEY_RLC = 'rate-limit.cache',
		CACHE_KEY_CCC = 'api-calls.cache';

	/**
	 * Available API headers.
	 */
	const
		HEADER_API_KEY                = 'X-Riot-Token',
		HEADER_RATELIMIT_TYPE         = 'X-Rate-Limit-Type',
		HEADER_METHOD_RATELIMIT       = 'X-Method-Rate-Limit',
		HEADER_METHOD_RATELIMIT_COUNT = 'X-Method-Rate-Limit-Count',
		HEADER_APP_RATELIMIT          = 'X-App-Rate-Limit',
		HEADER_APP_RATELIMIT_COUNT    = 'X-App-Rate-Limit-Count';

	/**
	 * Pick type constants.
	 */
	const
		PICK_BLIND            = 'BLIND_PICK',
		PICK_DRAFT            = 'DRAFT_MODE',
		PICK_RANDOM           = 'ALL_RANDOM',
		PICK_DRAFT_TOURNAMENT = 'TOURNAMENT_DRAFT';

	/**
	 * Map constants.
	 */
	const
		MAP_SUMMONERS_RIFT   = 'SUMMONERS_RIFT',
		MAP_TWISTED_TREELINE = 'TWISTED_TREELINE',
		MAP_HOWLING_ABYSS    = 'HOWLING_ABYSS';

	/**
	 * Spectator type constants.
	 */
	const
		SPECTATOR_NONE       = 'NONE',
		SPECTATOR_LOBBY_ONLY = 'LOBBYONLY',
		SPECTATOR_ALL        = 'ALL';

	/**
	 * Constants required for tournament API calls.
	 */
	const
		TOURNAMENT_ALLOWED_PICK_TYPES = [
			self::PICK_BLIND,
			self::PICK_DRAFT,
			self::PICK_RANDOM,
			self::PICK_DRAFT_TOURNAMENT,
		],
		TOURNAMENT_ALLOWED_MAPS = [
			self::MAP_SUMMONERS_RIFT,
			self::MAP_TWISTED_TREELINE,
			self::MAP_HOWLING_ABYSS,
		],
		TOURNAMENT_ALLOWED_SPECTATOR_TYPES = [
			self::SPECTATOR_NONE,
			self::SPECTATOR_LOBBY_ONLY,
			self::SPECTATOR_ALL,
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
		];

	const
		//  List of required setting keys
		SETTINGS_REQUIRED = [
			self::SET_KEY,
			self::SET_REGION,
		],
		//  List of allowed setting keys
		SETTINGS_ALLOWED = [
			self::SET_KEY,
			self::SET_REGION,
			self::SET_VERIFY_SSL,
			self::SET_KEY_INCLUDE_TYPE,
			self::SET_TOURNAMENT_KEY,
			self::SET_INTERIM,
			self::SET_CACHE_PROVIDER,
			self::SET_CACHE_PROVIDER_PARAMS,
			self::SET_CACHE_RATELIMIT,
			self::SET_CACHE_CALLS,
			self::SET_CACHE_CALLS_LENGTH,
			self::SET_USE_DUMMY_DATA,
			self::SET_SAVE_DUMMY_DATA,
			self::SET_EXTENSIONS,
			self::SET_DATADRAGON_INIT,
			self::SET_DATADRAGON_PARAMS,
			self::SET_STATICDATA_LINKING,
			self::SET_STATICDATA_LOCALE,
			self::SET_STATICDATA_VERSION,
			self::SET_STATICDATA_TAGS,
			self::SET_CALLBACKS_BEFORE,
			self::SET_CALLBACKS_AFTER,
			self::SET_API_BASEURL,
		],
		SETTINGS_INIT_ONLY = [
			self::SET_API_BASEURL,
			self::SET_DATADRAGON_INIT,
			self::SET_DATADRAGON_PARAMS,
		];

	/**
	 *   Available resource list.
	 *
	 * @var array $resources
	 */
	protected $resources = [
		self::RESOURCE_CHAMPION,
		self::RESOURCE_CHAMPIONMASTERY,
		self::RESOURCE_LEAGUE,
		self::RESOURCE_STATICDATA,
		self::RESOURCE_STATUS,
		self::RESOURCE_MATCH,
		self::RESOURCE_SPECTATOR,
		self::RESOURCE_SUMMONER,
		self::RESOURCE_THIRD_PARTY_CODE,
		self::RESOURCE_TOURNAMENT,
		self::RESOURCE_TOURNAMENT_STUB,
	];

	/**
	 *   Contains current settings.
	 *
	 * @var array $settings
	 */
	protected $settings = array(
		self::SET_API_BASEURL      => '.api.riotgames.com',
		self::SET_KEY_INCLUDE_TYPE => self::KEY_AS_HEADER,
		self::SET_VERIFY_SSL       => true,
		self::SET_USE_DUMMY_DATA   => false,
		self::SET_SAVE_DUMMY_DATA  => false,
		self::SET_STATICDATA_TAGS  => [ 'info' ],
	);

	/** @var IRegion $regions */
	public $regions;

	/** @var IPlatform $platforms */
	public $platforms;


	/** @var ICacheProvider $cache */
	protected $cache;


	/** @var IRateLimitControl $rlc */
	protected $rlc;

	/** @var int $rlc_savetime */
	protected $rlc_savetime = 3600;

	/** @var ICallCacheControl $ccc */
	protected $ccc;

	/** @var int $ccc_savetime */
	protected $ccc_savetime = 60;


	/** @var string $used_key */
	protected $used_key = self::SET_KEY;

	/** @var string $used_method */
	protected $used_method;

	/** @var string $endpoint */
	protected $endpoint;

	/** @var string $resource */
	protected $resource;

	/** @var string $resource_endpoint */
	protected $resource_endpoint;


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

	/** @var int $result_code */
	protected $result_code;

	/** @var callable[] $beforeCall */
	protected $beforeCall = [];

	/** @var callable[] $afterCall */
	protected $afterCall = [];


	/**
	 *   RiotAPI constructor.
	 *
	 * @param array $settings
	 * @param IRegion $custom_regionDataProvider
	 * @param IPlatform $custom_platformDataProvider
	 *
	 * @throws SettingsException
	 * @throws GeneralException
	 * @throws DataDragonException\RequestException
	 */
	public function __construct( array $settings, IRegion $custom_regionDataProvider = null, IPlatform $custom_platformDataProvider = null )
	{
		//  Checks if required settings are present
		foreach (self::SETTINGS_REQUIRED as $key)
			if (array_search($key, array_keys($settings), true) === false)
				throw new SettingsException("Required settings parameter '$key' is missing!");

		//  Checks SET_KEY_INCLUDE_TYPE value
		if (isset($settings[self::SET_KEY_INCLUDE_TYPE])
			&& in_array($settings[self::SET_KEY_INCLUDE_TYPE], [ self::KEY_AS_HEADER, self::KEY_AS_QUERY_PARAM ], true) == false)
		{
			throw new SettingsException("Value of settings parameter '" . self::SET_KEY_INCLUDE_TYPE . "' is not valid.");
		}

		//  Checks SET_EXTENSIONS value
		if (isset($settings[self::SET_EXTENSIONS]))
		{
			if (!is_array($settings[self::SET_EXTENSIONS]))
			{
				throw new SettingsException("Value of settings parameter '" . self::SET_EXTENSIONS . "' is not valid.");
			}
			else
			{
				foreach ($settings[self::SET_EXTENSIONS] as $api_object => $extender)
				{
					try
					{
						$ref = new \ReflectionClass($extender);
						if ($ref->implementsInterface(IApiObjectExtension::class) == false)
							throw new SettingsException("ObjectExtender '$extender' does not implement IApiObjectExtension interface.");

						if ($ref->isInstantiable() == false)
							throw new SettingsException("ObjectExtender '$extender' is not instantiable.");
					}
					catch (\ReflectionException $ex)
					{
						throw new SettingsException("Value of settings parameter '" . self::SET_EXTENSIONS . "' is not valid.", 0, $ex);
					}
				}
			}
		}

		if ($this->getSetting(self::SET_DATADRAGON_INIT))
			DataDragonAPI::initByCdn($this->getSetting(self::SET_DATADRAGON_PARAMS, []));

		//  Assigns allowed settings
		foreach (self::SETTINGS_ALLOWED as $key)
			if (isset($settings[$key]))
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

		//  Call data are going to be cached
		if ($this->getSetting(self::SET_CACHE_CALLS))
			$this->_setupCacheCalls();

		if ($this->getSetting(self::SET_STATICDATA_LINKING) == true)
		{
			$calls_caching_settings = $this->getSetting(self::SET_CACHE_CALLS_LENGTH, []);
			if ($this->getSetting(self::SET_CACHE_CALLS) == false
				|| (is_array($calls_caching_settings) && (isset($calls_caching_settings[self::RESOURCE_STATICDATA]) == false || $calls_caching_settings[self::RESOURCE_STATICDATA] <= 0))
				|| $calls_caching_settings <= 0)
			{
				throw new SettingsException('Using STATICDATA LINKING feature requires enabled call caching on STATICDATA RESOURCE.');
			}
		}


		//  Set up before calls callbacks
		$this->_setupBeforeCalls();

		//  Set up afterl calls callbacks
		$this->_setupAfterCalls();

		//  Sets platform based on current region
		$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($this->getSetting(self::SET_REGION)));
	}

	/**
	 *   Initializes library cache provider.
	 *
	 * @throws SettingsException
	 */
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
			//  and creates new instance of this cache provider
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

	/**
	 *   Initializes library call caching.
	 *
	 * @throws SettingsException
	 */
	public function _setupCacheCalls()
	{
		if ($this->isSettingSet(self::SET_CACHE_CALLS_LENGTH) == false)
		{
			//  Value is not set, setting default values
			$this->setSetting(self::SET_CACHE_CALLS_LENGTH, [
				self::RESOURCE_CHAMPION         => 60 * 10,
				self::RESOURCE_CHAMPIONMASTERY  => 60 * 60,
				self::RESOURCE_LEAGUE           => 60 * 10,
				self::RESOURCE_MATCH            => 0,
				self::RESOURCE_SPECTATOR        => 0,
				self::RESOURCE_STATICDATA       => 60 * 60 * 24,
				self::RESOURCE_STATUS           => 60,
				self::RESOURCE_SUMMONER         => 60 * 60,
				self::RESOURCE_THIRD_PARTY_CODE => 0,
				self::RESOURCE_TOURNAMENT       => 0,
				self::RESOURCE_TOURNAMENT_STUB  => 0,
			]);
		}
		else
		{
			$lengths = $this->getSetting(self::SET_CACHE_CALLS_LENGTH);

			//  Resource caching lengths are specified
			if (is_array($lengths))
			{
				array_walk($lengths, function ($value, $key) {
					if ((!is_integer($value) && !is_null($value)) || strpos($key, ':') == false)
						throw new SettingsException("Value of settings parameter '" . self::SET_CACHE_CALLS_LENGTH . "' is not valid.");
				});
			}
			elseif (!is_integer($lengths))
				throw new SettingsException("Value of settings parameter '" . self::SET_CACHE_CALLS_LENGTH . "' is not valid.");

			if (is_array($lengths))
			{
				//  The value is array, let's check it
				$new_value = [];
				$resources = $this->resources;
				foreach ($resources as $resource)
				{
					if (isset($lengths[$resource]))
					{
						if ($lengths[$resource] > $this->ccc_savetime)
							$this->ccc_savetime = $lengths[$resource];

						$new_value[$resource] = $lengths[$resource];
					}
					else
						$new_value[$resource] = null;
				}

				$this->setSetting(self::SET_CACHE_CALLS_LENGTH, $new_value);
			}
			else
			{
				//  The value is numeric, lets set the same limit to all resources
				$new_value = [];
				$resources = $this->resources;
				$this->ccc_savetime = $lengths;

				foreach ($resources as $resource)
					$new_value[$resource] = $lengths;

				$this->setSetting(self::SET_CACHE_CALLS_LENGTH, $new_value);
			}
		}
	}

	/**
	 *   Sets up internal callbacks - before the call is made.
	 *
	 * @throws SettingsException
	 */
	protected function _setupBeforeCalls()
	{
		//  API rate limit check before call is made
		$this->beforeCall[] = function () {
			if ($this->getSetting(self::SET_CACHE_RATELIMIT) && $this->rlc != false)
				if ($this->rlc->canCall($this->getSetting($this->used_key), $this->getSetting(self::SET_REGION), $this->getResource(), $this->getResourceEndpoint()) == false)
					throw new ServerLimitException('API call rate limit would be exceeded by this call.');
		};

		$callbacks = $this->getSetting(self::SET_CALLBACKS_BEFORE, []);
		if (is_array($callbacks) == false)
			$callbacks = [$callbacks];

		foreach ($callbacks as $c)
		{
			if (is_callable($c) == false)
				throw new SettingsException("Provided value of '" . self::SET_CALLBACKS_BEFORE . "' option is not valid.");

			$this->beforeCall[] = $c;
		}
	}

	/**
	 *   Sets up internal callbacks - after the call is made.
	 *
	 * @throws SettingsException
	 */
	protected function _setupAfterCalls()
	{
		//  Save ratelimits received with this request if RateLimit cache is enabled
		$this->afterCall[] = function () {
			if ($this->getSetting(self::SET_CACHE_RATELIMIT, false) && $this->rlc != false)
				$this->rlc->registerLimits($this->getSetting($this->used_key), $this->getSetting(self::SET_REGION), $this->getResourceEndpoint(), @$this->result_headers[self::HEADER_APP_RATELIMIT], @$this->result_headers[self::HEADER_METHOD_RATELIMIT]);
		};

		//  Register, that call has been made if RateLimit cache is enabled
		$this->afterCall[] = function () {
			if ($this->getSetting(self::SET_CACHE_RATELIMIT, false) && $this->rlc != false)
				$this->rlc->registerCall($this->getSetting($this->used_key), $this->getSetting(self::SET_REGION), $this->getResourceEndpoint(), @$this->result_headers[self::HEADER_APP_RATELIMIT_COUNT], @$this->result_headers[self::HEADER_METHOD_RATELIMIT_COUNT]);
		};

		//  Save result data, if CallCache is enabled and when the old result has expired
		$this->afterCall[] = function ( $api, $url, $requestHash ) {
			if ($this->getSetting(self::SET_CACHE_CALLS, false) && $this->ccc != false && $this->ccc->isCallCached($requestHash) == false)
			{
				//  Get information for how long to save the data
				if ($timeInterval = @$this->getSetting(self::SET_CACHE_CALLS_LENGTH)[$this->getResource()])
					$this->ccc->saveCallData($requestHash, $this->result_data_raw, $timeInterval);
			}
		};

		//  Save result data as new DummyData if enabled and if data does not already exist
		$this->afterCall[] = function () {
			if ($this->getSetting(self::SET_SAVE_DUMMY_DATA, false) && file_exists($this->_getDummyDataFileName()) == false)
				$this->_saveDummyData();
		};

		//  Save newly cached data
		$this->afterCall[] = function () {
			if ($this->getSetting(self::SET_CACHE_CALLS, false) || $this->getSetting(self::SET_CACHE_RATELIMIT, false))
				$this->saveCache();
		};

		$callbacks = $this->getSetting(self::SET_CALLBACKS_AFTER, []);
		if (is_array($callbacks) == false)
			$callbacks = [$callbacks];

		foreach ($callbacks as $c)
		{
			if (is_callable($c) == false)
				throw new SettingsException("Provided value of '" . self::SET_CALLBACKS_AFTER . "' option is not valid.");

			$this->afterCall[] = $c;
		}
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
			$this->cache->save(self::CACHE_KEY_RLC, $this->rlc, $this->rlc_savetime);
		}

		if ($this->getSetting(self::SET_CACHE_CALLS, false))
		{
			//  save CallCacheControl
			$this->cache->save(self::CACHE_KEY_CCC, $this->ccc, $this->ccc_savetime);
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
	 * @throws SettingsException
	 */
	public function setSetting( string $name, $value ): self
	{
		if (in_array($name, self::SETTINGS_INIT_ONLY))
			throw new SettingsException("Settings option '$name' can only be set on initialization of the library.");

		$this->settings[$name] = $value;
		return $this;
	}

	/**
	 *   Sets new values for specified set of keys in settings.
	 *
	 * @param array $values
	 *
	 * @return RiotAPI
	 * @throws SettingsException
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
	 * @throws SettingsException
	 * @throws GeneralException
	 */
	public function setRegion( string $region ): self
	{
		$this->setSetting(self::SET_REGION, $this->regions->getRegionName($region));
		$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($region));
		return $this;
	}

	/**
	 *   Sets temporary region to be used on API calls. Saves current region.
	 *
	 * @param string $tempRegion
	 *
	 * @return RiotAPI
	 * @throws SettingsException
	 * @throws GeneralException
	 */
	public function setTemporaryRegion( string $tempRegion ): self
	{
		$this->setSetting(self::SET_ORIG_REGION, $this->getSetting(self::SET_REGION));
		$this->setSetting(self::SET_REGION, $this->regions->getRegionName($tempRegion));
		$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($tempRegion));
		return $this;
	}

	/**
	 *   Unets temporary region and returns original region.
	 *
	 * @return RiotAPI
	 * @throws SettingsException
	 * @throws GeneralException
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
	 *   Sets call resource for target endpoint.
	 *
	 * @param string $resource
	 * @param string $endpoint
	 *
	 * @return RiotAPI
	 */
	protected function setResource( string $resource, string $endpoint ): self
	{
		$this->resource = $resource;
		$this->resource_endpoint = $endpoint;
		return $this;
	}

	/**
	 *   Returns call resource for last call.
	 *
	 * @return string
	 */
	protected function getResource(): string
	{
		return $this->resource;
	}

	/**
	 *   Returns call resource and endpoint for last call.
	 *
	 * @return string
	 */
	protected function getResourceEndpoint(): string
	{
		return $this->resource . $this->resource_endpoint;
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
	 *   Returns raw getResult data from the last call.
	 *
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->result_data;
	}

	/**
	 *   Makes call to RiotAPI.
	 *
	 * @param string|null $overrideRegion
	 * @param string $method
	 *
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 * @throws GeneralException
	 *
	 * @internal
	 */
	protected function makeCall( string $overrideRegion = null, string $method = self::METHOD_GET )
	{
		if ($overrideRegion)
			$this->setTemporaryRegion($overrideRegion);

		$this->used_method = $method;

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

		$this->_beforeCall($url, $requestHash);

		$response       = null;
		$headers        = null;
		$response_code  = null;

		if ($this->getSetting(self::SET_USE_DUMMY_DATA, false))
		{
			//  DummyData are supposed to be used
			try
			{
				//  try loading the data
				$this->_loadDummyData($headers, $response, $response_code);
			}
			catch (RequestException $ex)
			{
				//  loading failed, check whether an actual request should be made
				if ($this->getSetting(self::SET_SAVE_DUMMY_DATA, false) == false)
					//  saving is not allowed, dummydata does not exist
					throw new RequestException("No DummyData available for call. " . $this->_getDummyDataFileName());
			}
		}

		//  was response already fetched?
		if (isset($response) == false)
		{
			if ($this->getSetting(self::SET_CACHE_CALLS) && $this->ccc != false && $this->ccc->isCallCached($requestHash))
			{
				//  calls are cached and this request is saved in cache
				$response = $this->ccc->loadCallData($requestHash);
				$response_code = 200;
				$headers = [];
			}
			else
			{
				//  calls are not cached or this request is not cached
				//  perform call to Riot API
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
			throw new RequestException('cURL error ocurred: ' . $curl_error, $curl_errno);
		}

		$this->result_data_raw = $response;
		$this->result_data     = json_decode($response, true);
		$this->result_headers  = $headers;
		$this->result_code     = $response_code;

		$errMessage = "";
		if (!is_null($this->result_data) && isset($this->result_data['status']['message']))
			$errMessage = " ({$this->result_data['status']['message']})";

		if ($response_code == 503)
		{
			throw new ServerException('RiotAPI: Service is unavailable.', $response_code);
		}
		elseif ($response_code == 500)
		{
			throw new ServerException('RiotAPI: Internal server error occured.', $response_code);
		}
		elseif ($response_code == 429)
		{
			throw new ServerLimitException('RiotAPI: Rate limit for this API key was exceeded.' . $errMessage, $response_code);
		}
		elseif ($response_code == 415)
		{
			throw new RequestException('Request: Unsupported media type.' . $errMessage, $response_code);
		}
		elseif ($response_code == 404)
		{
			throw new RequestException('Request: Not found.' . $errMessage, $response_code);
		}
		elseif ($response_code == 403)
		{
			throw new RequestException('Request: Forbidden.' . $errMessage, $response_code);
		}
		elseif ($response_code == 401)
		{
			throw new RequestException('Request: Unauthorized.' . $errMessage, $response_code);
		}
		elseif ($response_code == 400)
		{
			throw new RequestException('Request: Invalid request.' . $errMessage, $response_code);
		}
		elseif ($response_code > 400)
		{
			throw new RequestException("RiotAPI: Unknown error occured. [CODE $response_code]" . $errMessage, $response_code);
		}

		$this->_afterCall($url, $requestHash, $ch);

		$this->query_data = array();
		$this->post_data  = null;
		$this->used_key   = self::SET_KEY;

		curl_close($ch);
	}

	/**
	 * @internal
	 *
	 *   Loads dummy response from file.
	 *
	 * @param $headers
	 * @param $response
	 * @param $response_code
	 *
	 * @throws RequestException
	 */
	public function _loadDummyData( &$headers, &$response, &$response_code )
	{
		$data = @file_get_contents($this->_getDummyDataFileName());
		$data = unserialize($data);

		if (!$data || empty($data))
			throw new RequestException("DummyData file failed to be opened.");

		$headers = $data['headers'];
		$response = $data['response'];
		$response_code = $data['code'];
	}

	/**
	 *   Saves dummy response to file.
	 *
	 * @internal
	 */
	public function _saveDummyData()
	{
		file_put_contents($this->_getDummyDataFileName(), serialize([
			'headers'  => $this->result_headers,
			'response' => $this->result_data_raw,
			'code'     => $this->result_code,
		]));
	}

	/**
	 *   Processes 'beforeCall' callbacks.
	 *
	 * @param string $url
	 * @param string $requestHash
	 *
	 * @throws RequestException
	 *
	 * @internal
	 */
	protected function _beforeCall( string $url, string $requestHash )
	{
		foreach ($this->beforeCall as $function)
		{
			if ($function($this, $url, $requestHash) === false)
			{
				throw new RequestException("Request terminated by beforeCall function.");
			}
		}
	}

	/**
	 *   Processes 'afterCall' callbacks.
	 *
	 * @param string $url
	 * @param string $requestHash
	 * @param        $curlResource
	 *
	 * @internal
	 */
	protected function _afterCall( string $url, string $requestHash, $curlResource )
	{
		foreach ($this->afterCall as $function)
		{
			$function($this, $url, $requestHash, $curlResource);
		}
	}

	/**
	 *   Builds API call URL based on current settings.
	 *
	 * @param array $curlHeaders
	 *
	 * @return string
	 *
	 * @throws GeneralException
	 * @internal
	 */
	public function _getCallUrl( &$curlHeaders = [] ): string
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
			$curlHeaders[] = self::HEADER_API_KEY . ': ' . $this->getSetting($this->used_key);
			$url_keyPart = (!empty($this->query_data) ? '?' : '');
		}

		return "https://" . $url_platformPart . $url_basePart . $this->endpoint . $url_keyPart . $url_queryPart;
	}

	/**
	 *   Returns dummy response filename based on current settings.
	 *
	 * @return string
	 *
	 * @internal
	 */
	public function _getDummyDataFileName(): string
	{
		$method = $this->used_method;
		$endp = str_replace([ '/', '.' ], [ '-', '' ], substr($this->endpoint, 1));
		$quer = str_replace([ '&', '%26', '=', '%3D' ], [ '_', '_', '-', '-' ], http_build_query($this->query_data));
		$data = !empty($this->post_data) ? '_' . md5(http_build_query($this->query_data)) : '';
		if (strlen($quer))
			$quer = "_" . $quer;

		return __DIR__ . "/../../tests/DummyData/{$method}_$endp$quer$data.json";
	}

	/**
	 *   Parses HTTP headers from raw result.
	 *
	 * @param $requestHeaders
	 *
	 * @return array
	 */
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
	 * ==================================================================d=d=
	 *     Champion Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#champion-v3
	 * ==================================================================d=d=
	 **/
	const RESOURCE_CHAMPION = '1237:champion';
	const RESOURCE_CHAMPION_V3 = 'v3';


	/**
	 *   Retrieve current champion rotations.
	 *
	 * @return Objects\ChampionInfo
	 *
	 * @throws GeneralException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 */
	public function getChampionRotations(): Objects\ChampionInfo
	{
		$this->setEndpoint("/lol/platform/" . self::RESOURCE_CHAMPION_V3 . "/champion-rotations")
			->makeCall();

		return new Objects\ChampionInfo($this->getResult(), $this);
	}

	public function getChampions( bool $only_free_to_play = false ): Objects\ChampionListDto
	{
		trigger_error("This call has been deprecated.", E_USER_DEPRECATED);
	}

	public function getChampionById( int $champion_id ): Objects\ChampionDto
	{
		trigger_error("This call has been deprecated.", E_USER_DEPRECATED);
	}

	/**
	 * ==================================================================d=d=
	 *     Champion Mastery Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#champion-mastery-v4
	 * ==================================================================d=d=
	 **/
	const RESOURCE_CHAMPIONMASTERY = '1418:champion-mastery';
	const RESOURCE_CHAMPIONMASTERY_VERSION = 'v4';

	/**
	 *   Get a champion mastery by player id and champion id. Response code 204 means
	 * there were no masteries found for given player id or player id and champion id
	 * combination. (RPC)
	 *
	 * @param string $encrypted_summoner_id
	 * @param int $champion_id
	 *
	 * @return Objects\ChampionMasteryDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#champion-mastery-v3/GET_getChampionMastery
	 */
	public function getChampionMastery( string $encrypted_summoner_id, int $champion_id ): Objects\ChampionMasteryDto
	{
		$this->setEndpoint("/lol/champion-mastery/" . self::RESOURCE_CHAMPIONMASTERY_VERSION . "/champion-masteries/by-summoner/{$encrypted_summoner_id}/by-champion/{$champion_id}")
			->setResource(self::RESOURCE_CHAMPIONMASTERY, "/champion-masteries/by-summoner/%s/by-champion/%i")
			->makeCall();

		return new Objects\ChampionMasteryDto($this->getResult(), $this);
	}

	/**
	 *   Get all champion mastery entries sorted by number of champion points descending
	 * (RPC)
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\ChampionMasteryDto[]
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#champion-mastery-v3/GET_getAllChampionMasteries
	 */
	public function getChampionMasteries( string $encrypted_summoner_id ): array
	{
		$this->setEndpoint("/lol/champion-mastery/" . self::RESOURCE_CHAMPIONMASTERY_VERSION . "/champion-masteries/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_CHAMPIONMASTERY, "/champion-masteries/by-summoner/%i")
			->makeCall();

		$r = array();
		foreach ($this->getResult() as $ident => $championMasteryDtoData)
			$r[$ident] = new Objects\ChampionMasteryDto($championMasteryDtoData, $this);

		return $r;
	}

	/**
	 *   Get a player's total champion mastery score, which is sum of individual champion
	 * mastery levels (RPC)
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return int
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#champion-mastery-v3/GET_getChampionMasteryScore
	 */
	public function getChampionMasteryScore( string $encrypted_summoner_id ): int
	{
		$this->setEndpoint("/lol/champion-mastery/" . self::RESOURCE_CHAMPIONMASTERY_VERSION . "/scores/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_CHAMPIONMASTERY, "/scores/by-summoner/%i")
			->makeCall();

		return $this->getResult();
	}


	/**
	 * ==================================================================d=d=
	 *     Spectator Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#spectator-v4
	 * ==================================================================d=d=
	 **/
	const RESOURCE_SPECTATOR = '1419:spectator';
	const RESOURCE_SPECTATOR_VERSION = 'v4';

	/**
	 *   Get current game information for the given summoner ID.
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\CurrentGameInfo
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#spectator-v3/GET_getCurrentGameInfoBySummoner
	 */
	public function getCurrentGameInfo( string $encrypted_summoner_id ): Objects\CurrentGameInfo
	{
		$this->setEndpoint("/lol/spectator/" . self::RESOURCE_SPECTATOR_VERSION . "/active-games/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_SPECTATOR, "/active-games/by-summoner/%i")
			->makeCall();

		return new Objects\CurrentGameInfo($this->getResult(), $this);
	}

	/**
	 *   Get list of featured games.
	 *
	 * @return Objects\FeaturedGames
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#spectator-v3/GET_getFeaturedGames
	 */
	public function getFeaturedGames(): Objects\FeaturedGames
	{
		$this->setEndpoint("/lol/spectator/" . self::RESOURCE_SPECTATOR_VERSION . "/featured-games")
			->setResource(self::RESOURCE_SPECTATOR, "/featured-games")
			->makeCall();

		return new Objects\FeaturedGames($this->getResult(), $this);
	}


	/**
	 * ==================================================================d=d=
	 *     League Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#league-v4
	 * ==================================================================d=d=
	 **/
	const RESOURCE_LEAGUE = '1424:league';
	const RESOURCE_LEAGUE_VERSION = 'v4';

	/**
	 *   Get league by its UUID.
	 *
	 * @param string $league_id
	 *
	 * @return Objects\LeagueListDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#league-v4/GET_getLeagueById
	 */
	public function getLeagueById( string $league_id ): Objects\LeagueListDto
	{
		$this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/leagues/{$league_id}")
			->setResource(self::RESOURCE_LEAGUE, "/leagues/%s")
			->makeCall();

		return new Objects\LeagueListDto($this->getResult(), $this);
	}

	/**
	 *   Get leagues mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\LeaguePositionDto[]
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#league-v4/GET_getAllLeaguePositionsForSummoner
	 */
	public function getLeaguePositionsForSummoner( string $encrypted_summoner_id ): array
	{
		$this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/positions/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_LEAGUE, "/positions/by-summoner/%i")
			->makeCall();

		$r = [];
		foreach ($this->getResult() as $leagueListDtoData)
			$r[] = new Objects\LeaguePositionDto($leagueListDtoData, $this);

		return $r;
	}

	/**
	 *   Get challenger tier leagues.
	 *
	 * @param string $game_queue_type
	 *
	 * @return Objects\LeagueListDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#league-v4/GET_getChallengerLeague
	 */
	public function getLeagueChallenger( string $game_queue_type ): Objects\LeagueListDto
	{
		$this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/challengerleagues/by-queue/{$game_queue_type}")
			->setResource(self::RESOURCE_LEAGUE, "/challengerleagues/by-queue/%s")
			->makeCall();

		return new Objects\LeagueListDto($this->getResult(), $this);
	}

	/**
	 *   Get grandmaster tier leagues.
	 *
	 * @param string $game_queue_type
	 *
	 * @return Objects\LeagueListDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#league-v4/GET_getMasterLeague
	 */
	public function getLeagueGrandmaster( string $game_queue_type ): Objects\LeagueListDto
	{
		$this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/grandmasterleagues/by-queue/{$game_queue_type}")
			->setResource(self::RESOURCE_LEAGUE, "/grandmasterleagues/by-queue/%s")
			->makeCall();

		return new Objects\LeagueListDto($this->getResult(), $this);
	}

	/**
	 *   Get master tier leagues.
	 *
	 * @param string $game_queue_type
	 *
	 * @return Objects\LeagueListDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#league-v4/GET_getMasterLeague
	 */
	public function getLeagueMaster( string $game_queue_type ): Objects\LeagueListDto
	{
		$this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/masterleagues/by-queue/{$game_queue_type}")
			->setResource(self::RESOURCE_LEAGUE, "/masterleagues/by-queue/%s")
			->makeCall();

		return new Objects\LeagueListDto($this->getResult(), $this);
	}


	/**
	 * ==================================================================d=d=
	 *     Static Data Endpoint Methods
	 * ==================================================================d=d=
	 **/
	const RESOURCE_STATICDATA = '1351:lol-static-data';
	const RESOURCE_STATICDATA_V3 = 'v3';

	/**
	 *   Retrieves champion list.
	 *
	 * @param bool $data_by_key
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticChampionListDto
	 *
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticChampions( bool $data_by_key = null, string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticChampionListDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			if ($data_by_key)
				$result = DataDragonAPI::getStaticChampionsWithKeys($locale, $version);
			else
				$result = DataDragonAPI::getStaticChampions($locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Create missing data
			$result['keys'] = array_map(function($d) use ($data_by_key) {
				return $data_by_key
					? $d['id']
					: $d['key'];
			}, $result['data']);
			$result['keys'] = array_flip($result['keys']);

			// Parse array and create instances
			return new StaticData\StaticChampionListDto($result, $this);
		}
	}

	/**
	 *   Retrieves a champion by its numeric key.
	 *
	 * @param int    $champion_id
	 * @param bool   $extended
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticChampionDto
	 * @throws RequestException
	 * @throws SettingsException
	 * @throws ServerException
	 */
	public function getStaticChampion( int $champion_id, bool $extended = false, string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticChampionDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticChampionByKey($champion_id, $locale, $version);
			if ($extended && $result)
				$result = @DataDragonAPI::getStaticChampionDetails($result['id'], $locale, $version)['data'][$result['id']];
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticChampionDto($result, $this);
		}
	}

	/**
	 *   Retrieves item list.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticItemListDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticItems( string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticItemListDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticItems($locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Create missing data
			array_walk($result['data'], function (&$d, $k) {
				$d['id'] = $k;
			});

			// Parse array and create instances
			return new StaticData\StaticItemListDto($result, $this);
		}
	}

	/**
	 *   Retrieves item by its unique ID.
	 *
	 * @param int $item_id
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticItemDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticItem( int $item_id, string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticItemDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticItem($item_id, $locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Create missing data
			$result['id'] = $item_id;

			// Parse array and create instances
			return new StaticData\StaticItemDto($result, $this);
		}
	}

	/**
	 *   Retrieve language strings data.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticLanguageStringsDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticLanguageStrings( string $locale = 'en_US', string $version = null ): StaticData\StaticLanguageStringsDto
	{
		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticLanguageStrings($locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticLanguageStringsDto($result, $this);
		}
	}

	/**
	 *   Retrieve supported languages data.
	 *
	 * @return array
	 * @throws RequestException
	 * @throws ServerException
	 */
	public function getStaticLanguages(): array
	{
		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticLanguages();
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			return $result;
		}
	}

	/**
	 *   Retrieve map data.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticMapDataDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticMaps( string $locale = 'en_US', string $version = null ): StaticData\StaticMapDataDto
	{
		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticMaps($locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticMapDataDto($result, $this);
		}
	}

	/**
	 *   Retrieves mastery list.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticMasteryListDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticMasteries( string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticMasteryListDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticMasteries($locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticMasteryListDto($result, $this);
		}
	}

	/**
	 *   Retrieves mastery by its unique ID.
	 *
	 * @param int    $mastery_id
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticMasteryDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticMastery( int $mastery_id, string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticMasteryDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticMastery($mastery_id, $locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticMasteryDto($result, $this);
		}
	}

	/**
	 *   Retrieve profile icon list.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticProfileIconDataDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticProfileIcons( string $locale = 'en_US', string $version = null ): StaticData\StaticProfileIconDataDto
	{
		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticProfileIcons($locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticProfileIconDataDto($result, $this);
		}
	}

	/**
	 *   Retrieve realm data. (Region versions)
	 *
	 * @return StaticData\StaticRealmDto
	 * @throws RequestException
	 * @throws ServerException
	 */
    public function getStaticRealm(): StaticData\StaticRealmDto
    {
	    $result = false;
	    try
	    {
		    // Fetch StaticData from JSON files
		    $result = DataDragonAPI::getStaticRealms($this->getSetting(self::SET_REGION));
	    }
	    catch (DataDragonException\ArgumentException $ex)
	    {
		    throw new RequestException($ex->getMessage(), $ex->getCode());
	    }
	    finally
	    {
		    if (!$result) throw new ServerException("StaticData failed to be loaded.");

		    // Parse array and create instances
		    return new StaticData\StaticRealmDto($result, $this);
	    }
    }

	/**
	 *   Retrieve reforged rune path.
	 *
	 * @param string $locale
	 * @param string|null $version
	 *
	 * @return StaticData\StaticReforgedRunePathDto[]
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
    public function getStaticReforgedRunePaths( string $locale = 'en_US', string $version = null ): array
    {
	    $result = false;
	    try
	    {
		    // Fetch StaticData from JSON files
		    $result = DataDragonAPI::getStaticReforgedRunes($locale, $version);
	    }
	    catch (DataDragonException\SettingsException $ex)
	    {
		    throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
	    }
	    catch (DataDragonException\ArgumentException $ex)
	    {
		    throw new RequestException($ex->getMessage(), $ex->getCode());
	    }
	    finally
	    {
		    if (!$result) throw new ServerException("StaticData failed to be loaded.");

		    $r = [];
		    foreach ($result as $item)
		    {
			    $rune = new StaticData\StaticReforgedRunePathDto($item, $this);
			    $r[$rune->id] = $rune;
		    }

		    return $r;
	    }
    }

	/**
	 *   Retrieve reforged rune path.
	 *
	 * @param string $locale
	 * @param string|null $version
	 *
	 * @return StaticData\StaticReforgedRuneDto[]
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
    public function getStaticReforgedRunes( string $locale = 'en_US', string $version = null ): array
    {
	    $result = false;
	    try
	    {
		    // Fetch StaticData from JSON files
		    $result = DataDragonAPI::getStaticReforgedRunes($locale, $version);
	    }
	    catch (DataDragonException\SettingsException $ex)
	    {
		    throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
	    }
	    catch (DataDragonException\ArgumentException $ex)
	    {
		    throw new RequestException($ex->getMessage(), $ex->getCode());
	    }
	    finally
	    {
		    if (!$result) throw new ServerException("StaticData failed to be loaded.");

		    $r = [];
		    foreach ($result as $path)
		    {
		    	foreach ($path['slots'] as $slot)
			    {
			    	foreach ($slot['runes'] as $item)
				    {
					    $rune = new StaticData\StaticReforgedRuneDto($item, $this);
					    $r[$rune->id] = $rune;
				    }
			    }
		    }

		    return $r;
	    }
    }

	/**
	 *   Retrieves rune list.
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticRuneListDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticRunes( string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticRuneListDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticRunes($locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticRuneListDto($result, $this);
		}
	}

	/**
	 *   Retrieves rune by its unique ID.
	 *
	 * @param int    $rune_id
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticRuneDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticRune( int $rune_id, string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticRuneDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticRune($rune_id, $locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticRuneDto($result, $this);
		}
	}

	/**
	 *   Retrieves summoner spell list.
	 *
	 * @param bool   $data_by_key
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticSummonerSpellListDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticSummonerSpells( bool $data_by_key = false, string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticSummonerSpellListDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			if ($data_by_key)
				$result = DataDragonAPI::getStaticSummonerSpellsWithKeys($locale, $version);
			else
				$result = DataDragonAPI::getStaticSummonerSpells($locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticSummonerSpellListDto($result, $this);
		}
	}

	/**
	 *   Retrieves summoner spell by its unique numeric key.
	 *
	 * @param int    $summoner_spell_key
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticSummonerSpellDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticSummonerSpell( int $summoner_spell_key, string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticSummonerSpellDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticSummonerSpellByKey($summoner_spell_key, $locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticSummonerSpellDto($result, $this);
		}
	}

	/**
	 *   Retrieves summoner spell by its unique string identifier.
	 *
	 * @param string $summoner_spell_id
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticSummonerSpellDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticSummonerSpellById( string $summoner_spell_id, string $locale = 'en_US', string $version = null, $tags = null ): StaticData\StaticSummonerSpellDto
	{
		if ($tags) trigger_error("Parameter 'tags' is no longer supported.", E_USER_DEPRECATED);

		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticSummonerSpellById($summoner_spell_id, $locale, $version);
		}
		catch (DataDragonException\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Parse array and create instances
			return new StaticData\StaticSummonerSpellDto($result, $this);
		}
	}

	/**
	 *   Retrieve version data.
	 *
	 * @return array
	 * @throws RequestException
	 * @throws ServerException
	 */
	public function getStaticVersions(): array
	{
		$result = false;
		try
		{
			// Fetch StaticData from JSON files
			$result = DataDragonAPI::getStaticVersions();
		}
		catch (DataDragonException\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}
		finally
		{
			if (!$result) throw new ServerException("StaticData failed to be loaded.");

			// Return data
			return $result;
		}
	}


	/**
	 * ==================================================================d=d=
	 *     Status Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#lol-status-v3
	 * ==================================================================d=d=
	 **/
	const RESOURCE_STATUS = '1246:lol-status';
	const RESOURCE_STATUS_VERSION = 'v3';

	/**
	 *   Get status data - shard list.
	 *
	 * @param string|null $override_region
	 *
	 * @return Objects\ShardStatus
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#lol-status-v3/GET_getShardData
	 */
	public function getStatusData( string $override_region = null ): Objects\ShardStatus
	{
		$this->setEndpoint("/lol/status/" . self::RESOURCE_STATUS_VERSION . "/shard-data")
			->setResource(self::RESOURCE_STATICDATA, "/shard-data")
			->makeCall($override_region);

		return new Objects\ShardStatus($this->getResult(), $this);
	}


	/**
	 * ==================================================================d=d=
	 *     Match Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#match-v4
	 * ==================================================================d=d=
	 **/
	const RESOURCE_MATCH = '1420:match';
	const RESOURCE_MATCH_VERSION = 'v4';

	/**
	 *   Retrieve match by match ID.
	 *
	 * @param int $match_id
	 *
	 * @return Objects\MatchDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#match-v4/GET_getMatch
	 */
	public function getMatch( $match_id ): Objects\MatchDto
	{
		$this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/matches/{$match_id}")
			->setResource(self::RESOURCE_MATCH, "/matches/%i")
			->makeCall();

		return new Objects\MatchDto($this->getResult(), $this);
	}

	/**
	 *   Retrieve match by match ID and tournament code.
	 *
	 * @param int    $match_id
	 * @param string $tournament_code
	 *
	 * @return Objects\MatchDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#match-v4/GET_getMatchByTournamentCode
	 */
	public function getMatchByTournamentCode( $match_id, string $tournament_code ): Objects\MatchDto
	{
		$this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/matches/{$match_id}/by-tournament-code/{$tournament_code}")
			->setResource(self::RESOURCE_MATCH, "/matches/%i/by-tournament-code/%s")
			->makeCall();

		return new Objects\MatchDto($this->getResult(), $this);
	}

	/**
	 *   Retrieve list of match IDs by tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return array
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#match-v4/GET_getMatchIdsByTournamentCode
	 */
	public function getMatchIdsByTournamentCode( string $tournament_code ): array
	{
		$this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/matches/by-tournament-code/{$tournament_code}/ids")
			->setResource(self::RESOURCE_MATCH, "/matches/by-tournament-code/%s/ids")
			->makeCall();

		return $this->getResult();
	}

	/**
	 *   Retrieve matchlist by account ID.
	 *
	 * @param string $encrypted_account_id
	 * @param int|array $queue
	 * @param int|array $season
	 * @param int|array $champion
	 * @param int $beginTime
	 * @param int $endTime
	 * @param int $beginIndex
	 * @param int $endIndex
	 *
	 * @return Objects\MatchlistDto
	 *
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 *
	 * @link https://developer.riotgames.com/api-methods/#match-v4/GET_getMatchlist
	 */
	public function getMatchlistByAccount( string $encrypted_account_id, $queue = null, $season = null, $champion = null, int $beginTime = null, int $endTime = null, int $beginIndex = null, int $endIndex = null ): Objects\MatchlistDto
	{
		$this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/matchlists/by-account/{$encrypted_account_id}")
			->setResource(self::RESOURCE_MATCH, "/matchlists/by-account/%i")
			->addQuery('queue', $queue)
			->addQuery('season', $season)
			->addQuery('champion', $champion)
			->addQuery('beginTime', $beginTime)
			->addQuery('endTime', $endTime)
			->addQuery('beginIndex', $beginIndex)
			->addQuery('endIndex', $endIndex)
			->makeCall();

		return new Objects\MatchlistDto($this->getResult(), $this);
	}

	/**
	 *   Retrieve matchlsit by account ID.
	 *
	 * @param int $match_id
	 *
	 * @return Objects\MatchTimelineDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#match-v4/GET_getMatchTimeline
	 */
	public function getMatchTimeline( $match_id ): Objects\MatchTimelineDto
	{
		$this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/timelines/by-match/{$match_id}")
			->setResource(self::RESOURCE_MATCH, "/timelines/by-match/%i")
			->makeCall();

		return new Objects\MatchTimelineDto($this->getResult(), $this);
	}


	/**
	 * ==================================================================d=d=
	 *     Summoner Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#summoner-v4
	 * ==================================================================d=d=
	 **/
	const RESOURCE_SUMMONER = '1416:summoner';
	const RESOURCE_SUMMONER_VERSION = 'v4';

	/**
	 *   Get single summoner object for a given summoner ID.
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#summoner-v4/GET_getBySummonerId
	 */
	public function getSummoner( string $encrypted_summoner_id ): Objects\SummonerDto
	{
		$this->setEndpoint("/lol/summoner/" . self::RESOURCE_SUMMONER_VERSION . "/summoners/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_SUMMONER, "/summoners/%i")
			->makeCall();

		return new Objects\SummonerDto($this->getResult(), $this);
	}

	/**
	 *   Get summoner name for a given summoner name.
	 *
	 * @param string $summoner_name
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#summoner-v4/GET_getBySummonerName
	 */
	public function getSummonerByName( string $summoner_name ): Objects\SummonerDto
	{
		$summoner_name = str_replace(' ', '', $summoner_name);

		$this->setEndpoint("/lol/summoner/" . self::RESOURCE_SUMMONER_VERSION . "/summoners/by-name/{$summoner_name}")
			->setResource(self::RESOURCE_SUMMONER, "/summoners/by-name/%s")
			->makeCall();

		return new Objects\SummonerDto($this->getResult(), $this);
	}

	/**
	 *   Get single summoner object for a given summoner's account ID.
	 *
	 * @param string $encrypted_account_id
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#summoner-v4/GET_getByAccountId
	 */
	public function getSummonerByAccount( string $encrypted_account_id ): Objects\SummonerDto
	{
		$this->setEndpoint("/lol/summoner/" . self::RESOURCE_SUMMONER_VERSION . "/summoners/by-account/{$encrypted_account_id}")
			->setResource(self::RESOURCE_SUMMONER, "/summoners/by-account/%i")
			->makeCall();

		return new Objects\SummonerDto($this->getResult(), $this);
	}


	/**
	 * ==================================================================d=d=
	 *     Third Party Code Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#third-party-code-v4
	 * ==================================================================d=d=
	 **/
	const RESOURCE_THIRD_PARTY_CODE = '1426:third-party-code';
	const RESOURCE_THIRD_PARTY_CODE_VERSION = 'v4';

	/**
	 *   Get third party code for given summoner ID.
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return string
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#third-party-code-v4/GET_getThirdPartyCodeBySummonerId
	 */
	public function getThirdPartyCodeBySummonerId( string $encrypted_summoner_id ): string
	{
		$this->setEndpoint("/lol/platform/" . self::RESOURCE_THIRD_PARTY_CODE_VERSION . "/third-party-code/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_THIRD_PARTY_CODE, "/third-party-code/by-summoner/%i")
			->makeCall();

		return $this->getResult();
	}


	/**
	 * ==================================================================d=d=
	 *     Tournament Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#tournament-v4
	 * ==================================================================d=d=
	 **/
	const RESOURCE_TOURNAMENT = '1436:tournament';
	const RESOURCE_TOURNAMENT_VERSION = 'v4';

	/**
	 *   Creates set of tournament codes for given tournament.
	 *
	 * @param int                      $tournament_id
	 * @param int                      $count
	 * @param TournamentCodeParameters $parameters
	 *
	 * @return array
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-v4/POST_createTournamentCode
	 */
	public function createTournamentCodes( int $tournament_id, int $count, TournamentCodeParameters $parameters ): array
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->createTournamentCodes_STUB($tournament_id, $count, $parameters);

		if ($parameters->teamSize <= 0)
			throw new RequestParameterException('Team size (teamSize) must be greater than or equal to 1.');

		if ($parameters->teamSize >= 6)
			throw new RequestParameterException('Team size (teamSize) must be less than or equal to 5.');

		if (empty($parameters->allowedSummonerIds))
			throw new RequestParameterException('List of participants (allowedSummonerIds) may not be empty. If you wish to allow anyone, fill it with 0, 1, 2, 3, etc.');

		if ($parameters->teamSize * 2 > count($parameters->allowedSummonerIds))
			throw new RequestParameterException('Not enough players to fill teams (more participants required).');

		if (in_array($parameters->pickType, self::TOURNAMENT_ALLOWED_PICK_TYPES, true) == false)
			throw new RequestParameterException('Value of pick type (pickType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_PICK_TYPES));

		if (in_array($parameters->mapType, self::TOURNAMENT_ALLOWED_MAPS, true) == false)
			throw new RequestParameterException('Value of map type (mapType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_MAPS));

		if (in_array($parameters->spectatorType, self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES, true) == false)
			throw new RequestParameterException('Value of spectator type (spectatorType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES));

		$data = json_encode($parameters);

		$this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/codes")
			->setResource(self::RESOURCE_TOURNAMENT, "/codes")
			->addQuery('tournamentId', $tournament_id)
			->addQuery('count', $count)
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Updates tournament code's settings.
	 *
	 * @param string                                 $tournament_code
	 * @param Objects\TournamentCodeUpdateParameters $parameters
	 *
	 * @return Objects\LobbyEventDtoWrapper
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-v4/PUT_updateCode
	 */
	public function editTournamentCode( string $tournament_code, TournamentCodeUpdateParameters $parameters )
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

		$this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/codes/{$tournament_code}")
			->setResource(self::RESOURCE_TOURNAMENT, "/codes/%s")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_PUT);

		return $this->getResult();
	}

	/**
	 *   Retrieves tournament code settings for given tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\TournamentCodeDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-v4/GET_getTournamentCode
	 */
	public function getTournamentCodeData( string $tournament_code ): Objects\TournamentCodeDto
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			throw new RequestException('This endpoint is not available in interim mode.');

		$this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/codes/{$tournament_code}")
			->setResource(self::RESOURCE_TOURNAMENT, "/codes/%s")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS);

		return new Objects\TournamentCodeDto($this->getResult(), $this);
	}

	/**
	 *   Creates a tournament provider and returns its ID.
	 *
	 * @param ProviderRegistrationParameters $parameters
	 *
	 * @return int
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-v4/POST_registerProviderData
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

		$this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/providers")
			->setResource(self::RESOURCE_TOURNAMENT, "/providers")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Creates a tournament and returns its ID.
	 *
	 * @param TournamentRegistrationParameters $parameters
	 *
	 * @return int
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-v4/POST_registerTournament
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

		$this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/tournaments")
			->setResource(self::RESOURCE_TOURNAMENT, "/tournaments")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Gets a list of lobby events by tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\LobbyEventDtoWrapper
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-v4/GET_getLobbyEventsByCode
	 */
	public function getTournamentLobbyEvents( string $tournament_code ): Objects\LobbyEventDtoWrapper
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->getTournamentLobbyEvents_STUB($tournament_code);

		$this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/lobby-events/by-code/{$tournament_code}")
			->setResource(self::RESOURCE_TOURNAMENT, "/lobby-events/by-code/%s")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS);

		return new Objects\LobbyEventDtoWrapper($this->getResult(), $this);
	}


	/**
	 * ==================================================================d=d=
	 *     Tournament Stub Endpoint Methods
	 *     @link https://developer.riotgames.com/api-methods/#tournament-stub-v4
	 * ==================================================================d=d=
	 **/
	const RESOURCE_TOURNAMENT_STUB = '1435:tournament-stub';
	const RESOURCE_TOURNAMENT_STUB_VERSION = 'v4';

	/**
	 *   Create a mock tournament code for the given tournament.
	 *
	 * @param int $tournament_id
	 * @param int $count
	 * @param TournamentCodeParameters $parameters
	 *
	 * @return array
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v4/POST_createTournamentCode
	 *
	 * @internal
	 */
	public function createTournamentCodes_STUB( int $tournament_id, int $count, TournamentCodeParameters $parameters ): array
	{
		if ($parameters->teamSize <= 0)
			throw new RequestParameterException('Team size (teamSize) must be greater than or equal to 1.');

		if ($parameters->teamSize >= 6)
			throw new RequestParameterException('Team size (teamSize) must be less than or equal to 5.');

		if (empty($parameters->allowedSummonerIds))
			throw new RequestParameterException('List of participants (allowedSummonerIds) may not be empty. If you wish to allow anyone, fill it with 0, 1, 2, 3, etc.');

		if ($parameters->teamSize * 2 > count($parameters->allowedSummonerIds))
			throw new RequestParameterException('Not enough players to fill teams (more participants required).');

		if (in_array($parameters->pickType, self::TOURNAMENT_ALLOWED_PICK_TYPES, true) == false)
			throw new RequestParameterException('Value of pick type (pickType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_PICK_TYPES));

		if (in_array($parameters->mapType, self::TOURNAMENT_ALLOWED_MAPS, true) == false)
			throw new RequestParameterException('Value of map type (mapType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_MAPS));

		if (in_array($parameters->spectatorType, self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES, true) == false)
			throw new RequestParameterException('Value of spectator type (spectatorType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES));

		$data = json_encode($parameters);

		$this->setEndpoint("/lol/tournament-stub/" . self::RESOURCE_TOURNAMENT_STUB_VERSION . "/codes")
			->setResource(self::RESOURCE_TOURNAMENT, "/codes")
			->addQuery('tournamentId', $tournament_id)
			->addQuery('count', $count)
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Creates a mock tournament provider and returns its ID.
	 *
	 * @param ProviderRegistrationParameters $parameters
	 *
	 * @return int
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v4/POST_registerProviderData
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

		$this->setEndpoint("/lol/tournament-stub/" . self::RESOURCE_TOURNAMENT_STUB_VERSION . "/providers")
			->setResource(self::RESOURCE_TOURNAMENT_STUB, "/providers")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Creates a mock tournament and returns its ID.
	 *
	 * @param TournamentRegistrationParameters $parameters
	 *
	 * @return int
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v4/POST_registerTournament
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

		$this->setEndpoint("/lol/tournament-stub/" . self::RESOURCE_TOURNAMENT_STUB_VERSION . "/tournaments")
			->setResource(self::RESOURCE_TOURNAMENT_STUB, "/tournaments")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->getResult();
	}

	/**
	 *   Gets a mock list of lobby events by tournament code.
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\LobbyEventDtoWrapper
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/api-methods/#tournament-stub-v4/GET_getLobbyEventsByCode
	 *
	 * @internal
	 */
	public function getTournamentLobbyEvents_STUB( string $tournament_code ): Objects\LobbyEventDtoWrapper
	{
		$this->setEndpoint("/lol/tournament-stub/" . self::RESOURCE_TOURNAMENT_STUB_VERSION . "/lobby-events/by-code/{$tournament_code}")
			->setResource(self::RESOURCE_TOURNAMENT_STUB, "/lobby-events/by-code/%s")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS);

		return new Objects\LobbyEventDtoWrapper($this->getResult(), $this);
	}


	/**
	 * ==================================================================d=d=
	 *     Fake Endpoint for testing purposes
	 * ==================================================================d=d=
	 **/

	/**
	 * @param             $specs
	 * @param string|null $region
	 * @param string|null $method
	 *
	 * @return mixed
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 *
	 * @internal
	 */
	public function makeTestEndpointCall( $specs, string $region = null, string $method = null )
	{
		$this->setEndpoint("/lol/test-endpoint/v0/" . $specs)
			->makeCall($region ?: null, $method ?: self::METHOD_GET);

		return $this->getResult();
	}
}
