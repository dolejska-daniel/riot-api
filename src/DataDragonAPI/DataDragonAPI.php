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

namespace RiotAPI\DataDragonAPI;

use RiotAPI\LeagueAPI\LeagueAPI;

use RiotAPI\LeagueAPI\Objects\SummonerDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticImageDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticRealmDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticChampionDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticItemDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticMapDetailsDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticMasteryDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticRuneDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticChampionSpellDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticSummonerSpellDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticReforgedRuneDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticReforgedRunePathDto;

use RiotAPI\LeagueAPI\Definitions\Cache;

use RiotAPI\LeagueAPI\Exceptions as LeagueExceptions;

use RiotAPI\DataDragonAPI\Exceptions\RequestException;
use RiotAPI\DataDragonAPI\Exceptions\SettingsException;
use RiotAPI\DataDragonAPI\Exceptions\ArgumentException;

use Nette\Utils\Html;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


class DataDragonAPI
{
	/**
	 *   Settings constants.
	 */
	const
		SET_ENDPOINT                    = 'datadragon-cdn',
		SET_VERSION                     = 'version',
		SET_CUSTOM_IMG_ATTRS            = 'custom-attrs',
		SET_DEFAULT_CLASS               = 'class-default',
		SET_PROFILE_ICON_CLASS          = 'class-profile',
		SET_MASTERY_ICON_CLASS          = 'class-mastery',
		SET_RUNE_ICON_CLASS             = 'class-rune',
		SET_REFORGED_RUNE_ICON_CLASS    = 'class-reforged-rune',
		SET_CHAMP_SPLASH_CLASS          = 'class-champ_splash',
		SET_CHAMP_LOADING_CLASS         = 'class-champ_loading',
		SET_CHAMP_ICON_CLASS            = 'class-champ_icon',
		SET_SPRITE_CLASS                = 'class-champ_icon_sprite',
		SET_SPELL_ICON_CLASS            = 'class-spell',
		SET_ITEM_ICON_CLASS             = 'class-item',
		SET_UI_ICON_CLASS               = 'class-scoreboard',
		SET_MINIMAP_CLASS               = 'class-minimap';

	/**
	 *   Contains library settings.
	 * 
	 * @var $settings array
	 */
	static protected $settings = [
		self::SET_ENDPOINT                  => 'https://ddragon.leagueoflegends.com/cdn/',
		self::SET_DEFAULT_CLASS             => 'dd-icon',
		self::SET_PROFILE_ICON_CLASS        => 'dd-icon-profile',
		self::SET_MASTERY_ICON_CLASS        => 'dd-icon-mastery',
		self::SET_RUNE_ICON_CLASS           => 'dd-icon-rune',
		self::SET_REFORGED_RUNE_ICON_CLASS  => 'dd-icon-reforged-rune',
		self::SET_CHAMP_SPLASH_CLASS        => 'dd-splash',
		self::SET_CHAMP_LOADING_CLASS       => 'dd-loading',
		self::SET_CHAMP_ICON_CLASS          => 'dd-icon-champ',
		self::SET_SPRITE_CLASS              => 'dd-sprite',
		self::SET_SPELL_ICON_CLASS          => 'dd-icon-spell',
		self::SET_ITEM_ICON_CLASS           => 'dd-icon-item',
		self::SET_UI_ICON_CLASS             => 'dd-icon-ui',
		self::SET_MINIMAP_CLASS             => 'dd-minimap',
	];

	/**
	 *   Static-data file constants.
	 */
	const
		STATIC_PROFILEICONS     = 'profileicon',
		STATIC_CHAMPIONS        = 'champion',
		STATIC_CHAMPION         = 'champion/',
		STATIC_ITEMS            = 'item',
		STATIC_MASTERIES        = 'mastery',
		STATIC_RUNES            = 'rune',
		STATIC_SUMMONERSPELLS   = 'summoner',
		STATIC_LANGUAGESTRINGS  = 'language',
		STATIC_MAPS             = 'map',
		STATIC_RUNESREFORGED    = 'runesReforged';

	/**
	 *   Available URL fragments.
	 */
	const
		STATIC_SUMMONERSPELLS_BY_KEY = "#by-key",
		STATIC_CHAMPION_BY_KEY       = "#by-key";

	/**
	 *   Contains library settings.
	 *
	 * @var array $settings
	 */
	static protected $staticFileTypes = [
		self::STATIC_PROFILEICONS,
		self::STATIC_CHAMPIONS,
		self::STATIC_ITEMS,
		self::STATIC_MASTERIES,
		self::STATIC_RUNES,
		self::STATIC_SUMMONERSPELLS,
		self::STATIC_LANGUAGESTRINGS,
		self::STATIC_MAPS,
	];

