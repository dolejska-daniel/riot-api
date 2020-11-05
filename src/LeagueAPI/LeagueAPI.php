<?php

/**
 * Copyright (C) 2016-2020  Daniel Dolejška
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

namespace RiotAPI\LeagueAPI;

use RiotAPI\LeagueAPI\Definitions\AsyncRequest;
use RiotAPI\LeagueAPI\Definitions\CallCacheControl;
use RiotAPI\LeagueAPI\Definitions\ICallCacheControl;
use RiotAPI\LeagueAPI\Definitions\IPlatform;
use RiotAPI\LeagueAPI\Definitions\Platform;
use RiotAPI\LeagueAPI\Definitions\IRegion;
use RiotAPI\LeagueAPI\Definitions\Region;
use RiotAPI\LeagueAPI\Definitions\IRateLimitControl;
use RiotAPI\LeagueAPI\Definitions\RateLimitControl;
use RiotAPI\LeagueAPI\Definitions\Cache;

use RiotAPI\LeagueAPI\Objects;
use RiotAPI\LeagueAPI\Objects\IApiObjectExtension;
use RiotAPI\LeagueAPI\Objects\StaticData;
use RiotAPI\LeagueAPI\Objects\ProviderRegistrationParameters;
use RiotAPI\LeagueAPI\Objects\TournamentCodeParameters;
use RiotAPI\LeagueAPI\Objects\TournamentCodeUpdateParameters;
use RiotAPI\LeagueAPI\Objects\TournamentRegistrationParameters;

use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\Exceptions\RequestException;
use RiotAPI\LeagueAPI\Exceptions\RequestParameterException;
use RiotAPI\LeagueAPI\Exceptions\ServerException;
use RiotAPI\LeagueAPI\Exceptions\ServerLimitException;
use RiotAPI\LeagueAPI\Exceptions\SettingsException;
use RiotAPI\LeagueAPI\Exceptions\DataNotFoundException;
use RiotAPI\LeagueAPI\Exceptions\ForbiddenException;
use RiotAPI\LeagueAPI\Exceptions\UnauthorizedException;
use RiotAPI\LeagueAPI\Exceptions\UnsupportedMediaTypeException;

use RiotAPI\DataDragonAPI\DataDragonAPI;
use RiotAPI\DataDragonAPI\Exceptions as DataDragonExceptions;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Exception as GuzzleHttpExceptions;
use function GuzzleHttp\Promise\settle;

use Nette\Utils\DateTime;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 *   Class LeagueAPI
 *
 * @package LeagueAPI
 */
