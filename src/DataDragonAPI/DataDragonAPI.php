<?php

/**
 * Copyright (C) 2016  Daniel Dolejška.
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

namespace DataDragonAPI;

use DataDragonAPI\Exception\RequestException;
use DataDragonAPI\Exception\SettingsException;
use Nette\Utils\Html;
use RiotAPI\Objects\StaticData\SChampionDto;
use RiotAPI\Objects\StaticData\SImageDto;
use RiotAPI\Objects\StaticData\SItemDto;
use RiotAPI\Objects\StaticData\SMapDetailsDto;
use RiotAPI\Objects\StaticData\SMasteryDto;
use RiotAPI\Objects\StaticData\SRealmDto;
use RiotAPI\Objects\StaticData\SRuneDto;
use RiotAPI\Objects\StaticData\SSummonerSpellDto;
use RiotAPI\Objects\SummonerDto;
use RiotAPI\RiotAPI;

class DataDragonAPI
{
    /** Settings constants. */
    const
        SET_ENDPOINT = 'datadragon-cdn';
    const SET_VERSION = 'version';
    const SET_CUSTOM_IMG_ATTRS = 'custom-attrs';
    const SET_DEFAULT_CLASS = 'class-default';
    const SET_PROFILE_ICON_CLASS = 'class-profile';
    const SET_MASTERY_ICON_CLASS = 'class-mastery';
    const SET_RUNE_ICON_CLASS = 'class-rune';
    const SET_CHAMP_SPLASH_CLASS = 'class-champ_splash';
    const SET_CHAMP_LOADING_CLASS = 'class-champ_loading';
    const SET_CHAMP_ICON_CLASS = 'class-champ_icon';
    const SET_SPRITE_CLASS = 'class-champ_icon_sprite';
    const SET_SUMSPELL_ICON_CLASS = 'class-summoner_spell';
    const SET_ITEM_ICON_CLASS = 'class-item';
    const SET_UI_ICON_CLASS = 'class-scoreboard';
    const SET_MINIMAP_CLASS = 'class-minimap';

    /**
     *   Contains library settings.
     *
     * @var array
     */
    protected static $settings = [
        self::SET_ENDPOINT            => 'http://ddragon.leagueoflegends.com/cdn/',
        self::SET_DEFAULT_CLASS       => 'dd-icon',
        self::SET_PROFILE_ICON_CLASS  => 'icon-profile',
        self::SET_MASTERY_ICON_CLASS  => 'icon-mastery',
        self::SET_RUNE_ICON_CLASS     => 'icon-rune',
        self::SET_CHAMP_SPLASH_CLASS  => 'champ-splash',
        self::SET_CHAMP_LOADING_CLASS => 'champ-loading',
        self::SET_CHAMP_ICON_CLASS    => 'icon-champ',
        self::SET_SPRITE_CLASS        => 'sprite',
        self::SET_SUMSPELL_ICON_CLASS => 'icon-sumspell',
        self::SET_ITEM_ICON_CLASS     => 'icon-item',
        self::SET_UI_ICON_CLASS       => 'icon-ui',
        self::SET_MINIMAP_CLASS       => 'minimap',
    ];

    protected static $initialized = false;

    /**
     *   Creates new instance by fetching latest Realm info from DataDragon.
     *
     * @param array $customSettings
     *
     * @throws RequestException
     */
    public static function initByCdn(array $customSettings = [])
    {
        $data = file_get_contents('https://ddragon.leagueoflegends.com/api/versions.json');
        if ($data == false) {
            throw new RequestException('Version list failed to be fetched from DataDragon.');
        }
        $obj = json_decode($data);

        self::setSettings([
            self::SET_VERSION  => reset($obj),
            self::SET_ENDPOINT => 'http://ddragon.leagueoflegends.com/cdn/',
        ]);

        if (!empty($customSettings)) {
            self::setSettings($customSettings);
        }

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
    public static function initByRegion(string $region_name, array $customSettings = [])
    {
        $data = file_get_contents("https://ddragon.leagueoflegends.com/realms/$region_name.json");
        if ($data == false) {
            throw new RequestException('Version list failed to be fetched from DataDragon.');
        }
        $obj = json_decode($data);

        self::setSettings([
            self::SET_VERSION  => $obj->dd,
            self::SET_ENDPOINT => $obj->cdn.'/',
        ]);

        if (!empty($customSettings)) {
            self::setSettings($customSettings);
        }

        self::$initialized = true;
    }

    /**
     *   Creates new instance by specifying CDN version.
     *
     * @param string $version
     * @param array  $customSettings
     */
    public static function initByVersion(string $version, array $customSettings = [])
    {
        self::setSettings([
            self::SET_VERSION  => $version,
        ]);

        if (!empty($customSettings)) {
            self::setSettings($customSettings);
        }

        self::$initialized = true;
    }

    /**
     *   Creates new instance by fetching latest Realm info by API static-data endpoint
     * request.
     *
     * @param RiotAPI $api
     * @param array   $customSettings
     */
    public static function initByApi(RiotAPI $api, array $customSettings = [])
    {
        self::initByRealmObject($api->getStaticRealm());

        if (!empty($customSettings)) {
            self::setSettings($customSettings);
        }

        self::$initialized = true;
    }

    /**
     *   Creates new instance from Realm object.
     *
     * @param SRealmDto $realm
     * @param array     $customSettings
     */
    public static function initByRealmObject(SRealmDto $realm, array $customSettings = [])
    {
        self::setSettings([
            self::SET_ENDPOINT => $realm->cdn,
            self::SET_VERSION  => $realm->dd,
        ]);

        if (!empty($customSettings)) {
            self::setSettings($customSettings);
        }

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
    public static function getSetting(string $name, $defaultValue = null)
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
    public static function setSetting(string $name, $value)
    {
        self::$settings[$name] = $value;
    }

    /**
     *   Sets new values for specified set of keys in settings.
     *
     * @param array $values
     */
    public static function setSettings(array $values)
    {
        foreach ($values as $name => $value) {
            self::setSetting($name, $value);
        }
    }

    /**
     *   Checks if specified settings key is set.
     *
     * @param string $name
     *
     * @return bool
     */
    public static function isSettingSet(string $name): bool
    {
        return isset(self::$settings[$name]) && !empty(self::$settings[$name]);
    }

    public static function checkInit()
    {
        if (!self::$initialized) {
            throw new SettingsException('DataDragon class was not initialized - version is potentially unknown.');
        }
    }

    /****************************************d*d*
     *
     *  Available methods
     *
     * @link https://developer.riotgames.com/docs/static-data
     *
     ********************************************/

    /**
     *   Returns profile icon in img HTML TAG.
     *
     * @param int $profile_icon_id
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getProfileIcon(int $profile_icon_id): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_PROFILE_ICON_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/profileicon/{$profile_icon_id}.png",
            'alt'   => 'Profile Icon',
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns profile icon from API static-data Summoner object in img HTML TAG.
     *
     * @param SummonerDto $summoner
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getProfileIconO(SummonerDto $summoner): Html
    {
        return self::getProfileIcon($summoner->profileIconId);
    }

    /**
     *   Returns champion splash in img HTML TAG.
     *
     * @param string $champion_name
     * @param int    $skin
     *
     * @return Html
     */
    public static function getChampionSplash(string $champion_name, int $skin = 0): Html
    {
        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_CHAMP_SPLASH_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT)."img/champion/splash/{$champion_name}_{$skin}.jpg",
            'alt'   => $champion_name,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns champion splash from API static-data Champion object in img HTML TAG.
     *
     * @param SChampionDto $champion
     * @param int          $skin
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getChampionSplashO(SChampionDto $champion, int $skin = 0): Html
    {
        return self::getChampionSplash($champion->key, $skin);
    }

    /**
     *   Returns champion loading screen image in img HTML TAG.
     *
     * @param string $champion_name
     * @param int    $skin
     *
     * @return Html
     */
    public static function getChampionLoading(string $champion_name, int $skin = 0): Html
    {
        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_CHAMP_LOADING_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT)."img/champion/loading/{$champion_name}_{$skin}.jpg",
            'alt'   => $champion_name,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns champion loading screen image from API static-data Champion object in
     * img HTML TAG.
     *
     * @param SChampionDto $champion
     * @param int          $skin
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getChampionLoadingO(SChampionDto $champion, int $skin = 0): Html
    {
        return self::getChampionLoading($champion->key, $skin);
    }

    /**
     *   Returns champion icon in img HTML TAG.
     *
     * @param string $champion_name
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getChampionIcon(string $champion_name): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_CHAMP_ICON_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/champion/{$champion_name}.png",
            'alt'   => $champion_name,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns champion icon from API static-data Champion object in img HTML TAG.
     *
     * @param SChampionDto $champion
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getChampionIconO(SChampionDto $champion): Html
    {
        return self::getChampionIcon($champion->key);
    }

    /**
     *   Returns icon from icon sprite in img HTML TAG.
     *
     * @param string $source
     * @param int    $x
     * @param int    $y
     * @param int    $w
     * @param int    $h
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getFromSprite(string $source, int $x, int $y, int $w = 48, int $h = 48): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_SPRITE_CLASS),
            'style' => 'background: transparent url('.self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/sprite/{$source}".") -{$x}px -{$y}px; width: {$w}px; height: {$h}px;",
            'src'   => 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
            'alt'   => 'Sprite Icon',
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns icon from API static-data ImageDto object in img HTML TAG.
     *
     * @param SImageDto $image
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getFromSpriteO(SImageDto $image): Html
    {
        return self::getFromSprite($image->sprite, $image->x, $image->y, $image->w, $image->h);
    }

    /**
     *   Returns summoner spell icon in img HTML TAG.
     *
     * @param string $spell_name
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getSummonerSpellIcon(string $spell_name): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_SUMSPELL_ICON_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/spell/{$spell_name}.png",
            'alt'   => $spell_name,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns summoner spell icon from API static-data SummonerSpell object in img
     * HTML TAG.
     *
     * @param SSummonerSpellDto $summonerSpell
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getSummonerSpellIconO(SSummonerSpellDto $summonerSpell): Html
    {
        return self::getSummonerSpellIcon($summonerSpell->key);
    }

    /**
     *   Returns item icon in img HTML TAG.
     *
     * @param int $item_id
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getItemIcon(int $item_id): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_ITEM_ICON_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/item/{$item_id}.png",
            'alt'   => $item_id,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns item icon from API static-data Item object in img HTML TAG.
     *
     * @param SItemDto $item
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getItemIconO(SItemDto $item): Html
    {
        return self::getItemIcon($item->id);
    }

    /**
     *   Returns mastery icon in img HTML TAG.
     *
     * @param int $mastery_id
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getMasteryIcon(int $mastery_id): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_MASTERY_ICON_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/mastery/{$mastery_id}.png",
            'alt'   => $mastery_id,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns mastery icon from API static-data Mastery object in img HTML TAG.
     *
     * @param SMasteryDto $mastery
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getMasteryIconO(SMasteryDto $mastery): Html
    {
        return self::getMasteryIcon($mastery->id);
    }

    /**
     *   Returns rune icon in img HTML TAG.
     *
     * @param int $rune_id
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getRuneIcon(int $rune_id): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_RUNE_ICON_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/rune/{$rune_id}.png",
            'alt'   => $rune_id,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns rune icon from API static-data Rune object in img HTML TAG.
     *
     * @param SRuneDto $rune
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getRuneIconO(SRuneDto $rune): Html
    {
        return self::getRuneIcon($rune->id);
    }

    /**
     *   Returns minimap in img HTML TAG.
     *
     * @param int $map_id
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getMinimap(int $map_id): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_MINIMAP_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/map/map{$map_id}.png",
            'alt'   => $map_id,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }

    /**
     *   Returns minimap from API static-data MapDetails object in img HTML TAG.
     *
     * @param SMapDetailsDto $mapDetails
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getMinimapO(SMapDetailsDto $mapDetails): Html
    {
        return self::getMinimap($mapDetails->mapId);
    }

    /**
     *   Returns UI icon in img HTML TAG.
     *
     * @param string $name
     *
     * @throws SettingsException
     *
     * @return Html
     */
    public static function getScoreboardIcon(string $name): Html
    {
        self::checkInit();

        $attrs = array_merge([
            'class' => self::getSetting(self::SET_DEFAULT_CLASS).' '.self::getSetting(self::SET_UI_ICON_CLASS),
            'src'   => self::getSetting(self::SET_ENDPOINT).self::getSetting(self::SET_VERSION)."/img/ui/{$name}.png",
            'alt'   => $name,
        ], self::getSetting(self::SET_CUSTOM_IMG_ATTRS, []));

        return Html::el('img', $attrs);
    }
}