	/**
	 *   Contains library settings.
	 *
	 * @var array $settings
	 */
	static protected $staticData = [];

	/**
	 *   Indicates, whether the library has been initialized or not.
	 *
	 * @var bool $initialized
	 */
	static protected $initialized = false;

	/**
	 *   Indicates, whether HTTPS is used or not.
	 *
	 * @var bool $ssl
	 */
	static public $ssl = true;

	/**
	 *   Holds current caching interface.
	 *
	 * @var CacheItemPoolInterface $cache
	 */
	static protected $cache;

	/**
	 *   Sets active cache interface instance.
	 *
	 * @param CacheItemPoolInterface $cacheInterface
	 */
	public static function setCacheInterface( CacheItemPoolInterface $cacheInterface )
	{
		self::$cache = $cacheInterface;
	}

	/**
	 *   Returns or initializes default cache interface.
	 *
	 * @return CacheItemPoolInterface
	 */
	public static function getCacheInterface()
	{
		if (!self::$cache)
		{
			$cacheInterface = new FilesystemAdapter(
				Cache::DATADRAGON_NAMESPACE,
				Cache::LIFETIME,
				Cache::getDirectoryPath()
			);
			self::setCacheInterface($cacheInterface);
		}

		return self::$cache;
	}


	/**
	 *   Creates new instance by fetching latest Realm info from DataDragon.
	 *
	 * @param array $customSettings
	 *
	 * @throws RequestException
	 */
	public static function initByCdn( array $customSettings = [] )
	{
		$data = file_get_contents("https://ddragon.leagueoflegends.com/api/versions.json");
		if ($data == false)
			throw new RequestException('Version list failed to be fetched from DataDragon.');

		$obj = json_decode($data);

		self::setSettings([
			self::SET_VERSION  => reset($obj),
			self::SET_ENDPOINT => self::getCdnUrl(),
		]);

		if (!empty($customSettings))
			self::setSettings($customSettings);

		self::$initialized = true;
	}

	/**
	 *   Creates new instance by fetching latest Realm info from DataDragon.
	 *
	 * @param string $region_name
	 * @param array  $customSettings
	 *
	 * @throws RequestException
	 */
	public static function initByRegion( string $region_name, array $customSettings = [] )
	{
		$region_name = strtolower($region_name);
		$data = file_get_contents(self::getDataDragonUrl() . "/realms/$region_name.json");
		if ($data == false)
			throw new RequestException('Version list failed to be fetched from DataDragon.');

		$obj = json_decode($data);

		self::setSettings([
			self::SET_VERSION  => $obj->dd,
			self::SET_ENDPOINT => $obj->cdn . "/",
		]);

		if (!empty($customSettings))
			self::setSettings($customSettings);

		self::$initialized = true;
	}

	/**
	 *   Creates new instance by specifying CDN version.
	 *
	 * @param string $version
	 * @param array  $customSettings
	 */
	public static function initByVersion( string $version, array $customSettings = [] )
	{
		self::setSettings([
			self::SET_VERSION  => $version,
			self::SET_ENDPOINT => self::getCdnUrl(),
		]);

		if (!empty($customSettings))
			self::setSettings($customSettings);

		self::$initialized = true;
	}

	/**
	 *   Creates new instance by fetching latest Realm info by API static-data endpoint
	 * request.
	 *
	 * @param LeagueAPI $api
	 * @param array $customSettings
	 *
	 * @throws LeagueExceptions\RequestException
	 * @throws LeagueExceptions\ServerException
	 */
	public static function initByApi( LeagueAPI $api, array $customSettings = [] )
	{
		self::initByRealmObject($api->getStaticRealm(), $customSettings);
	}

	/**
	 *   Creates new instance from Realm object.
	 *
	 * @param StaticRealmDto $realm
	 * @param array          $customSettings
	 */
	public static function initByRealmObject( StaticRealmDto $realm, array $customSettings = [] )
	{
		self::setSettings([
			self::SET_ENDPOINT => $realm->cdn . "/",
			self::SET_VERSION  => $realm->dd,
		]);

		if (!empty($customSettings))
			self::setSettings($customSettings);

		self::$initialized = true;
	}


	/**
	 *   Returns vaue of requested key from settings.
	 *
	 * @param string     $name
	 * @param mixed|null $defaultValue
	 *
	 * @return mixed
	 */
	public static function getSetting( string $name, $defaultValue = null )
	{
		return self::isSettingSet($name)
			? self::$settings[$name]
			: $defaultValue;
	}