class LeagueAPI
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
		SET_REGION                   = 'SET_REGION',
		SET_ORIG_REGION              = 'SET_ORIG_REGION',
		SET_PLATFORM                 = 'SET_PLATFORM',                 /** Set internally by setting region **/
		SET_VERIFY_SSL               = 'SET_VERIFY_SSL',               /** Specifies whether or not to verify SSL (verification often fails on localhost) **/
		SET_KEY                      = 'SET_KEY',                      /** API key used by default **/
		SET_TFT_KEY					 = 'SET_TFT_KEY',				   /** API TFT key used by default **/
		SET_TOURNAMENT_KEY           = 'SET_TOURNAMENT_KEY',           /** API key used when working with tournaments **/
		SET_KEY_INCLUDE_TYPE         = 'SET_KEY_INCLUDE_TYPE',         /** API key request include type (header, query) **/
		SET_INTERIM                  = 'SET_INTERIM',                  /** Used to set whether or not is your application in Interim mode (Tournament STUB endpoints) **/
		SET_CACHE_PROVIDER           = 'SET_CACHE_PROVIDER',           /** Specifies CacheProvider class name **/
		SET_CACHE_PROVIDER_PARAMS    = 'SET_CACHE_PROVIDER_PARAMS',    /** Specifies parameters passed to CacheProvider class when initializing **/
		SET_DD_CACHE_PROVIDER_PARAMS = 'SET_DD_CACHE_PROVIDER_PARAMS', /** Specifies parameters passed to DataDragonAPI CacheProvider class when initializing **/
		SET_CACHE_RATELIMIT          = 'SET_CACHE_RATELIMIT',          /** Used to set whether or not to saveCallData and check API key's rate limit **/
		SET_CACHE_CALLS              = 'SET_CACHE_CALLS',              /** Used to set whether or not to temporary saveCallData API call's results **/
		SET_CACHE_CALLS_LENGTH       = 'SET_CACHE_CALLS_LENGTH',       /** Specifies for how long are call results saved **/
		SET_EXTENSIONS               = 'SET_EXTENSIONS',               /** Specifies ApiObject's extensions **/
		SET_DATADRAGON_INIT          = 'SET_DATADRAGON_INIT',          /** Specifies whether or not should DataDragonAPI be initialized by this library **/
		SET_DATADRAGON_PARAMS        = 'SET_DATADRAGON_PARAMS',        /** Specifies parameters passed to DataDragonAPI when initialized **/
		SET_GUZZLE_CLIENT_CFG        = 'SET_GUZZLE_CLIENT_CFG',        /** Specifies configuration passed to Guzzle library client. */
		SET_GUZZLE_REQ_CFG           = 'SET_GUZZLE_REQ_CFG',           /** Specifies configuration passed to Guzzle request. */
		SET_STATICDATA_LINKING       = 'SET_STATICDATA_LINKING',
		SET_STATICDATA_LOCALE        = 'SET_STATICDATA_LOCALE',
		SET_STATICDATA_VERSION       = 'SET_STATICDATA_VERSION',
		SET_CALLBACKS_BEFORE         = 'SET_CALLBACKS_BEFORE',
		SET_CALLBACKS_AFTER          = 'SET_CALLBACKS_AFTER',
		SET_API_BASEURL              = 'SET_API_BASEURL',
		SET_USE_DUMMY_DATA           = 'SET_USE_DUMMY_DATA',
		SET_SAVE_DUMMY_DATA          = 'SET_SAVE_DUMMY_DATA',
		SET_DEBUG                    = 'SET_DEBUG';

	/**
	 * Available API key inclusion options.
	 */
	const
		KEY_AS_QUERY_PARAM = 'keyInclude:query',
		KEY_AS_HEADER      = 'keyInclude:header';

	/**
	 * Cache constants used to identify cache target.
	 */
	const
		CACHE_KEY_RLC = 'LeagueAPI.rate-limit.cache',
		CACHE_KEY_CCC = 'LeagueAPI.api-calls.cache';

	/**
	 * Available API headers.
	 */
	const
		HEADER_API_KEY                = 'X-Riot-Token',
		HEADER_RATELIMIT_TYPE         = 'X-Rate-Limit-Type',
		HEADER_METHOD_RATELIMIT       = 'X-Method-Rate-Limit',
		HEADER_METHOD_RATELIMIT_COUNT = 'X-Method-Rate-Limit-Count',
		HEADER_APP_RATELIMIT          = 'X-App-Rate-Limit',
		HEADER_APP_RATELIMIT_COUNT    = 'X-App-Rate-Limit-Count',
		HEADER_DEPRECATION            = 'X-Riot-Deprecated';

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
			self::SET_TFT_KEY,
			self::SET_REGION,
			self::SET_VERIFY_SSL,
			self::SET_KEY_INCLUDE_TYPE,
			self::SET_TOURNAMENT_KEY,
			self::SET_INTERIM,
			self::SET_CACHE_PROVIDER,
			self::SET_CACHE_PROVIDER_PARAMS,
			self::SET_DD_CACHE_PROVIDER_PARAMS,
			self::SET_CACHE_RATELIMIT,
			self::SET_CACHE_CALLS,
			self::SET_CACHE_CALLS_LENGTH,
			self::SET_USE_DUMMY_DATA,
			self::SET_SAVE_DUMMY_DATA,
			self::SET_EXTENSIONS,
			self::SET_DATADRAGON_INIT,
			self::SET_DATADRAGON_PARAMS,
			self::SET_GUZZLE_CLIENT_CFG,
			self::SET_GUZZLE_REQ_CFG,
			self::SET_STATICDATA_LINKING,
			self::SET_STATICDATA_LOCALE,
			self::SET_STATICDATA_VERSION,
			self::SET_CALLBACKS_BEFORE,
			self::SET_CALLBACKS_AFTER,
			self::SET_API_BASEURL,
			self::SET_DEBUG,
		],
		SETTINGS_INIT_ONLY = [
			self::SET_API_BASEURL,
			self::SET_DATADRAGON_INIT,
			self::SET_DATADRAGON_PARAMS,
			self::SET_CACHE_PROVIDER,
			self::SET_CACHE_PROVIDER_PARAMS,
			self::SET_DD_CACHE_PROVIDER_PARAMS,
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
		self::RESOURCE_LEAGUE_EXP,
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
		self::SET_API_BASEURL       => '.api.riotgames.com',
		self::SET_KEY_INCLUDE_TYPE  => self::KEY_AS_HEADER,
		self::SET_USE_DUMMY_DATA    => false,
		self::SET_SAVE_DUMMY_DATA   => false,
		self::SET_VERIFY_SSL        => true,
		self::SET_DEBUG             => false,
		self::SET_GUZZLE_CLIENT_CFG => [],
		self::SET_GUZZLE_REQ_CFG    => [],
	);

	/** @var IRegion $regions */
	public $regions;

	/** @var IPlatform $platforms */
	public $platforms;


	/** @var CacheItemPoolInterface $cache */
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


	/** @var Client $guzzle */
	protected $guzzle;

	/** @var AsyncRequest $next_async_request */
	protected $next_async_request;

	/** @var AsyncRequest[] $async_requests */
	protected $async_requests = [];

	/** @var Client[] $async_clients */
	protected $async_clients = [];


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
	 *   LeagueAPI constructor.
	 *
	 * @param array $settings
	 * @param IRegion $custom_regionDataProvider
	 * @param IPlatform $custom_platformDataProvider
	 *
	 * @throws SettingsException
	 * @throws GeneralException
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

		$this->guzzle = new Client($this->getSetting(self::SET_GUZZLE_CLIENT_CFG));

		$this->_setupDefaultCacheProviderSettings();

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

		if ($this->getSetting(self::SET_DATADRAGON_INIT))
		{
			DataDragonAPI::initByApi($this, $this->getSetting(self::SET_DATADRAGON_PARAMS, []));
			$dd_cache = $this->_initializeCacheProvider(
				$this->getSetting(self::SET_CACHE_PROVIDER),
				$this->getSetting(self::SET_DD_CACHE_PROVIDER_PARAMS, [])
			);
			DataDragonAPI::setCacheInterface($dd_cache);
		}
	}

	protected function _setupDefaultCacheProviderSettings()
	{
		//  If something should be cached
		if (!$this->isSettingSet(self::SET_CACHE_PROVIDER))
		{
			$this->settings[self::SET_CACHE_PROVIDER] = FilesystemAdapter::class;
		}

		if ($this->getSetting(self::SET_CACHE_PROVIDER) === FilesystemAdapter::class)
		{
			if (!$this->isSettingSet(self::SET_CACHE_PROVIDER_PARAMS))
			{
				$this->settings[self::SET_CACHE_PROVIDER_PARAMS] =  [
					Cache::LEAGUEAPI_NAMESPACE, // namespace
					Cache::LIFETIME, // default lifetime
					Cache::getDirectoryPath() // directory
				];
			}

			if (!$this->isSettingSet(self::SET_DD_CACHE_PROVIDER_PARAMS))
			{
				$this->settings[self::SET_DD_CACHE_PROVIDER_PARAMS] =  [
					Cache::DATADRAGON_NAMESPACE, // namespace
					Cache::LIFETIME, // default lifetime
					Cache::getDirectoryPath() // directory
				];
			}
		}
	}

	/**
	 *   Initializes library cache provider.
	 *
	 * @throws SettingsException
	 */
	protected function _setupCacheProvider()
	{
		$this->cache = $this->_initializeCacheProvider(
			$this->getSetting(self::SET_CACHE_PROVIDER),
			$this->getSetting(self::SET_CACHE_PROVIDER_PARAMS, [])
		);

		//  Loads existing cache or creates new storages
		$this->loadCache();
	}

	/**
	 * @param $cacheProviderClass
	 * @param array $params
	 *
	 * @return CacheItemPoolInterface
	 *
	 * @throws SettingsException
	 */
	protected function _initializeCacheProvider( $cacheProviderClass, array $params ): CacheItemPoolInterface
	{
		try
		{
			//  Creates reflection of specified cache provider (can be user-made)
			$cacheProvider = new \ReflectionClass($cacheProviderClass);
			//  Checks if this cache provider implements required interface
			if (!$cacheProvider->implementsInterface(CacheItemPoolInterface::class))
				throw new SettingsException("Provided CacheProvider does not implement Psr\Cache\CacheItemPoolInterface (PSR-6)");

			//  and creates new instance of this cache provider
			/** @var CacheItemPoolInterface $instance */
			$instance = $cacheProvider->newInstanceArgs($params);
			return $instance;
		}
		catch (\ReflectionException $ex)
		{
			//  probably problem when instantiating the class
			throw new SettingsException("Failed to initialize CacheProvider class: {$ex->getMessage()}.", $ex->getCode(), $ex);
		}
		catch (\Throwable $ex)
		{
			//  something went wrong when initializing the class - invalid settings, etc.
			throw new SettingsException("CacheProvider class failed to be initialized: {$ex->getMessage()}.", $ex->getCode(), $ex);
		}
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
		$this->afterCall[] = function () {
			$requestHash = func_get_arg(2);
			if ($this->getSetting(self::SET_CACHE_CALLS, false) && $this->ccc != false && $this->ccc->isCallCached($requestHash) == false)
			{
				//  Get information for how long to save the data
				if ($timeInterval = @$this->getSetting(self::SET_CACHE_CALLS_LENGTH)[$this->getResource()])
					$this->ccc->saveCallData($requestHash, $this->result_data_raw, $timeInterval);
			}
		};

		//  Save result data as new DummyData if enabled and if data does not already exist
		$this->afterCall[] = function () {
			$dummyData_file = func_get_arg(3);
			if ($this->getSetting(self::SET_SAVE_DUMMY_DATA, false) && file_exists($dummyData_file) == false)
				$this->_saveDummyData($dummyData_file);
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
	 *   LeagueAPI destructor.
	 *   Saves cache files (if needed) before destroying the object.
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
			$rlc = $this->cache->getItem(self::CACHE_KEY_RLC);
			if ($rlc->isHit())
			{
				//  nothing loaded, creating new instance
				$rlc = $rlc->get();
			}
			else
			{
				//  nothing loaded, creating new instance
				$rlc = new RateLimitControl($this->regions);
			}

			$this->rlc = $rlc;
		}

		if ($this->getSetting(self::SET_CACHE_CALLS, false))
		{
			//  call cache enabled, try to load already existing object
			$ccc = $this->cache->getItem(self::CACHE_KEY_CCC);
			if ($ccc->isHit())
			{
				//  nothing loaded, creating new instance
				$ccc = $ccc->get();
			}
			else
			{
				//  nothing loaded, creating new instance
				$ccc = new CallCacheControl();
			}

			$this->ccc = $ccc;
		}
	}

	/**
	 *   Saves required cache objects.
	 *
	 * @internal
	 */
	protected function saveCache(): bool
	{
		if (!$this->cache)
			return false;

		if ($this->getSetting(self::SET_CACHE_RATELIMIT, false))
		{
			// Save RateLimitControl
			$rlc = $this->cache->getItem(self::CACHE_KEY_RLC);
			$rlc->set($this->rlc);
			$rlc->expiresAfter($this->rlc_savetime);

			$this->cache->saveDeferred($rlc);
		}

		if ($this->getSetting(self::SET_CACHE_CALLS, false))
		{
			// Save CallCacheControl
			$ccc = $this->cache->getItem(self::CACHE_KEY_CCC);
			$ccc->set($this->ccc);
			$ccc->expiresAfter($this->ccc_savetime);

			$this->cache->saveDeferred($ccc);
		}

		return $this->cache->commit();
	}

	/**
	 *   Removes all cached data.
	 *
	 * @return bool
	 */
	public function clearCache(): bool
	{
		if ($this->rlc)
			$this->rlc->clear();

		if ($this->ccc)
			$this->ccc->clear();

		return $this->cache->clear();
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
	 * @return LeagueAPI
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
	 * @return LeagueAPI
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
	 * @return LeagueAPI
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
	 * @return LeagueAPI
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
	 * @return LeagueAPI
	 * @throws SettingsException
	 * @throws GeneralException
	 */
	public function unsetTemporaryRegion(): self
	{
		if ($this->isSettingSet(self::SET_ORIG_REGION))
		{
			$region = $this->getSetting(self::SET_ORIG_REGION);
			$this->setSetting(self::SET_REGION, $region);
			$this->setSetting(self::SET_PLATFORM, $this->platforms->getPlatformName($region));
			$this->setSetting(self::SET_ORIG_REGION, null);
		}
		return $this;
	}

	/**
	 * The AMERICAS routing value serves NA, BR, LAN, LAS, and OCE.
	 * The ASIA routing value serves KR and JP.
	 * The EUROPE routing value serves EUNE, EUW, TR, and RU.
	 *
	 * @param string $platform
	 *
	 * @throws GeneralException
	 * @throws SettingsException
	 */
	public function setTemporaryContinentRegionForPlatform(string $platform)
	{
		switch (strtolower($platform))
		{
			case Platform::EUROPE_WEST:
			case Platform::EUROPE_EAST:
			case Platform::TURKEY:
			case Platform::RUSSIA:
				$this->setTemporaryRegion(Region::EUROPE);
				break;

			case Platform::NORTH_AMERICA:
			case Platform::LAMERICA_NORTH:
			case Platform::LAMERICA_SOUTH:
			case Platform::BRASIL:
			case Platform::OCEANIA:
				$this->setTemporaryRegion(Region::AMERICAS);
				break;

			case Platform::KOREA:
			case Platform::JAPAN:
				$this->setTemporaryRegion(Region::ASIA);
				break;

			default:
				throw new GeneralException("Unable to convert provided platform ID to corresponding continent region.");
		}
	}

	/**
	 *   Sets API key type for next API call.
	 *
	 * @param string $keyType
	 *
	 * @return LeagueAPI
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
	 * @return LeagueAPI
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
	 * @return LeagueAPI
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
	 * @return LeagueAPI
	 */
	protected function addQuery( string $name, $value ): self
	{
		if (!is_null($value))
		{
			$this->query_data[$name] = $value;
		}

		return $this;
	}

	/**
	 *   Sets POST/PUT data.
	 *
	 * @param string $data
	 *
	 * @return LeagueAPI
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
	 *   Returns HTTP response headers from the last call.
	 *
	 * @return array
	 */
	public function getResultHeaders()
	{
		return $this->result_headers;
	}

	/**
	 *   Returns current API request limits.
	 *
	 * @return array
	 */
	public function getCurrentLimits()
	{
		return $this->rlc->getCurrentStatus($this->getSetting($this->used_key), $this->getSetting(self::SET_REGION), $this->getResourceEndpoint());
	}

	/**
	 *   Adds next API call to given async request group. Sending needs to be
	 * initiated by calling commitAsync function.
	 *
	 * @param callable|null $onFulfilled
	 * @param callable|null $onRejected
	 * @param string        $group
	 *
	 * @return LeagueAPI
	 */
	public function nextAsync( callable $onFulfilled = null, callable $onRejected = null, string $group = "default" ): self
	{
		$client = @$this->async_clients[$group];
		if (!$client)
			$this->async_clients[$group] = $client = new Client($this->getSetting(self::SET_GUZZLE_CLIENT_CFG));

		$this->async_requests[$group][] = $this->next_async_request = new AsyncRequest($client);
		$this->next_async_request->onFulfilled = $onFulfilled;
		$this->next_async_request->onRejected = $onRejected;

		return $this;
	}

	/**
	 *   Initiates async requests from given group. Waits until completed.
	 *
	 * @param string $group
	 */
	public function commitAsync( string $group = "default" )
	{
		/** @var AsyncRequest[] $requests */
		$requests = @$this->async_requests[$group] ?: [];
		$promises = array_map(function ($r) { return $r->getPromise(); }, $requests);
		settle($promises)->wait();

		unset($this->async_clients[$group]);
		unset($this->async_requests[$group]);
	}

	/**
	 * @internal
	 *
	 * @param PromiseInterface $promise
	 * @param callable         $resultCallback
	 *
	 * @return null
	 */
	function resolveOrEnqueuePromise( PromiseInterface $promise, callable $resultCallback = null )
	{
		if ($this->next_async_request)
		{
			$promise = $promise->then(function($result) use ($resultCallback) {
				return $resultCallback ? $resultCallback($result) : null;
			});
			$this->next_async_request->setPromise($promise);
			return $this->next_async_request = null;
		}
		return $resultCallback ? $resultCallback($promise->wait()) : null;
	}

	/**
	 * @internal
	 *
	 *   Makes call to LeagueAPI.
	 *
	 * @param string|null $overrideRegion
	 * @param string $method
	 *
	 * @return PromiseInterface
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 * @throws GeneralException
	 */
	protected function makeCall( string $overrideRegion = null, string $method = self::METHOD_GET ): PromiseInterface
	{
		if ($overrideRegion)
			$this->setTemporaryRegion($overrideRegion);

		$this->used_method = $method;

		$requestHeaders = [];
		$requestPromise = null;
		$url = $this->_getCallUrl($requestHeaders);
		$requestHash = md5($url);

		$this->_beforeCall($url, $requestHash);

		if (!$requestPromise && $this->getSetting(self::SET_USE_DUMMY_DATA, false))
		{
			// DummyData are supposed to be used
			try
			{
				// try loading the data
				$this->_loadDummyData($responseHeaders, $responseBody, $responseCode);
				$this->processCallResult($responseHeaders, $responseBody, $responseCode);
				$this->_afterCall($url, $requestHash, $this->_getDummyDataFileName());
				$requestPromise = new FulfilledPromise($this->getResult());
			}
			catch (RequestException $ex)
			{
				// loading failed, check whether an actual request should be made
				if ($this->getSetting(self::SET_SAVE_DUMMY_DATA, false) == false)
					// saving is not allowed, dummydata does not exist
					throw $ex;
			}
		}

		if (!$requestPromise && $this->getSetting(self::SET_CACHE_CALLS) && $this->ccc && $this->ccc->isCallCached($requestHash))
		{
			// calls are cached and this request is saved in cache
			$this->processCallResult([], $this->ccc->loadCallData($requestHash), 200);
			$requestPromise = new FulfilledPromise($this->getResult());
		}

		if (!$requestPromise)
		{
			// calls are not cached or this request is not cached
			// perform call to Riot API
			$guzzle = $this->guzzle;
			if ($this->next_async_request)
				$guzzle = $this->next_async_request->client;

			$options = $this->getSetting(self::SET_GUZZLE_REQ_CFG);
			$options[RequestOptions::VERIFY] = $this->getSetting(self::SET_VERIFY_SSL);
			$options[RequestOptions::HEADERS] = $requestHeaders;
			if ($this->post_data)
				$options[RequestOptions::BODY] = $this->post_data;

			if ($this->isSettingSet(self::SET_DEBUG) && $this->getSetting(self::SET_DEBUG))
				$options[RequestOptions::DEBUG] = fopen('php://stderr', 'w');

			// Create HTTP request
			$requestPromise = $guzzle->requestAsync(
				$method,
				$url,
				$options
			);

			$dummyData_file = $this->_getDummyDataFileName();
			$requestPromise = $requestPromise->then(function(ResponseInterface $response) use ($url, $requestHash, $dummyData_file) {
				$this->processCallResult($response->getHeaders(), $response->getBody(), $response->getStatusCode());
				$this->_afterCall($url, $requestHash, $dummyData_file);
				return $this->getResult();
			});
		}

		// If request fails, try to process it and raise exceptions
		$requestPromise = $requestPromise->otherwise(function($ex) {
			/** @var \Exception $ex */

			if ($ex instanceof GuzzleHttpExceptions\RequestException)
			{
				$responseHeaders = [];
				$responseBody    = null;
				$responseCode    = $ex->getCode();

				if ($response = $ex->getResponse())
				{
					$responseHeaders = $response->getHeaders();
					$responseBody    = $response->getBody();
				}

				$this->processCallResult($responseHeaders, $responseBody, $responseCode);
				throw new RequestException("LeagueAPI: Request error occured - {$ex->getMessage()}", $ex->getCode(), $ex);
			}
			elseif ($ex instanceof GuzzleHttpExceptions\ServerException)
			{
				throw new ServerException("LeagueAPI: Server error occured - {$ex->getMessage()}", $ex->getCode(), $ex);
			}

			throw new RequestException("LeagueAPI: Request could not be sent - {$ex->getMessage()}", $ex->getCode(), $ex);
		});

		if ($this->next_async_request)
			return $requestPromise;

		if ($overrideRegion)
			$this->unsetTemporaryRegion();

		$this->query_data = [];
		$this->post_data  = null;

		return $requestPromise;
	}

	/**
	 * @internal
	 *
	 * @param array $response_headers
	 * @param string $response_body
	 * @param int $response_code
	 *
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 */
	protected function processCallResult( array $response_headers = null, string $response_body = null, int $response_code = 0 )
	{
		// flatten response headers array from Guzzle
		array_walk($response_headers, function ( &$value ) {
			if (is_array($value) && count($value) == 1)
				$value = $value[0];
		});

		$this->result_code     = $response_code;
		$this->result_headers  = $response_headers;
		$this->result_data_raw = $response_body;
		$this->result_data     = json_decode($response_body, true);

		if (isset($this->result_headers[self::HEADER_DEPRECATION]))
			trigger_error("Used endpoint '{$this->getResourceEndpoint()}' is being deprecated! This endpoint will stop working on " . DateTime::from($this->result_headers[self::HEADER_DEPRECATION]) . ".", E_USER_WARNING);

		$message = isset($this->result_data['status']) ? @$this->result_data['status']['message'] : "";
		switch ($response_code)
		{
			case 503:
				throw new ServerException('LeagueAPI: Service is temporarily unavailable.', $response_code);
			case 500:
				throw new ServerException('LeagueAPI: Internal server error occured.', $response_code);
			case 429:
				throw new ServerLimitException("LeagueAPI: Rate limit for this API key was exceeded. $message", $response_code);
			case 415:
				throw new UnsupportedMediaTypeException("LeagueAPI: Unsupported media type. $message", $response_code);
			case 404:
				throw new DataNotFoundException("LeagueAPI: Not Found. $message", $response_code);
			case 403:
				throw new ForbiddenException("LeagueAPI: Forbidden. $message", $response_code);
			case 401:
				throw new UnauthorizedException("LeagueAPI: Unauthorized. $message", $response_code);
			case 400:
				throw new RequestException("LeagueAPI: Request is invalid. $message", $response_code);
			default:
				if ($response_code >= 500)
					throw new ServerException("LeagueAPI: Unspecified error occured ({$response_code}). $message", $response_code);
				if ($response_code >= 400)
					throw new RequestException("LeagueAPI: Unspecified error occured ({$response_code}). $message", $response_code);
		}
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
		$data = @unserialize($data);
		if (!$data)
			throw new RequestException("No DummyData available for call. File '{$this->_getDummyDataFileName()}' failed to be parsed.");

		$headers       = $data['headers'];
		$response      = $data['response'];
		$response_code = $data['code'];
	}

	/**
	 * @internal
	 *
	 *   Saves dummy response to file.
	 *
	 * @param string|null $dummyData_file
	 */
	public function _saveDummyData( string $dummyData_file = null )
	{
		file_put_contents($dummyData_file ?: $this->_getDummyDataFileName(), serialize([
			'headers'  => $this->result_headers,
			'response' => $this->result_data_raw,
			'code'     => $this->result_code,
		]));
	}

	/**
	 * @internal
	 *
	 *   Processes 'beforeCall' callbacks.
	 *
	 * @param string $url
	 * @param string $requestHash
	 *
	 * @throws RequestException
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
	 * @internal
	 *
	 *   Processes 'afterCall' callbacks.
	 *
	 * @param string $url
	 * @param string $requestHash
	 * @param string $dummyData_file
	 */
	protected function _afterCall( string $url, string $requestHash, string $dummyData_file )
	{
		foreach ($this->afterCall as $function)
		{
			$function($this, $url, $requestHash, $dummyData_file);
		}
	}

	/**
	 * @internal
	 *
	 *   Builds API call URL based on current settings.
	 *
	 * @param array $requestHeaders
	 *
	 * @return string
	 *
	 * @throws GeneralException
	 */
	public function _getCallUrl( &$requestHeaders = [] ): string
	{
		//  TODO: move logic to Guzzle?
		$requestHeaders = [];
		//  Platform against which will call be made
		$url_platformPart = $this->platforms->getPlatformName($this->getSetting(self::SET_REGION));

		//  API base url
		$url_basePart = $this->getSetting(self::SET_API_BASEURL);

		//  Query parameters
		$url_queryPart = "";
		foreach ($this->query_data as $key => $value)
		{
			if (is_array($value))
			{
				foreach ($value as $v)
					$url_queryPart.= "&$key=$v";
			}
			else
				$url_queryPart.= "&$key=$value";
		}
		$url_queryPart = substr($url_queryPart, 1);

		//  API key
		$url_keyPart = "";
		if ($this->getSetting(self::SET_KEY_INCLUDE_TYPE) === self::KEY_AS_QUERY_PARAM)
		{
			//  API key is to be included as query parameter
			$url_keyPart = "?api_key=" . $this->getSetting($this->used_key);
			if (!empty($url_queryPart))
				$url_keyPart.= '&';
		}
		elseif ($this->getSetting(self::SET_KEY_INCLUDE_TYPE) === self::KEY_AS_HEADER)
		{
			//  API key is to be included as request header
			$requestHeaders[self::HEADER_API_KEY] = $this->getSetting($this->used_key);
			if (!empty($url_queryPart))
				$url_keyPart = '?';
		}

		return "https://" . $url_platformPart . $url_basePart . $this->endpoint . $url_keyPart . $url_queryPart;
	}

	/**
	 * @internal
	 *
	 *   Returns dummy response filename based on current settings.
	 *
	 * @return string
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
	 * ==================================================================dd=
	 *     Champion Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#champion-v3
	 * ==================================================================dd=
	 **/
	const RESOURCE_CHAMPION = '1237:champion';
	const RESOURCE_CHAMPION_VERSION = 'v3';


	/**
	 *   Retrieve current champion rotations.
	 *
	 * @cli-name get-rotations
	 * @cli-namespace champion
	 *
	 * @return Objects\ChampionInfo
	 *
	 * @throws GeneralException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 */
	public function getChampionRotations()
	{
		$resultPromise = $this->setEndpoint("/lol/platform/" . self::RESOURCE_CHAMPION_VERSION . "/champion-rotations")
			->setResource(self::RESOURCE_CHAMPION, "/champion-rotations")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\ChampionInfo($result, $this);
		});
	}

	/**
	 * ==================================================================dd=
	 *     Champion Mastery Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#champion-mastery-v4
	 * ==================================================================dd=
	 **/
	const RESOURCE_CHAMPIONMASTERY = '1418:champion-mastery';
	const RESOURCE_CHAMPIONMASTERY_VERSION = 'v4';

	/**
	 *   Get a champion mastery by player id and champion id. Response code 204 means
	 * there were no masteries found for given player id or player id and champion id
	 * combination. (RPC)
	 *
	 * @cli-name get-mastery
	 * @cli-namespace champion-mastery
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
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#champion-mastery-v4/GET_getChampionMastery
	 */
	public function getChampionMastery( string $encrypted_summoner_id, int $champion_id )
	{
		$resultPromise = $this->setEndpoint("/lol/champion-mastery/" . self::RESOURCE_CHAMPIONMASTERY_VERSION . "/champion-masteries/by-summoner/{$encrypted_summoner_id}/by-champion/{$champion_id}")
			->setResource(self::RESOURCE_CHAMPIONMASTERY, "/champion-masteries/by-summoner/%s/by-champion/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\ChampionMasteryDto($result, $this);
		});
	}

	/**
	 *   Get all champion mastery entries sorted by number of champion points descending
	 * (RPC)
	 *
	 * @cli-name get-masteries
	 * @cli-namespace champion-mastery
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\ChampionMasteryDto[]
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#champion-mastery-v4/GET_getAllChampionMasteries
	 */
	public function getChampionMasteries( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/lol/champion-mastery/" . self::RESOURCE_CHAMPIONMASTERY_VERSION . "/champion-masteries/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_CHAMPIONMASTERY, "/champion-masteries/by-summoner/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			foreach ($result as $ident => $championMasteryDtoData)
				$r[$ident] = new Objects\ChampionMasteryDto($championMasteryDtoData, $this);

			return $r ?? [];
		});
	}

	/**
	 *   Get a player's total champion mastery score, which is sum of individual champion
	 * mastery levels (RPC)
	 *
	 * @cli-name get-mastery-score
	 * @cli-namespace champion-mastery
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return int
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#champion-mastery-v4/GET_getChampionMasteryScore
	 */
	public function getChampionMasteryScore( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/lol/champion-mastery/" . self::RESOURCE_CHAMPIONMASTERY_VERSION . "/scores/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_CHAMPIONMASTERY, "/scores/by-summoner/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(int $result) {
			return $result;
		});
	}


	/**
	 * ==================================================================dd=
	 *     Spectator Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#spectator-v4
	 * ==================================================================dd=
	 **/
	const RESOURCE_SPECTATOR = '1419:spectator';
	const RESOURCE_SPECTATOR_VERSION = 'v4';

	/**
	 *   Get current game information for the given summoner ID.
	 *
	 * @cli-name get-current-game-info
	 * @cli-namespace spectator
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\CurrentGameInfo
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#spectator-v4/GET_getCurrentGameInfoBySummoner
	 */
	public function getCurrentGameInfo( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/lol/spectator/" . self::RESOURCE_SPECTATOR_VERSION . "/active-games/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_SPECTATOR, "/active-games/by-summoner/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\CurrentGameInfo($result, $this);
		});
	}

	/**
	 *   Get list of featured games.
	 *
	 * @cli-name get-featured-games
	 * @cli-namespace spectator
	 *
	 * @return Objects\FeaturedGames
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#spectator-v4/GET_getFeaturedGames
	 */
	public function getFeaturedGames()
	{
		$resultPromise = $this->setEndpoint("/lol/spectator/" . self::RESOURCE_SPECTATOR_VERSION . "/featured-games")
			->setResource(self::RESOURCE_SPECTATOR, "/featured-games")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\FeaturedGames($result, $this);
		});
	}


	/**
	 * ==================================================================dd=
	 *     League Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#league-v4
	 * ==================================================================dd=
	 **/
	const RESOURCE_LEAGUE = '1424:league';
	const RESOURCE_LEAGUE_VERSION = 'v4';

	/**
	 *   Get league by its UUID.
	 *
	 * @cli-name get-by-id
	 * @cli-namespace league
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
	 * @link https://developer.riotgames.com/apis#league-v4/GET_getLeagueById
	 */
	public function getLeagueById( string $league_id )
	{
		$resultPromise = $this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/leagues/{$league_id}")
			->setResource(self::RESOURCE_LEAGUE, "/leagues/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LeagueListDto($result, $this);
		});
	}

	/**
	 *   Get leagues mapped by summoner ID for a given list of summoner IDs.
	 *
	 * @cli-name get-positions-for-summoner
	 * @cli-namespace league
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
	 * @link https://developer.riotgames.com/apis#league-v4/GET_getAllLeaguePositionsForSummoner
	 */
	public function getLeaguePositionsForSummoner( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/entries/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_LEAGUE, "/entries/by-summoner/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			foreach ($result as $leagueListDtoData)
				$r[] = new Objects\LeaguePositionDto($leagueListDtoData, $this);

			return $r ?? [];
		});
	}

	/**
	 *   Get league entries in all queues for a given summoner ID.
	 *
	 * @cli-name get-league-entries-for-summoner
	 * @cli-namespace league
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\LeagueEntryDto[]
	 *
	 * @throws GeneralException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 *
	 * @link https://developer.riotgames.com/apis#league-v4/GET_getLeagueEntriesForSummoner
	 */
	public function getLeagueEntriesForSummoner( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/entries/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_LEAGUE, "/entries/by-summoner/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			foreach ($result as $leagueEntryDtoData)
				$r[] = new Objects\LeagueEntryDto($leagueEntryDtoData, $this);

			return $r ?? [];
		});
	}

	/**
	 *   Get all the league entries.
	 *
	 * @cli-name get-league-entries
	 * @cli-namespace league
	 *
	 * @param string $queue
	 * @param string $tier
	 * @param string $division
	 * @param int $page
	 *
	 * @return Objects\LeagueEntryDto[]
	 *
	 * @throws GeneralException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 * @link https://developer.riotgames.com/apis#league-v4/GET_getLeagueEntries
	 */
	public function getLeagueEntries( string $queue, string $tier, string $division, int $page = 1 )
	{
		$resultPromise = $this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/entries/{$queue}/{$tier}/{$division}")
			->setResource(self::RESOURCE_LEAGUE, "/entries/%s/%s/%s")
			->addQuery('page', $page)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			foreach ($result as $leagueEntryDtoData)
				$r[] = new Objects\LeagueEntryDto($leagueEntryDtoData, $this);

			return $r ?? [];
		});
	}

	/**
	 *   Get challenger tier leagues.
	 *
	 * @cli-name get-league-challenger
	 * @cli-namespace league
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
	 * @link https://developer.riotgames.com/apis#league-v4/GET_getChallengerLeague
	 */
	public function getLeagueChallenger( string $game_queue_type )
	{
		$resultPromise = $this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/challengerleagues/by-queue/{$game_queue_type}")
			->setResource(self::RESOURCE_LEAGUE, "/challengerleagues/by-queue/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LeagueListDto($result, $this);
		});
	}

	/**
	 *   Get grandmaster tier leagues.
	 *
	 * @cli-name get-league-grandmaster
	 * @cli-namespace league
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
	 * @link https://developer.riotgames.com/apis#league-v4/GET_getMasterLeague
	 */
	public function getLeagueGrandmaster( string $game_queue_type )
	{
		$resultPromise = $this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/grandmasterleagues/by-queue/{$game_queue_type}")
			->setResource(self::RESOURCE_LEAGUE, "/grandmasterleagues/by-queue/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LeagueListDto($result, $this);
		});
	}

	/**
	 *   Get master tier leagues.
	 *
	 * @cli-name get-league-master
	 * @cli-namespace league
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
	 * @link https://developer.riotgames.com/apis#league-v4/GET_getMasterLeague
	 */
	public function getLeagueMaster( string $game_queue_type )
	{
		$resultPromise = $this->setEndpoint("/lol/league/" . self::RESOURCE_LEAGUE_VERSION . "/masterleagues/by-queue/{$game_queue_type}")
			->setResource(self::RESOURCE_LEAGUE, "/masterleagues/by-queue/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LeagueListDto($result, $this);
		});
	}


	/**
	 * ==================================================================dd=
	 *     League Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#league-exp-v4
	 * ==================================================================dd=
	 **/
	const RESOURCE_LEAGUE_EXP = '1474:league-exp';
	const RESOURCE_LEAGUE_EXP_VERSION = 'v4';

	/**
	 *   Get all the league entries.
	 *
	 * @cli-name get-league-entries
	 * @cli-namespace league-exp
	 *
	 * @param string $queue
	 * @param string $tier
	 * @param string $division
	 * @param int $page
	 *
	 * @return Objects\LeagueEntryDto[]
	 *
	 * @throws GeneralException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 *
	 * @link https://developer.riotgames.com/apis#league-exp-v4/GET_getLeagueEntries
	 */
	public function getLeagueEntriesExp( string $queue, string $tier, string $division, int $page = 1 )
	{
		$resultPromise = $this->setEndpoint("/lol/league-exp/" . self::RESOURCE_LEAGUE_EXP_VERSION . "/entries/{$queue}/{$tier}/{$division}")
			->setResource(self::RESOURCE_LEAGUE_EXP, "/entries/%s/%s/%s")
			->addQuery('page', $page)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			foreach ($result as $leagueEntryDtoData)
				$r[] = new Objects\LeagueEntryDto($leagueEntryDtoData, $this);

			return $r ?? [];
		});
	}


	/**
	 * ==================================================================dd=
	 *     Static Data Endpoint Methods
	 * ==================================================================dd=
	 **/
	const RESOURCE_STATICDATA = '1351:lol-static-data';

	/**
	 * @param $method
	 * @param mixed ...$arguments
	 * @return bool|mixed
	 *
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	protected function _makeStaticCall($method, ...$arguments)
	{
		try
		{
			// Fetch StaticData from JSON files
			$result = call_user_func_array($method, $arguments);
			$this->result_data = $result;
			$this->result_data_raw = [];
			$this->result_headers = [];
			$this->result_code = 200;

			if (!$result)
			{
				$this->result_code = 599;
				throw new ServerException("StaticData failed to be loaded.");
			}
		}
		catch (DataDragonExceptions\SettingsException $ex)
		{
			throw new SettingsException("DataDragon API was not initialized properly! StaticData endpoints cannot be used.");
		}
		catch (DataDragonExceptions\ArgumentException $ex)
		{
			throw new RequestException($ex->getMessage(), $ex->getCode());
		}

		return $result;
	}

	/**
	 *   Retrieves champion list.
	 *
	 * @cli-name get-champions
	 * @cli-namespace static-data
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
	public function getStaticChampions( bool $data_by_key = false, string $locale = 'en_US', string $version = null ): StaticData\StaticChampionListDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticChampions", $locale, $version, $data_by_key);
		return new StaticData\StaticChampionListDto($result, $this);
	}

	/**
	 *   Retrieves a champion by its numeric key.
	 *
	 * @cli-name get-champion
	 * @cli-namespace static-data
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
	public function getStaticChampion( int $champion_id, bool $extended = false, string $locale = 'en_US', string $version = null ): StaticData\StaticChampionDto
	{
		if ($champion_id == -1)
			return new StaticData\StaticChampionDto(["id" => -1, "name" => "None"], $this);

		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticChampionByKey", $champion_id, $locale, $version);
		if ($extended && $result)
		{
			$champion_id = $result['id'];
			$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticChampionDetails", $champion_id, $locale, $version);
			$result = $result['data'][$champion_id];
			$this->result_data = $result;
		}

		return new StaticData\StaticChampionDto($result, $this);
	}

	/**
	 *   Retrieves item list.
	 *
	 * @cli-name get-items
	 * @cli-namespace static-data
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticItemListDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticItems( string $locale = 'en_US', string $version = null ): StaticData\StaticItemListDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticItems", $locale, $version);

		// Create missing data
		array_walk($result['data'], function (&$d, $k) {
			$d['id'] = $k;
		});
		$this->result_data = $result;

		return new StaticData\StaticItemListDto($result, $this);
	}

	/**
	 *   Retrieves item by its unique ID.
	 *
	 * @cli-name get-item
	 * @cli-namespace static-data
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
	public function getStaticItem( int $item_id, string $locale = 'en_US', string $version = null ): StaticData\StaticItemDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticItem", $item_id, $locale, $version);

		// Create missing data
		$result['id'] = $item_id;
		$this->result_data = $result;

		return new StaticData\StaticItemDto($result, $this);
	}

	/**
	 *   Retrieve language strings data.
	 *
	 * @cli-name get-language-strings
	 * @cli-namespace static-data
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
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticLanguageStrings", $locale, $version);
		return new StaticData\StaticLanguageStringsDto($result, $this);
	}

	/**
	 *   Retrieve supported languages data.
	 *
	 * @cli-name get-languages
	 * @cli-namespace static-data
	 *
	 * @return array
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticLanguages(): array
	{
		return $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticLanguages");
	}

	/**
	 *   Retrieve map data.
	 *
	 * @cli-name get-maps
	 * @cli-namespace static-data
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
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticMaps", $locale, $version);
		return new StaticData\StaticMapDataDto($result, $this);
	}

	/**
	 *   Retrieves mastery list.
	 *
	 * @cli-name get-masteries
	 * @cli-namespace static-data
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticMasteryListDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticMasteries( string $locale = 'en_US', string $version = null ): StaticData\StaticMasteryListDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticMasteries", $locale, $version);
		return new StaticData\StaticMasteryListDto($result, $this);
	}

	/**
	 *   Retrieves mastery by its unique ID.
	 *
	 * @cli-name get-mastery
	 * @cli-namespace static-data
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
	public function getStaticMastery( int $mastery_id, string $locale = 'en_US', string $version = null ): StaticData\StaticMasteryDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticMastery", $mastery_id, $locale, $version);
		return new StaticData\StaticMasteryDto($result, $this);
	}

	/**
	 *   Retrieve profile icon list.
	 *
	 * @cli-name get-profile-icons
	 * @cli-namespace static-data
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
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticProfileIcons", $locale, $version);
		return new StaticData\StaticProfileIconDataDto($result, $this);
	}

	/**
	 *   Retrieve realm data. (Region versions)
	 *
	 * @cli-name get-realm
	 * @cli-namespace static-data
	 *
	 * @return StaticData\StaticRealmDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
    public function getStaticRealm(): StaticData\StaticRealmDto
    {
	    $result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticRealms", $this->getSetting(self::SET_REGION));
	    return new StaticData\StaticRealmDto($result, $this);
    }

	/**
	 *   Retrieve reforged rune path.
	 *
	 * @cli-name get-reforged-rune-paths
	 * @cli-namespace static-data
	 *
	 * @param string $locale
	 * @param string|null $version
	 *
	 * @return StaticData\StaticReforgedRunePathList
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
    public function getStaticReforgedRunePaths( string $locale = 'en_US', string $version = null ): StaticData\StaticReforgedRunePathList
    {
	    $result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticReforgedRunes", $locale, $version);

	    // Create missing data
	    $r = [];
	    foreach ($result as $path)
		    $r[$path['id']] = $path;
	    $result = [ 'paths' => $r ];
	    $this->result_data = $result;

	    return new StaticData\StaticReforgedRunePathList($result, $this);
    }

	/**
	 *   Retrieve reforged rune path.
	 *
	 * @cli-name get-reforged-runes
	 * @cli-namespace static-data
	 *
	 * @param string $locale
	 * @param string|null $version
	 *
	 * @return StaticData\StaticReforgedRuneList
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
    public function getStaticReforgedRunes( string $locale = 'en_US', string $version = null ): StaticData\StaticReforgedRuneList
    {
	    $result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticReforgedRunes", $locale, $version);

	    // Create missing data
	    $r = [];
	    foreach ($result as $path)
	    {
		    foreach ($path['slots'] as $slot)
		    {
			    foreach ($slot['runes'] as $item)
			    {
				    $r[$item['id']] = $item;
			    }
		    }
	    }
	    $result = [ 'runes' => $r ];
	    $this->result_data = $result;

	    return new StaticData\StaticReforgedRuneList($result, $this);
    }

	/**
	 *   Retrieves rune list.
	 *
	 * @cli-name get-runes
	 * @cli-namespace static-data
	 *
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticRuneListDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticRunes( string $locale = 'en_US', string $version = null ): StaticData\StaticRuneListDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticRunes", $locale, $version);
		return new StaticData\StaticRuneListDto($result, $this);
	}

	/**
	 *   Retrieves rune by its unique ID.
	 *
	 * @cli-name get-rune
	 * @cli-namespace static-data
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
	public function getStaticRune( int $rune_id, string $locale = 'en_US', string $version = null ): StaticData\StaticRuneDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticRune", $rune_id, $locale, $version);
		return new StaticData\StaticRuneDto($result, $this);
	}

	/**
	 *   Retrieves summoner spell list.
	 *
	 * @cli-name get-summoner-spells
	 * @cli-namespace static-data
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
	public function getStaticSummonerSpells( bool $data_by_key = false, string $locale = 'en_US', string $version = null ): StaticData\StaticSummonerSpellListDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticSummonerSpells", $locale, $version, $data_by_key);
		return new StaticData\StaticSummonerSpellListDto($result, $this);
	}

	/**
	 *   Retrieves summoner spell by its unique numeric key.
	 *
	 * @cli-name get-summoner-spell
	 * @cli-namespace static-data
	 *
	 * @param int    $summoner_spell_id
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticSummonerSpellDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticSummonerSpell( int $summoner_spell_id, string $locale = 'en_US', string $version = null ): StaticData\StaticSummonerSpellDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticSummonerSpellById", $summoner_spell_id, $locale, $version);
		return new StaticData\StaticSummonerSpellDto($result, $this);
	}

	/**
	 *   Retrieves summoner spell by its unique string identifier.
	 *
	 * @cli-name get-summoner-spell-by-key
	 * @cli-namespace static-data
	 *
	 * @param string $summoner_spell_key
	 * @param string $locale
	 * @param string $version
	 *
	 * @return StaticData\StaticSummonerSpellDto
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticSummonerSpellByKey( string $summoner_spell_key, string $locale = 'en_US', string $version = null ): StaticData\StaticSummonerSpellDto
	{
		$result = $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticSummonerSpell", $summoner_spell_key, $locale, $version);
		return new StaticData\StaticSummonerSpellDto($result, $this);
	}

	/**
	 *   Retrieve version data.
	 *
	 * @cli-name get-versions
	 * @cli-namespace static-data
	 *
	 * @return array
	 * @throws RequestException
	 * @throws ServerException
	 * @throws SettingsException
	 */
	public function getStaticVersions(): array
	{
		return $this->_makeStaticCall("RiotAPI\\DataDragonAPI\\DataDragonAPI::getStaticVersions");
	}


	/**
	 * ==================================================================dd=
	 *     Status Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#lol-status-v3
	 * ==================================================================dd=
	 **/
	const RESOURCE_STATUS = '1246:lol-status';
	const RESOURCE_STATUS_VERSION = 'v3';

	/**
	 *   Get status data - shard list.
	 *
	 * @cli-name get
	 * @cli-namespace status
	 *
	 * @param string|null $override_region
	 *
	 * @return Objects\ShardStatus
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#lol-status-v3/GET_getShardData
	 */
	public function getStatusData( string $override_region = null )
	{
		$resultPromise = $this->setEndpoint("/lol/status/" . self::RESOURCE_STATUS_VERSION . "/shard-data")
			->setResource(self::RESOURCE_STATICDATA, "/shard-data")
			->makeCall($override_region);

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\ShardStatus($result, $this);
		});
	}


	/**
	 * ==================================================================dd=
	 *     Match Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#match-v4
	 * ==================================================================dd=
	 **/
	const RESOURCE_MATCH = '1420:match';
	const RESOURCE_MATCH_VERSION = 'v4';

	/**
	 *   Retrieve match by match ID.
	 *
	 * @cli-name get
	 * @cli-namespace match
	 *
	 * @param int $match_id
	 *
	 * @return Objects\MatchDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#match-v4/GET_getMatch
	 */
	public function getMatch( $match_id )
	{
		$resultPromise = $this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/matches/{$match_id}")
			->setResource(self::RESOURCE_MATCH, "/matches/%i")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\MatchDto($result, $this);
		});
	}

	/**
	 *   Retrieve match by match ID and tournament code.
	 *
	 * @cli-name get-by-tournament-code
	 * @cli-namespace match
	 *
	 * @param int $match_id
	 * @param string $tournament_code
	 *
	 * @return Objects\MatchDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
	 */
	public function getMatchByTournamentCode( $match_id, string $tournament_code )
	{
		$resultPromise = $this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/matches/{$match_id}/by-tournament-code/{$tournament_code}")
			->setResource(self::RESOURCE_MATCH, "/matches/%i/by-tournament-code/%s")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\MatchDto($result, $this);
		});
	}

	/**
	 *   Retrieve list of match IDs by tournament code.
	 *
	 * @cli-name get-ids-by-tournament-code
	 * @cli-namespace match
	 *
	 * @param string $tournament_code
	 *
	 * @return array
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#match-v4/GET_getMatchIdsByTournamentCode
	 */
	public function getMatchIdsByTournamentCode( string $tournament_code )
	{
		$resultPromise = $this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/matches/by-tournament-code/{$tournament_code}/ids")
			->setResource(self::RESOURCE_MATCH, "/matches/by-tournament-code/%s/ids")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return $result;
		});
	}

	/**
	 *   Retrieve matchlist by account ID.
	 *
	 * @cli-name get-matchlist-by-account
	 * @cli-namespace match
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
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#match-v4/GET_getMatchlist
	 */
	public function getMatchlistByAccount( string $encrypted_account_id, $queue = null, $season = null, $champion = null, int $beginTime = null, int $endTime = null, int $beginIndex = null, int $endIndex = null )
	{
		$resultPromise = $this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/matchlists/by-account/{$encrypted_account_id}")
			->setResource(self::RESOURCE_MATCH, "/matchlists/by-account/%s")
			->addQuery('queue', $queue)
			->addQuery('season', $season)
			->addQuery('champion', $champion)
			->addQuery('beginTime', $beginTime)
			->addQuery('endTime', $endTime)
			->addQuery('beginIndex', $beginIndex)
			->addQuery('endIndex', $endIndex)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\MatchlistDto($result, $this);
		});
	}

	/**
	 *   Retrieve matchlsit by account ID.
	 *
	 * @cli-name get-timeline
	 * @cli-namespace match
	 *
	 * @param int $match_id
	 *
	 * @return Objects\MatchTimelineDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#match-v4/GET_getMatchTimeline
	 */
	public function getMatchTimeline( $match_id )
	{
		$resultPromise = $this->setEndpoint("/lol/match/" . self::RESOURCE_MATCH_VERSION . "/timelines/by-match/{$match_id}")
			->setResource(self::RESOURCE_MATCH, "/timelines/by-match/%i")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\MatchTimelineDto($result, $this);
		});
	}


	/**
	 * ==================================================================dd=
	 *     Summoner Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#summoner-v4
	 * ==================================================================dd=
	 **/
	const RESOURCE_SUMMONER = '1416:summoner';
	const RESOURCE_SUMMONER_VERSION = 'v4';

	/**
	 *   Get single summoner object for a given summoner ID.
	 *
	 * @cli-name get
	 * @cli-namespace summoner
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#summoner-v4/GET_getBySummonerId
	 */
	public function getSummoner( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/lol/summoner/" . self::RESOURCE_SUMMONER_VERSION . "/summoners/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_SUMMONER, "/summoners/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\SummonerDto($result, $this);
		});
	}

	/**
	 *   Get summoner for a given summoner name.
	 *
	 * @cli-name get-by-name
	 * @cli-namespace summoner
	 *
	 * @param string $summoner_name
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#summoner-v4/GET_getBySummonerName
	 */
	public function getSummonerByName( string $summoner_name )
	{
		$summoner_name = str_replace(' ', '', $summoner_name);
		if (trim($summoner_name) === '') {
			throw new RequestParameterException('Provided summoner name must not be empty');
		}

		$resultPromise = $this->setEndpoint("/lol/summoner/" . self::RESOURCE_SUMMONER_VERSION . "/summoners/by-name/{$summoner_name}")
			->setResource(self::RESOURCE_SUMMONER, "/summoners/by-name/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\SummonerDto($result, $this);
		});
	}

	/**
	 *   Get single summoner object for a given summoner's account ID.
	 *
	 * @cli-name get-by-account-id
	 * @cli-namespace summoner
	 *
	 * @param string $encrypted_account_id
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#summoner-v4/GET_getByAccountId
	 */
	public function getSummonerByAccountId( string $encrypted_account_id )
	{
		$resultPromise = $this->setEndpoint("/lol/summoner/" . self::RESOURCE_SUMMONER_VERSION . "/summoners/by-account/{$encrypted_account_id}")
			->setResource(self::RESOURCE_SUMMONER, "/summoners/by-account/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\SummonerDto($result, $this);
		});
	}

	/**
	 *   Get single summoner object for a given summoner's PUUID.
	 *
	 * @cli-name get-by-puuid
	 * @cli-namespace summoner
	 *
	 * @param string $encrypted_puuid
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#summoner-v4/GET_getByPUUID
	 */
	public function getSummonerByPUUID( string $encrypted_puuid )
	{
		$resultPromise = $this->setEndpoint("/lol/summoner/" . self::RESOURCE_SUMMONER_VERSION . "/summoners/by-puuid/{$encrypted_puuid}")
			->setResource(self::RESOURCE_SUMMONER, "/summoners/by-puuid/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\SummonerDto($result, $this);
		});
	}

	/**
	 * ==================================================================dd=
	 *     TFT League Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#tft-league-v1
	 * ==================================================================dd=
	 **/
	const RESOURCE_TFT_LEAGUE = '1484:tft-league';
	const RESOURCE_TFT_LEAGUE_VERSION = 'v1';

	/**
	 *   Get TFT league entries for a given summoner ID.
	 *
	 * @cli-name get-entries-for-summoner
	 * @cli-namespace tft-league
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\LeagueEntryDto[]
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-league-v1/GET_getLeagueEntriesForSummoner
	 */
	public function getTFTLeagueEntriesForSummoner( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/tft/league/" . self::RESOURCE_TFT_LEAGUE_VERSION . "/entries/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_TFT_LEAGUE, "/entries/by-summoner/%s")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			foreach ($result as $leagueEntryDtoData)
				$r[] = new Objects\LeagueEntryDto($leagueEntryDtoData, $this);

			return $r ?? [];
		});
	}

	/**
	 *   Get TFT league with given ID, including inactive entries.
	 *
	 * @cli-name get-by-id
	 * @cli-namespace tft-league
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
	 * @link https://developer.riotgames.com/apis#tft-league-v1/GET_getLeagueById
	 */
	public function getTFTLeagueById( string $league_id )
	{
		$resultPromise = $this->setEndpoint("/tft/league/" . self::RESOURCE_TFT_LEAGUE_VERSION . "/leagues/{$league_id}")
			->setResource(self::RESOURCE_TFT_LEAGUE, "/leagues/%s")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LeagueListDto($result, $this);
		});
	}

	/**
	 *   Get all the TFT league entries.
	 *
	 * @cli-name get-league-entries
	 * @cli-namespace tft-league
	 *
	 * @param string $tier
	 * @param string $division
	 * @param int $page
	 *
	 * @return Objects\LeagueEntryDto[]
	 *
	 * @throws GeneralException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws SettingsException
	 *
	 * @link https://developer.riotgames.com/apis#league-v4/GET_getLeagueEntries
	 */
	public function getTFTLeagueEntries( string $tier, string $division, int $page = 1 )
	{
		$resultPromise = $this->setEndpoint("/tft/league/" . self::RESOURCE_TFT_LEAGUE_VERSION . "/entries/{$tier}/{$division}")
			->setResource(self::RESOURCE_LEAGUE, "/entries/%s/%s")
			->addQuery('page', $page)
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			foreach ($result as $leagueEntryDtoData)
				$r[] = new Objects\LeagueEntryDto($leagueEntryDtoData, $this);

			return $r ?? [];
		});
	}

	/**
	 *   Get the TFT challenger league.
	 *
	 * @cli-name get-challenger
	 * @cli-namespace tft-league
	 *
	 * @return Objects\LeagueListDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-league-v1/GET_getChallengerLeague
	 */
	public function getTFTChallengerLeague()
	{
		$resultPromise = $this->setEndpoint("/tft/league/" . self::RESOURCE_TFT_LEAGUE_VERSION . "/challenger")
			->setResource(self::RESOURCE_TFT_LEAGUE, "/challenger")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LeagueListDto($result, $this);
		});
	}

	/**
	 *   Get the TFT grandmaster league.
	 *
	 * @cli-name get-grandmaster
	 * @cli-namespace tft-league
	 *
	 * @return Objects\LeagueListDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-league-v1/GET_getGrandmasterLeague
	 */
	public function getTFTGrandmasterLeague()
	{
		$resultPromise = $this->setEndpoint("/tft/league/" . self::RESOURCE_TFT_LEAGUE_VERSION . "/grandmaster")
			->setResource(self::RESOURCE_TFT_LEAGUE, "/grandmaster")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LeagueListDto($result, $this);
		});
	}

	/**
	 *   Get the TFT master league.
	 *
	 * @cli-name get-master
	 * @cli-namespace tft-league
	 *
	 * @return Objects\LeagueListDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-league-v1/GET_getMasterLeague
	 */
	public function getTFTMasterLeague()
	{
		$resultPromise = $this->setEndpoint("/tft/league/" . self::RESOURCE_TFT_LEAGUE_VERSION . "/master")
			->setResource(self::RESOURCE_TFT_LEAGUE, "/master")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LeagueListDto($result, $this);
		});
	}


	/**
	 * ==================================================================dd=
	 *     TFT Match Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#tft-match-v1
	 * ==================================================================dd=
	 **/
	const RESOURCE_TFT_MATCH = '1481:tft-match';
	const RESOURCE_TFT_MATCH_VERSION = 'v1';

	/**
	 *   Retrieve match by match ID.
	 *
	 * @cli-name get
	 * @cli-namespace tft-match
	 *
	 * @param string $match_id
	 *
	 * @return Objects\MatchDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-match-v1/GET_getMatch
	 */
	public function getTFTMatch( string $match_id )
	{
		$this->setTemporaryContinentRegionForPlatform(explode("_", $match_id)[0]);
		$resultPromise = $this->setEndpoint("/tft/match/" . self::RESOURCE_TFT_MATCH_VERSION . "/matches/{$match_id}")
			->setResource(self::RESOURCE_TFT_MATCH, "/matches/%i")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		$this->unsetTemporaryRegion();
		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\MatchDto($result, $this);
		});
	}
	/**
	 * Get matchs with Puuid
	 *
	 * @cli-name get-by-puuid
	 * @cli-namespace tft-match
	 *
	 * @param string $encrypted_puuid
	 * @param int $count
	 *
	 * @return Objects\MatchDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-match-v1/GET_getMatchIdsByPUUID
	 */
	public function getTFTMatchByPuuid( string $encrypted_puuid, int $count = 20 )
	{
		$this->setTemporaryContinentRegionForPlatform($this->getSetting(self::SET_PLATFORM));
		$resultPromise = $this->setEndpoint("/tft/match/" . self::RESOURCE_TFT_MATCH_VERSION . "/matches/by-puuid/{$encrypted_puuid}/ids")
			->setResource(self::RESOURCE_TFT_MATCH, "/matches/%i")
			->addQuery('count', $count)
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		$this->unsetTemporaryRegion();
		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\MatchDto($result, $this);
		});
	}

	/**
	 * ==================================================================dd=
	 *     TFT Summoner Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#tft-summoner-v1
	 * ==================================================================dd=
	 **/
	const RESOURCE_TFT_SUMMONER = '1483:tft-summoner';
	const RESOURCE_TFT_SUMMONER_VERSION = 'v1';

	/**
	 *   Get TFT single summoner object for a given summoner ID.
	 *
	 * @cli-name get
	 * @cli-namespace tft-summoner
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-summoner-v1/GET_getBySummonerId
	 */
	public function getTFTSummoner( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/tft/lol/summoner/" . self::RESOURCE_TFT_SUMMONER_VERSION . "/summoners/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_TFT_SUMMONER, "/summoners/%s")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\SummonerDto($result, $this);
		});
	}

	/**
	 *   Get TFT summoner for a given summoner name.
	 *
	 * @cli-name get-by-name
	 * @cli-namespace tft-summoner
	 *
	 * @param string $summoner_name
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-summoner-v4/GET_getBySummonerName
	 */
	public function getTFTSummonerByName( string $summoner_name )
	{
		$summoner_name = str_replace(' ', '', $summoner_name);
		$resultPromise = $this->setEndpoint("/tft/summoner/" . self::RESOURCE_TFT_SUMMONER_VERSION . "/summoners/by-name/{$summoner_name}")
			->setResource(self::RESOURCE_TFT_SUMMONER, "/summoners/by-name/%s")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\SummonerDto($result, $this);
		});
	}

	/**
	 *   Get TFT single summoner object for a given summoner's account ID.
	 *
	 * @cli-name get-by-account-id
	 * @cli-namespace tft-summoner
	 *
	 * @param string $encrypted_account_id
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-summoner-v4/GET_getByAccountId
	 */
	public function getTFTSummonerByAccountId( string $encrypted_account_id )
	{
		$resultPromise = $this->setEndpoint("/tft/lol/summoner/" . self::RESOURCE_TFT_SUMMONER_VERSION . "/summoners/by-account/{$encrypted_account_id}")
			->setResource(self::RESOURCE_TFT_SUMMONER, "/summoners/by-account/%s")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\SummonerDto($result, $this);
		});
	}

	/**
	 *   Get TFT single summoner object for a given summoner's PUUID.
	 *
	 * @cli-name get-by-puuid
	 * @cli-namespace tft-summoner
	 *
	 * @param string $encrypted_puuid
	 *
	 * @return Objects\SummonerDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tft-summoner-v4/GET_getByPUUID
	 */
	public function getTFTSummonerByPUUID( string $encrypted_puuid )
	{
		$resultPromise = $this->setEndpoint("/tft/lol/summoner/" . self::RESOURCE_TFT_SUMMONER_VERSION . "/summoners/by-puuid/{$encrypted_puuid}")
			->setResource(self::RESOURCE_TFT_SUMMONER, "/summoners/by-puuid/%s")
			->useKey(self::SET_TFT_KEY)
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\SummonerDto($result, $this);
		});
	}


	/**
	 * ==================================================================dd=
	 *     Third Party Code Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#third-party-code-v4
	 * ==================================================================dd=
	 **/
	const RESOURCE_THIRD_PARTY_CODE = '1426:third-party-code';
	const RESOURCE_THIRD_PARTY_CODE_VERSION = 'v4';

	/**
	 *   Get third party code for given summoner ID.
	 *
	 * @cli-name get-by-summoner-id
	 * @cli-namespace third-party-code
	 *
	 * @param string $encrypted_summoner_id
	 *
	 * @return string
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#third-party-code-v4/GET_getThirdPartyCodeBySummonerId
	 */
	public function getThirdPartyCodeBySummonerId( string $encrypted_summoner_id )
	{
		$resultPromise = $this->setEndpoint("/lol/platform/" . self::RESOURCE_THIRD_PARTY_CODE_VERSION . "/third-party-code/by-summoner/{$encrypted_summoner_id}")
			->setResource(self::RESOURCE_THIRD_PARTY_CODE, "/third-party-code/by-summoner/%s")
			->makeCall();

		return $this->resolveOrEnqueuePromise($resultPromise, function(string $result) {
			return $result;
		});
	}


	/**
	 * ==================================================================dd=
	 *     Tournament Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#tournament-v4
	 * ==================================================================dd=
	 **/
	const RESOURCE_TOURNAMENT = '1436:tournament';
	const RESOURCE_TOURNAMENT_VERSION = 'v4';

	/**
	 *   Creates set of tournament codes for given tournament.
	 *
	 * @cli-name create-codes
	 * @cli-namespace tournament
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
	 * @link https://developer.riotgames.com/apis#tournament-v4/POST_createTournamentCode
	 */
	public function createTournamentCodes( int $tournament_id, int $count, TournamentCodeParameters $parameters )
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->createTournamentCodes_STUB($tournament_id, $count, $parameters);

		if ($parameters->teamSize <= 0)
			throw new RequestParameterException('Team size (teamSize) must be greater than or equal to 1.');

		if ($parameters->teamSize >= 6)
			throw new RequestParameterException('Team size (teamSize) must be less than or equal to 5.');

		if (in_array($parameters->pickType, self::TOURNAMENT_ALLOWED_PICK_TYPES, true) == false)
			throw new RequestParameterException('Value of pick type (pickType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_PICK_TYPES));

		if (in_array($parameters->mapType, self::TOURNAMENT_ALLOWED_MAPS, true) == false)
			throw new RequestParameterException('Value of map type (mapType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_MAPS));

		if (in_array($parameters->spectatorType, self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES, true) == false)
			throw new RequestParameterException('Value of spectator type (spectatorType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES));

		$parameter_array = get_object_vars($parameters);
		if (empty($parameters->allowedSummonerIds))
		{
			unset($parameter_array['allowedSummonerIds']);
		}
		else
		{
			if ($parameters->teamSize * 2 > count($parameters->allowedSummonerIds))
				throw new RequestParameterException('Not enough players to fill teams (more participants required). If you wish to allow anyone do not fill "allowedSummonerIds" field.');
		}
		$data = json_encode($parameter_array);

		$resultPromise = $this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/codes")
			->setResource(self::RESOURCE_TOURNAMENT, "/codes")
			->addQuery('tournamentId', $tournament_id)
			->addQuery('count', $count)
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return $result;
		});
	}

	/**
	 *   Updates tournament code's settings.
	 *
	 * @cli-name edit-code
	 * @cli-namespace tournament
	 *
	 * @param string $tournament_code
	 * @param Objects\TournamentCodeUpdateParameters $parameters
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws RequestParameterException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tournament-v4/PUT_updateCode
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

		$resultPromise = $this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/codes/{$tournament_code}")
			->setResource(self::RESOURCE_TOURNAMENT, "/codes/%s")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_PUT);

		$this->resolveOrEnqueuePromise($resultPromise);
	}

	/**
	 *   Retrieves tournament code settings for given tournament code.
	 *
	 * @cli-name get-code-data
	 * @cli-namespace tournament
	 *
	 * @param string $tournament_code
	 *
	 * @return Objects\TournamentCodeDto
	 *
	 * @throws SettingsException
	 * @throws RequestException
	 * @throws ServerException
	 * @throws ServerLimitException
	 * @throws GeneralException
	 *
	 * @link https://developer.riotgames.com/apis#tournament-v4/GET_getTournamentCode
	 */
	public function getTournamentCodeData( string $tournament_code )
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			throw new RequestException('This endpoint is not available in interim mode.');

		$resultPromise = $this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/codes/{$tournament_code}")
			->setResource(self::RESOURCE_TOURNAMENT, "/codes/%s")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS);

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\TournamentCodeDto($result, $this);
		});
	}

	/**
	 *   Creates a tournament provider and returns its ID.
	 *
	 * @cli-name create-provider
	 * @cli-namespace tournament
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
	 * @link https://developer.riotgames.com/apis#tournament-v4/POST_registerProviderData
	 */
	public function createTournamentProvider( ProviderRegistrationParameters $parameters )
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->createTournamentProvider_STUB($parameters);

		if (empty($parameters->url))
			throw new RequestParameterException('Callback URL (url) may not be empty.');

		if (in_array(strtolower($parameters->region), self::TOURNAMENT_ALLOWED_REGIONS, true) == false)
			throw new RequestParameterException('Value of region (region) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_REGIONS));

		$parameters->region = strtoupper($parameters->region);

		$data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

		$resultPromise = $this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/providers")
			->setResource(self::RESOURCE_TOURNAMENT, "/providers")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->resolveOrEnqueuePromise($resultPromise, function(int $result) {
			return $result;
		});
	}

	/**
	 *   Creates a tournament and returns its ID.
	 *
	 * @cli-name create-tournament
	 * @cli-namespace tournament
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
	 * @link https://developer.riotgames.com/apis#tournament-v4/POST_registerTournament
	 */
	public function createTournament( TournamentRegistrationParameters $parameters )
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->createTournament_STUB($parameters);

		if (empty($parameters->name))
			throw new RequestParameterException('Tournament name (name) may not be empty.');

		if ($parameters->providerId <= 0)
			throw new RequestParameterException('ProviderID (providerId) must be greater than or equal to 1.');

		$data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

		$resultPromise = $this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/tournaments")
			->setResource(self::RESOURCE_TOURNAMENT, "/tournaments")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->resolveOrEnqueuePromise($resultPromise, function(int $result) {
			return $result;
		});
	}

	/**
	 *   Gets a list of lobby events by tournament code.
	 *
	 * @cli-name get-lobby-events
	 * @cli-namespace tournament
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
	 * @link https://developer.riotgames.com/apis#tournament-v4/GET_getLobbyEventsByCode
	 */
	public function getTournamentLobbyEvents( string $tournament_code )
	{
		if ($this->getSetting(self::SET_INTERIM, false))
			return $this->getTournamentLobbyEvents_STUB($tournament_code);

		$resultPromise = $this->setEndpoint("/lol/tournament/" . self::RESOURCE_TOURNAMENT_VERSION . "/lobby-events/by-code/{$tournament_code}")
			->setResource(self::RESOURCE_TOURNAMENT, "/lobby-events/by-code/%s")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS);

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LobbyEventDtoWrapper($result, $this);
		});
	}


	/**
	 * ==================================================================dd=
	 *     Tournament Stub Endpoint Methods
	 *     @link https://developer.riotgames.com/apis#tournament-stub-v4
	 * ==================================================================dd=
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
	 * @link https://developer.riotgames.com/apis#tournament-stub-v4/POST_createTournamentCode
	 *
	 * @internal
	 */
	public function createTournamentCodes_STUB( int $tournament_id, int $count, TournamentCodeParameters $parameters )
	{
		if ($parameters->teamSize <= 0)
			throw new RequestParameterException('Team size (teamSize) must be greater than or equal to 1.');

		if ($parameters->teamSize >= 6)
			throw new RequestParameterException('Team size (teamSize) must be less than or equal to 5.');

		if (in_array($parameters->pickType, self::TOURNAMENT_ALLOWED_PICK_TYPES, true) == false)
			throw new RequestParameterException('Value of pick type (pickType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_PICK_TYPES));

		if (in_array($parameters->mapType, self::TOURNAMENT_ALLOWED_MAPS, true) == false)
			throw new RequestParameterException('Value of map type (mapType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_MAPS));

		if (in_array($parameters->spectatorType, self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES, true) == false)
			throw new RequestParameterException('Value of spectator type (spectatorType) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_SPECTATOR_TYPES));

		$parameter_array = get_object_vars($parameters);
		if (empty($parameters->allowedSummonerIds))
		{
			unset($parameter_array['allowedSummonerIds']);
		}
		else
		{
			if ($parameters->teamSize * 2 > count($parameters->allowedSummonerIds))
				throw new RequestParameterException('Not enough players to fill teams (more participants required). If you wish to allow anyone do not fill "allowedSummonerIds" field.');
		}
		$data = json_encode($parameter_array);

		$resultPromise = $this->setEndpoint("/lol/tournament-stub/" . self::RESOURCE_TOURNAMENT_STUB_VERSION . "/codes")
			->setResource(self::RESOURCE_TOURNAMENT, "/codes")
			->addQuery('tournamentId', $tournament_id)
			->addQuery('count', $count)
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return $result;
		});
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
	 * @link https://developer.riotgames.com/apis#tournament-stub-v4/POST_registerProviderData
	 *
	 * @internal
	 */
	public function createTournamentProvider_STUB( ProviderRegistrationParameters $parameters )
	{
		if (empty($parameters->url))
			throw new RequestParameterException('Callback URL (url) may not be empty.');

		if (in_array(strtolower($parameters->region), self::TOURNAMENT_ALLOWED_REGIONS, true) == false)
			throw new RequestParameterException('Value of region (region) is invalid. Allowed values: ' . implode(', ', self::TOURNAMENT_ALLOWED_REGIONS));

		$parameters->region = strtoupper($parameters->region);

		$data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

		$resultPromise = $this->setEndpoint("/lol/tournament-stub/" . self::RESOURCE_TOURNAMENT_STUB_VERSION . "/providers")
			->setResource(self::RESOURCE_TOURNAMENT_STUB, "/providers")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->resolveOrEnqueuePromise($resultPromise, function(int $result) {
			return $result;
		});
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
	 * @link https://developer.riotgames.com/apis#tournament-stub-v4/POST_registerTournament
	 *
	 * @internal
	 */
	public function createTournament_STUB( TournamentRegistrationParameters $parameters )
	{
		if (empty($parameters->name))
			throw new RequestParameterException('Tournament name (name) may not be empty.');

		if ($parameters->providerId <= 0)
			throw new RequestParameterException('ProviderID (providerId) must be greater than or equal to 1.');

		$data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

		$resultPromise = $this->setEndpoint("/lol/tournament-stub/" . self::RESOURCE_TOURNAMENT_STUB_VERSION . "/tournaments")
			->setResource(self::RESOURCE_TOURNAMENT_STUB, "/tournaments")
			->setData($data)
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS, self::METHOD_POST);

		return $this->resolveOrEnqueuePromise($resultPromise, function(int $result) {
			return $result;
		});
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
	 * @link https://developer.riotgames.com/apis#tournament-stub-v4/GET_getLobbyEventsByCode
	 *
	 * @internal
	 */
	public function getTournamentLobbyEvents_STUB( string $tournament_code )
	{
		$resultPromise = $this->setEndpoint("/lol/tournament-stub/" . self::RESOURCE_TOURNAMENT_STUB_VERSION . "/lobby-events/by-code/{$tournament_code}")
			->setResource(self::RESOURCE_TOURNAMENT_STUB, "/lobby-events/by-code/%s")
			->useKey(self::SET_TOURNAMENT_KEY)
			->makeCall(Region::AMERICAS);

		return $this->resolveOrEnqueuePromise($resultPromise, function(array $result) {
			return new Objects\LobbyEventDtoWrapper($result, $this);
		});
	}


	/**
	 * ==================================================================dd=
	 *     Fake Endpoint for testing purposes
	 * ==================================================================dd=
	 **/

	/**
	 * @internal
	 *
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
	 * @throws GeneralException
	 */
	public function makeTestEndpointCall( $specs, string $region = null, string $method = null )
	{
		$resultPromise = $this->setEndpoint("/lol/test-endpoint/v0/{$specs}")
			->setResource("v0", "/lol/test-endpoint/v0/%s")
			->makeCall($region ?: null, $method ?: self::METHOD_GET);

		return $this->resolveOrEnqueuePromise($resultPromise, function($result) {
			return $result;
		});
	}
}
