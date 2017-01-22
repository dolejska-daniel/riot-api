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

namespace DataDragonAPI;

use Nette\Utils\Html;


class DataDragonAPI
{
	/** Settings constants. */
	const
		SET_ENDPOINT            = 'region',
		SET_VERSION             = 'version',
		SET_DEFAULT_CLASS       = 'default_icon_class',
		SET_PROFILE_ICON_CLASS  = 'class-profile',
		SET_MASTERY_ICON_CLASS  = 'class-mastery',
		SET_RUNE_ICON_CLASS     = 'class-rune',
		SET_CHAMP_SPLASH_CLASS  = 'class-champ_splash',
		SET_CHAMP_LOADING_CLASS = 'class-champ_loading',
		SET_CHAMP_ICON_CLASS    = 'class-champ_icon',
		SET_SPRITE_CLASS        = 'class-champ_icon_sprite',
		SET_SUMSPELL_ICON_CLASS = 'class-summoner_spell',
		SET_ITEM_ICON_CLASS     = 'class-item',
		SET_UI_ICON_CLASS       = 'class-scoreboard',
		SET_MINIMAP_CLASS       = 'class-minimap';


	/**
	 *   Contains library settings.
	 * 
	 * @var $settings array
	 */
	protected $settings = array(
		self::SET_ENDPOINT            => "http://ddragon.leagueoflegends.com/cdn/",
		self::SET_DEFAULT_CLASS       => "dd-icon",
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
	);


	/**
	 *   DataDragonAPI constructor.
	 *
	 * @param array $settings
	 *
	 * @throws Exceptions\GeneralException
	 */
	public function __construct( array $settings )
	{
		$required_settings = [
			self::SET_ENDPOINT,
			self::SET_VERSION,
		];

		//  Checks if required settings are present
		foreach ($required_settings as $key)
			if (array_search($key, array_keys($settings), true) === false)
				throw new Exceptions\GeneralException("Required settings parameter '$key' was not specified!");

		$allowed_settings = array_merge([
			self::SET_DEFAULT_CLASS,
			self::SET_PROFILE_ICON_CLASS,
			self::SET_MASTERY_ICON_CLASS,
			self::SET_RUNE_ICON_CLASS,
			self::SET_CHAMP_SPLASH_CLASS,
			self::SET_CHAMP_LOADING_CLASS,
			self::SET_CHAMP_ICON_CLASS,
			self::SET_SPRITE_CLASS,
			self::SET_SUMSPELL_ICON_CLASS,
			self::SET_ITEM_ICON_CLASS,
			self::SET_UI_ICON_CLASS,
			self::SET_MINIMAP_CLASS,
		], $required_settings);

		//  Assigns allowed settings
		foreach ($allowed_settings as $key)
			if (array_search($key, array_keys($settings), true) !== false)
				$this->settings[$key] = $settings[$key];
	}


	/**
	 *   Returns profile icon in img HTML TAG.
	 *
	 * @param int $profile_icon_id
	 *
	 * @return Html
	 */
	public function getProfileIcon( int $profile_icon_id ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_PROFILE_ICON_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/profileicon/{$profile_icon_id}.png",
			'alt'   => 'Profile Icon'
		);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns champion splash in img HTML TAG.
	 *
	 * @param string $champion_name
	 * @param int    $skin
	 *
	 * @return Html
	 */
	public function getChampionSplash( string $champion_name, int $skin = 0 ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_CHAMP_SPLASH_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . "img/champion/splash/{$champion_name}_{$skin}.jpg",
			'alt'   => $champion_name
		);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns champion loading screen image in img HTML TAG.
	 *
	 * @param string $champion_name
	 * @param int    $skin
	 *
	 * @return Html
	 */
	public function getChampionLoading( string $champion_name, int $skin = 0 ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_CHAMP_LOADING_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . "img/champion/loading/{$champion_name}_{$skin}.jpg",
			'alt'   => $champion_name
		);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns champion icon in img HTML TAG.
	 *
	 * @param string $champion_name
	 *
	 * @return Html
	 */
	public function getChampionIcon( string $champion_name ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_CHAMP_ICON_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/champion/{$champion_name}.png",
			'alt'   => $champion_name
		);

		return Html::el('img', $attrs);
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
	 * @return Html
	 */
	public function getFromSprite( string $source, int $x, int $y, int $w = 48, int $h = 48 ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_SPRITE_CLASS]}",
			'style' => 'background: transparent url(' . $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/sprite/{$source}" . ") -{$x}px -{$y}px; width: {$w}px; height: {$h}px;",
			'alt'   => 'Sprite Icon'
		);
		
		return Html::el('img', $attrs);
	}

	/**
	 *   Returns summoner spell icon in img HTML TAG.
	 *
	 * @param string $spell_name
	 *
	 * @return Html
	 */
	public function getSummonerSpellIcon( string $spell_name ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_SUMSPELL_ICON_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/spell/{$spell_name}.png",
			'alt'   => $spell_name
		);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns item icon in img HTML TAG.
	 *
	 * @param int $item_id
	 *
	 * @return Html
	 */
	public function getItemIcon( int $item_id ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_ITEM_ICON_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/item/{$item_id}.png",
			'alt'   => $item_id
		);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns mastery icon in img HTML TAG.
	 *
	 * @param int $mastery_id
	 *
	 * @return Html
	 */
	public function getMasteryIcon( int $mastery_id ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_MASTERY_ICON_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/mastery/{$mastery_id}.png",
			'alt'   => $mastery_id
		);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns rune icon in img HTML TAG.
	 *
	 * @param int $rune_id
	 *
	 * @return Html
	 */
	public function getRuneIcon( int $rune_id ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_RUNE_ICON_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/rune/{$rune_id}.png",
			'alt'   => $rune_id
		);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns minimap in img HTML TAG.
	 *
	 * @param int $map_id
	 *
	 * @return Html
	 */
	public function getMinimap( int $map_id ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_MINIMAP_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/map/map{$map_id}.png",
			'alt'   => $map_id
		);

		return Html::el('img', $attrs);
	}

	/**
	 *   Returns UI icon in img HTML TAG.
	 *
	 * @param string $name
	 *
	 * @return Html
	 */
	public function getScoreboardIcon( string $name ): Html
	{
		$attrs = array(
			'class' => "{$this->settings[self::SET_ENDPOINT]} {$this->settings[self::SET_UI_ICON_CLASS]}",
			'src'   => $this->settings[self::SET_ENDPOINT] . $this->settings[self::SET_VERSION] . "/img/ui/{$name}.png",
			'alt'   => $name
		);

		return Html::el('img', $attrs);
	}
}