	/**
	 *   Sets new value for specified key in settings.
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	public static function setSetting( string $name, $value )
	{
		self::$settings[$name] = $value;
	}

	/**
	 *   Sets new values for specified set of keys in settings.
	 *
	 * @param array $values
	 */
	public static function setSettings( array $values )
	{
		foreach ($values as $name => $value)
			self::setSetting($name, $value);
	}

	/**
	 *   Checks if specified settings key is set.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public static function isSettingSet( string $name ): bool
	{
		return isset(self::$settings[$name]) && !empty(self::$settings[$name]);
	}

	/**
	 *   Checks whether the library has been initialized.
	 *
	 * @throws SettingsException
	 */
	public static function checkInit()
	{
		if (!self::$initialized)
			throw new SettingsException('DataDragon class was not initialized - version is potentially unknown.');
	}

	/**
	 *   Returns URL schema based on library settings.
	 *
	 * @return string
	 */
	protected static function getUrlSchema(): string
	{
		return self::$ssl ? "https" : "http";
	}

	/**
	 *   Returns DataDragon URL.
	 *
	 * @return string
	 */
	public static function getDataDragonUrl(): string
	{
		return self::getUrlSchema() . "://ddragon.leagueoflegends.com";
	}

	/**
	 *   Returns DataDragon CDN endpoint URL.
	 *
	 * @return string
	 */
	public static function getCdnUrl(): string
	{
		return self::getDataDragonUrl() . "/cdn/";
	}

	/**
	 *   Returns static-data file URL based on given type.
	 *
	 * @param string $dataType
	 * @param string $key
	 * @param string $locale
	 * @param null $version
	 * @param string|null $fragment
	 *
	 * @return string
	 *
	 * @throws SettingsException
	 */
	public static function getStaticDataFileUrl( string $dataType, string $key = null, $locale = 'en_US', $version = null, string $fragment = null ): string
	{
		if (is_null($version))
			self::checkInit();

		return self::getCdnUrl() . ($version ?: self::getSetting(self::SET_VERSION)) . "/data/$locale/$dataType$key.json$fragment";
	}

	/**
	 *   Loads static-data for given URL. First from cache, if it doesnt exist,
	 * try to fetch up-to date from web.
	 *
	 * @param string $url
	 * @param callable|null $postprocess (string $url, array $data)
	 * @param bool $data_from_postprocess
	 *
	 * @return array
	 *
	 * @throws ArgumentException
	 */
	protected static function loadStaticData( string $url, callable $postprocess = null, bool $data_from_postprocess = false ): array
	{
		// Try loading from cache
		$data = self::loadCachedStaticData($url);
		if ($data->isHit()) return $data->get();

		$fragmentlessUrl = $url;
		if (($fragmentPos = strpos($url, "#")) !== false)
			$fragmentlessUrl = substr($url, 0, $fragmentPos);

		// Try loading from web
		$data = @file_get_contents($url);
		if (!$data) throw new ArgumentException("Failed to load static-data for URL: '$url'.");

		// Decode and save data to cache
		$data = json_decode($data, true);
		self::saveStaticData($fragmentlessUrl, $data);

		if ($postprocess)
		{
			$postprocess_data = $postprocess($fragmentlessUrl, $data);
			if ($data_from_postprocess)
				return $postprocess_data;
		}
		return $data;
	}

	/**
	 * @return bool
	 */
	public static function clearCachedStaticData()
	{
		return self::getCacheInterface()->clear();
	}

	/**
	 * @param string $url
	 *
	 * @return array
	 */
	protected static function loadCachedStaticData( string $url ): CacheItemInterface
	{
		$urlHash = md5($url);
		return self::getCacheInterface()->getItem($urlHash);
	}

	/**
	 * @param string $url
	 * @param array  $data
	 */
	protected static function saveStaticData( string $url, array $data )
	{
		$urlHash = md5($url);
		$cacheInterface = self::getCacheInterface();

		$staticData = $cacheInterface->getItem($urlHash);
		$staticData->set($data);
		$staticData->expiresAfter(3600);

		$cacheInterface->save($staticData);
	}


	// ==================================================================dd=
	//     Available methods
	//     @link https://developer.riotgames.com/ddragon.html
	// ==================================================================dd=

	// ---------------------------------------------dd-
	//  Profile icons
	// ---------------------------------------------dd-

	/**
	 *   Returns profile icon URL.
	 *
	 * @param int $profile_icon_id
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getProfileIconUrl( int $profile_icon_id ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/profileicon/{$profile_icon_id}.png";
	}

	/**
	 *   Returns profile icon in img HTML TAG.
	 *
	 * @param int   $profile_icon_id
	 * @param array $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getProfileIcon( int $profile_icon_id, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt' => 'Profile Icon' ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_PROFILE_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getProfileIconUrl($profile_icon_id);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns profile icon from API static-data Summoner object in img HTML TAG.
	 *
	 * @param SummonerDto $summoner
	 * @param array       $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getProfileIconO( SummonerDto $summoner, array $attributes = [] ): Html
	{
		return self::getProfileIcon($summoner->profileIconId, $attributes);
	}

	/**
	 *   Returns profile icon URL.
	 *
	 * @param string $summoner_name
	 * @param string $platform_id
	 *
	 * @return string
	 */
	public static function getProfileIconUrlByName( string $summoner_name, string $platform_id ): string
	{
		return "https://avatar.leagueoflegends.com/{$platform_id}/{$summoner_name}.png";
	}

	/**
	 *   Returns profile icon in img HTML TAG.
	 *
	 * @param string $summoner_name
	 * @param string $platform_id
	 * @param array  $attributes
	 *
	 * @return Html
	 */
	public static function getProfileIconByName( string $summoner_name, string $platform_id, array $attributes = [] ): Html
	{
		$attrs = array_merge([ 'alt' => $summoner_name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_PROFILE_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getProfileIconUrlByName($summoner_name, $platform_id);

		return Html::el('img', $attrs);
	}


	// ---------------------------------------------dd-
	//  Champion splashes
	// ---------------------------------------------dd-

	/**
	 *   Returns champion splash image URL.
	 *
	 * @param string $champion_name
	 * @param int    $skin
	 *
	 * @return string
	 */
	public static function getChampionSplashUrl( string $champion_name, int $skin = 0 ): string
	{
		return self::getSetting(self::SET_ENDPOINT) . "img/champion/splash/{$champion_name}_{$skin}.jpg";
	}

	/**
	 *   Returns champion splash image in img HTML TAG.
	 *
	 * @param string $champion_name
	 * @param int    $skin
	 * @param array  $attributes
	 *
	 * @return Html
	 */
	public static function getChampionSplash( string $champion_name, int $skin = 0, array $attributes = [] ): Html
	{
		$attrs = array_merge([ 'alt' => $champion_name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_CHAMP_SPLASH_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getChampionSplashUrl($champion_name, $skin);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns champion splash from API static-data Champion object in img HTML TAG.
	 *
	 * @param StaticChampionDto $champion
	 * @param int               $skin
	 * @param array             $attributes
	 *
	 * @return Html
	 */
	public static function getChampionSplashO( StaticChampionDto $champion, int $skin = 0, array $attributes = [] ): Html
	{
		return self::getChampionSplash($champion->id, $skin, $attributes);
	}


	// ---------------------------------------------dd-
	//  Champion loading
	// ---------------------------------------------dd-

	/**
	 *   Returns champion loading screen image URL.
	 *
	 * @param string $champion_name
	 * @param int    $skin
	 *
	 * @return string
	 */
	public static function getChampionLoadingUrl( string $champion_name, int $skin = 0 ): string
	{
		return self::getSetting(self::SET_ENDPOINT) . "img/champion/loading/{$champion_name}_{$skin}.jpg";
	}

	/**
	 *   Returns champion loading screen image in img HTML TAG.
	 *
	 * @param string $champion_name
	 * @param int    $skin
	 * @param array  $attributes
	 *
	 * @return Html
	 */
	public static function getChampionLoading( string $champion_name, int $skin = 0, array $attributes = [] ): Html
	{
		$attrs = array_merge([ 'alt' => $champion_name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_CHAMP_LOADING_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getChampionLoadingUrl($champion_name, $skin);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns champion loading screen image from API static-data Champion object in
	 * img HTML TAG.
	 *
	 * @param StaticChampionDto $champion
	 * @param int               $skin
	 * @param array             $attributes
	 *
	 * @return Html
	 */
	public static function getChampionLoadingO( StaticChampionDto $champion, int $skin = 0, array $attributes = [] ): Html
	{
		return self::getChampionLoading($champion->id, $skin, $attributes);
	}


	// ---------------------------------------------dd-
	//  Champion icon
	// ---------------------------------------------dd-

	/**
	 *   Returns champion icon URL.
	 *
	 * @param string $champion_name
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getChampionIconUrl( string $champion_name ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/champion/{$champion_name}.png";
	}

	/**
	 *   Returns champion icon in img HTML TAG.
	 *
	 * @param string $champion_name
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getChampionIcon( string $champion_name, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt' => $champion_name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_CHAMP_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getChampionIconUrl($champion_name);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns champion icon from API static-data Champion object in img HTML TAG.
	 *
	 * @param StaticChampionDto $champion
	 * @param array             $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getChampionIconO( StaticChampionDto $champion, array $attributes = [] ): Html
	{
		return self::getChampionIcon($champion->id, $attributes);
	}


	// ---------------------------------------------dd-
	//  Sprites
	// ---------------------------------------------dd-

	/**
	 *   Returns icon from icon sprite in img HTML TAG.
	 *
	 * @param string $source
	 * @param int    $x
	 * @param int    $y
	 * @param int    $w
	 * @param int    $h
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getFromSprite( string $source, int $x, int $y, int $w = 48, int $h = 48, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt' => 'Sprite Icon' ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_SPRITE_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['style'] = 'background: transparent url(' . self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/sprite/{$source}" . ") -{$x}px -{$y}px; width: {$w}px; height: {$h}px;";
		$attrs['src'] = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
		
		return Html::el('img', $attrs);
	}

	/**
	 *   Returns icon from API static-data ImageDto object in img HTML TAG.
	 *
	 * @param StaticImageDto $image
	 * @param array          $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getFromSpriteO( StaticImageDto $image, array $attributes = [] ): Html
	{
		return self::getFromSprite($image->sprite, $image->x, $image->y, $image->w, $image->h, $attributes);
	}


	// ---------------------------------------------dd-
	//  Spell and summoner spell icon
	// ---------------------------------------------dd-

	/**
	 *   Returns spell icon URL.
	 *
	 * @param string $spell_name
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getSpellIconUrl( string $spell_name ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/spell/{$spell_name}.png";
	}

	/**
	 *   Returns passive icon URL.
	 *
	 * @param string $image_name
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getPassiveIconUrl( string $image_name ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/passive/{$image_name}.png";
	}

	/**
	 *   Returns spell or summoner spell icon in img HTML TAG.
	 *
	 * @param string $spell_name
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getSpellIcon( string $spell_name, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt'   => $spell_name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_SPELL_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getSpellIconUrl($spell_name);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns summoner spell icon from API static-data SummonerSpell object in img
	 * HTML TAG.
	 *
	 * @param StaticSummonerSpellDto $summonerSpell
	 * @param array                  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getSummonerSpellIconO( StaticSummonerSpellDto $summonerSpell, array $attributes = [] ): Html
	{
		return self::getSpellIcon($summonerSpell->id, $attributes);
	}

	/**
	 *   Returns spell icon from API static-data ChampionSpell object in img
	 * HTML TAG.
	 *
	 * @param StaticChampionSpellDto $championSpell
	 * @param array                  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getChampionSpellIconO( StaticChampionSpellDto $championSpell, array $attributes = [] ): Html
	{
		return self::getSpellIcon($championSpell->id, $attributes);
	}


	// ---------------------------------------------dd-
	//  Champion passive icon
	// ---------------------------------------------dd-

	/**
	 *   Returns champion passive icon in img HTML TAG.
	 *
	 * @param string $image_name
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getChampionPassiveIcon( $image_name, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt'   => $image_name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_SPELL_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getPassiveIconUrl($image_name);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns champion passive icon from API static-data ChampionDto object
	 * in img HTML TAG.
	 *
	 * @param StaticChampionDto $champion
	 * @param array             $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getChampionPassiveIconO( StaticChampionDto $champion, array $attributes = [] ): Html
	{
		if (is_null($champion->passive))
		{
			trigger_error("Extended champion static data are required for this function to work.");
			return Html::el("p")->setHtml("This <code>StaticChampionDto</code> instance does not contain required data. Extended data are required.");
		}

		// Remove ".png" from image name
		$image_name = substr($champion->passive->image->full, 0, -4);
		return self::getChampionPassiveIcon($image_name, $attributes);
	}


	// ---------------------------------------------dd-
	//  Item icon
	// ---------------------------------------------dd-

	/**
	 *   Returns item icon URL.
	 *
	 * @param int $item_id
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getItemIconUrl( int $item_id ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/item/{$item_id}.png";
	}

	/**
	 *   Returns item icon in img HTML TAG.
	 *
	 * @param int    $item_id
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getItemIcon( int $item_id, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt'   => $item_id ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_ITEM_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getItemIconUrl($item_id);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns item icon from API static-data Item object in img HTML TAG.
	 *
	 * @param StaticItemDto $item
	 * @param array         $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getItemIconO( StaticItemDto $item, array $attributes = [] ): Html
	{
		return self::getItemIcon($item->id, $attributes);
	}


	// ---------------------------------------------dd-
	//  Mastery icon
	// ---------------------------------------------dd-

	/**
	 *   Return mastery icon URL.
	 *
	 * @param int $mastery_id
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getMasteryIconUrl( int $mastery_id ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/mastery/{$mastery_id}.png";
	}

	/**
	 *   Returns mastery icon in img HTML TAG.
	 *
	 * @param int    $mastery_id
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getMasteryIcon( int $mastery_id, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt'   => $mastery_id ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_MASTERY_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getMasteryIconUrl($mastery_id);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns mastery icon from API static-data Mastery object in img HTML TAG.
	 *
	 * @param StaticMasteryDto $mastery
	 * @param array            $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getMasteryIconO( StaticMasteryDto $mastery, array $attributes = [] ): Html
	{
		return self::getMasteryIcon($mastery->id, $attributes);
	}


	// ---------------------------------------------dd-
	//  Rune icon
	// ---------------------------------------------dd-

	/**
	 *   Returns rune icon URL.
	 *
	 * @param int $rune_id
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getRuneIconUrl( int $rune_id ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/rune/{$rune_id}.png";
	}

	/**
	 *   Returns rune icon in img HTML TAG.
	 *
	 * @param int    $rune_id
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getRuneIcon( int $rune_id, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt'   => $rune_id ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_RUNE_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getRuneIconUrl($rune_id);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns rune icon from API static-data Rune object in img HTML TAG.
	 *
	 * @param StaticRuneDto $rune
	 * @param array         $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getRuneIconO( StaticRuneDto $rune, array $attributes = [] ): Html
	{
		return self::getRuneIcon($rune->id, $attributes);
	}


	// ---------------------------------------------dd-
	//  Runes Reforged
	// ---------------------------------------------dd-

	/**
	 *   Returns reforged rune icon URL.
	 *
	 * @param StaticReforgedRuneDto $rune
	 *
	 * @return string
	 */
	public static function getReforgedRuneIconUrlO( StaticReforgedRuneDto $rune ): string
	{
		return self::getSetting(self::SET_ENDPOINT) . "img/$rune->icon";
	}

	/**
	 *   Returns reforged rune icon from API static-data ReforgedRune object in
	 * img HTML TAG.
	 *
	 * @param StaticReforgedRuneDto $rune
	 * @param array                 $attributes
	 *
	 * @return Html
	 */
	public static function getReforgedRuneIconO( StaticReforgedRuneDto $rune, array $attributes = [] ): Html
	{
		$attrs = array_merge([ 'alt'   => $rune->name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_REFORGED_RUNE_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getReforgedRuneIconUrlO($rune);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns reforged rune path icon URL.
	 *
	 * @param StaticReforgedRunePathDto $runePath
	 *
	 * @return string
	 */
	public static function getReforgedRunePathIconUrlO( StaticReforgedRunePathDto $runePath ): string
	{
		return self::getSetting(self::SET_ENDPOINT) . "img/$runePath->icon";
	}

	/**
	 *   Returns reforged rune path from API static-data ReforgedRunePath
	 * object in img HTML TAG.
	 *
	 * @param StaticReforgedRunePathDto $runePath
	 * @param array                     $attributes
	 *
	 * @return Html
	 */
	public static function getReforgedRunePathIconO( StaticReforgedRunePathDto $runePath, array $attributes = [] ): Html
	{
		$attrs = array_merge([ 'alt'   => $runePath->name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_REFORGED_RUNE_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getReforgedRunePathIconUrlO($runePath);

		return Html::el('img', $attrs);
	}


	// ---------------------------------------------dd-
	//  Minimap
	// ---------------------------------------------dd-

	/**
	 *   Returns minimap image URL.
	 *
	 * @param int $map_id
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getMinimapUrl( int $map_id ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/map/map{$map_id}.png";
	}

	/**
	 *   Returns minimap image in img HTML TAG.
	 *
	 * @param int    $map_id
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getMinimap( int $map_id, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt' => $map_id ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_MINIMAP_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getMinimapUrl($map_id);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns minimap from API static-data MapDetails object in img HTML TAG.
	 *
	 * @param StaticMapDetailsDto $mapDetails
	 * @param array               $attributes
	 * 
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getMinimapO( StaticMapDetailsDto $mapDetails, array $attributes = [] ): Html
	{
		return self::getMinimap($mapDetails->mapId, $attributes);
	}


	// ---------------------------------------------dd-
	//  Scoreboard icon
	// ---------------------------------------------dd-

	/**
	 *   Returns UI icon URL.
	 *
	 * @param string $name
	 *
	 * @return string
	 * @throws SettingsException
	 */
	public static function getScoreboardIconUrl( string $name ): string
	{
		self::checkInit();
		return self::getSetting(self::SET_ENDPOINT) . self::getSetting(self::SET_VERSION) . "/img/ui/{$name}.png";
	}

	/**
	 *   Returns UI icon in img HTML TAG.
	 *
	 * @param string $name
	 * @param array  $attributes
	 *
	 * @return Html
	 * @throws SettingsException
	 */
	public static function getScoreboardIcon( string $name, array $attributes = [] ): Html
	{
		self::checkInit();

		$attrs = array_merge([ 'alt' => $name ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []), $attributes);
		$attrs['class'] = implode(' ', [
			self::getSetting(self::SET_DEFAULT_CLASS),
			self::getSetting(self::SET_UI_ICON_CLASS),
			@self::getSetting(self::SET_CUSTOM_IMG_ATTRS, [])['class'],
			@$attributes['class'],
		]);
		$attrs['src'] = self::getScoreboardIconUrl($name);

		return Html::el('img', $attrs);
	}

	// ---------------------------------------------dd-
	//  Static-data
	// ---------------------------------------------dd-

	/**
	 * @param string $locale
	 * @param string|null $version
	 * @param bool $data_by_key
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticChampions( string $locale = 'en_US', string $version = null, bool $data_by_key = false ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_CHAMPIONS, null, $locale, $version, $data_by_key ? self::STATIC_CHAMPION_BY_KEY : null);
		return self::loadStaticData($url, [DataDragonAPI::class, "_champion"], $data_by_key);
	}

	/**
	 * @param string      $champion_id
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticChampionDetails( string $champion_id, string $locale = 'en_US', string $version = null ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_CHAMPION, $champion_id, $locale, $version);
		return self::loadStaticData($url);
	}

	/**
	 * @param string      $champion_id
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 *
	 * @see getStaticChampion
	 */
	public static function getStaticChampionById( string $champion_id, string $locale = 'en_US', string $version = null ) : array
	{
		$data = self::getStaticChampions($locale, $version);
		if (isset($data['data'][$champion_id]) == false)
			throw new ArgumentException("Champion with ID '$champion_id' was not found.", 404);

		return $data['data'][$champion_id];
	}

	/**
	 * @param int         $champion_key
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 *
	 * @see getStaticChampion
	 */
	public static function getStaticChampionByKey( int $champion_key, string $locale = 'en_US', string $version = null ) : array
	{
		$data = self::getStaticChampions($locale, $version, true);
		if (isset($data['data'][$champion_key]) == false)
			throw new ArgumentException("Champion with key '$champion_key' was not found.", 404);

		return $data['data'][$champion_key];
	}

	/**
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticItems( string $locale = 'en_US', string $version = null ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_ITEMS, null, $locale, $version);
		return self::loadStaticData($url);
	}

	/**
	 * @param int         $item_id
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticItem( int $item_id, string $locale = 'en_US', string $version = null ) : array
	{
		$data = self::getStaticItems($locale, $version);
		if (isset($data['data'][$item_id]) == false)
			throw new ArgumentException("Item with ID '$item_id' was not found.", 404);

		return $data['data'][$item_id];
	}

	/**
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticLanguageStrings( string $locale = 'en_US', string $version = null ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_LANGUAGESTRINGS, null, $locale, $version);
		return self::loadStaticData($url);
	}

	/**
	 * @return array
	 * @throws ArgumentException
	 */
	public static function getStaticLanguages() : array
	{
		$url = self::getCdnUrl() . "languages.json";
		return self::loadStaticData($url);
	}

	/**
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticMaps( string $locale = 'en_US', string $version = null ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_MAPS, null, $locale, $version);
		return self::loadStaticData($url);
	}

	/**
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticMasteries( string $locale = 'en_US', string $version = null ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_MASTERIES, null, $locale, $version);
		return self::loadStaticData($url);
	}

	/**
	 * @param int         $mastery_id
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticMastery( int $mastery_id, string $locale = 'en_US', string $version = null ) : array
	{
		$data = self::getStaticMasteries($locale, $version);
		if (isset($data['data'][$mastery_id]) == false)
			throw new ArgumentException("Mastery with ID '$mastery_id' was not found.", 404);

		return $data['data'][$mastery_id];
	}

	/**
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticRunes( string $locale = 'en_US', string $version = null ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_RUNES, null, $locale, $version);
		return self::loadStaticData($url);
	}

	/**
	 * @param int         $rune_id
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticRune( int $rune_id, string $locale = 'en_US', string $version = null) : array
	{
		$data = self::getStaticRunes($locale, $version);
		if (isset($data['data'][$rune_id]) == false)
			throw new ArgumentException("Rune with ID '$rune_id' was not found.", 404);

		return $data['data'][$rune_id];
	}

	/**
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticProfileIcons( string $locale = 'en_US', string $version = null ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_PROFILEICONS, null, $locale, $version);
		return self::loadStaticData($url);
	}

	/**
	 * @param string $region
	 *
	 * @return array
	 * @throws ArgumentException
	 */
	public static function getStaticRealms( string $region ) : array
	{
		$region = strtolower($region);
		$url = self::getDataDragonUrl() . "/realms/$region.json";
		return self::loadStaticData($url);
	}

	/**
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticReforgedRunes( string $locale = 'en_US', string $version = null ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_RUNESREFORGED, null, $locale, $version);
		return self::loadStaticData($url);
	}

	/**
	 * @param string $locale
	 * @param string|null $version
	 * @param bool $data_by_key
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticSummonerSpells( string $locale = 'en_US', string $version = null, bool $data_by_key = false ) : array
	{
		$url = self::getStaticDataFileUrl(self::STATIC_SUMMONERSPELLS, null, $locale, $version, $data_by_key ? self::STATIC_SUMMONERSPELLS_BY_KEY : null);
		return self::loadStaticData($url, [DataDragonAPI::class, "_summonerSpell"], $data_by_key);
	}

	/**
	 * @param string      $summonerspell_key
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticSummonerSpell( string $summonerspell_key, string $locale = 'en_US', string $version = null ) : array
	{
		$data = self::getStaticSummonerSpells($locale, $version);
		if (isset($data['data'][$summonerspell_key]) == false)
			throw new ArgumentException("Summoner spell with key '$summonerspell_key' was not found.", 404);

		return $data['data'][$summonerspell_key];
	}

	/**
	 * @param int         $summonerspell_key
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 */
	public static function getStaticSummonerSpellByKey( int $summonerspell_key, string $locale = 'en_US', string $version = null ) : array
	{
		return self::getStaticSummonerSpell($summonerspell_key, $locale, $version);
	}

	/**
	 * @param string      $summonerspell_id
	 * @param string      $locale
	 * @param string|null $version
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws SettingsException
	 *
	 * @see getStaticSummonerSpell
	 */
	public static function getStaticSummonerSpellById( string $summonerspell_id, string $locale = 'en_US', string $version = null ) : array
	{
		$data = self::getStaticSummonerSpells($locale, $version, true);
		if (isset($data['data'][$summonerspell_id]) == false)
			throw new ArgumentException("Summoner spell with ID '$summonerspell_id' was not found.", 404);

		return $data['data'][$summonerspell_id];
	}

	/**
	 * @return array
	 * @throws ArgumentException
	 */
	public static function getStaticVersions() : array
	{
		$url = self::getDataDragonUrl() . "/api/versions.json";
		return self::loadStaticData($url);
	}


	// ---------------------------------------------dd-
	//  Static-data processing functions
	// ---------------------------------------------dd-

	/**
	 * @param string $url
	 * @param array $data
	 *
	 * @return array
	 *
	 * @internal
	 */
	protected static function _summonerSpell( string $url, array $data )
	{
		$url .= self::STATIC_SUMMONERSPELLS_BY_KEY;
		$data_by_key = $data;
		$data_by_key['data'] = [];

		array_walk($data['data'], function( $d ) use (&$data_by_key) {
			$data_by_key['data'][(int)$d['key']] = $d;
		});

		self::saveStaticData($url, $data_by_key);
		return $data_by_key;
	}

	/**
	 * @param string $url
	 * @param array $data
	 *
	 * @return array
	 *
	 * @internal
	 */
	protected static function _champion( string $url, array $data )
	{
		$url .= self::STATIC_CHAMPION_BY_KEY;
		$data_by_key = $data;
		$data_by_key['data'] = [];

		array_walk($data['data'], function( $d ) use (&$data_by_key) {
			$data_by_key['data'][(int)$d['key']] = $d;
		});

		self::saveStaticData($url, $data_by_key);
		return $data_by_key;
	}
}