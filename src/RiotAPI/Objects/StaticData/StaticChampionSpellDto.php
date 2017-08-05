<?php

/**
 * Copyright (C) 2016-2017  Daniel Dolejška
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

namespace RiotAPI\Objects\StaticData;

use RiotAPI\Objects\ApiObject;


/**
 *   Class StaticChampionSpellDto
 * This object contains champion spell data.
 *
 * Used in:
 *   lol-static-data (v3)
 *     @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getChampionList
 *     @link https://developer.riotgames.com/api-methods/#lol-static-data-v3/GET_getChampionById
 *
 * @package RiotAPI\Objects\StaticData
 */
class StaticChampionSpellDto extends ApiObject
{
	/** @var string $cooldownBurn */
	public $cooldownBurn;

	/** @var string $resource */
	public $resource;

	/** @var StaticLevelTipDto $leveltip */
	public $leveltip;

	/** @var StaticSpellVarsDto[] $vars */
	public $vars;

	/** @var string $costType */
	public $costType;

	/** @var StaticImageDto $image */
	public $image;

	/** @var string $sanitizedDescription */
	public $sanitizedDescription;

	/** @var string $sanitizedTooltip */
	public $sanitizedTooltip;

	/**
	 *   This field is a List of List of Double.
	 *
	 * @var Staticobject[] $effect
	 */
	public $effect;

	/** @var string $tooltip */
	public $tooltip;

	/** @var int $maxrank */
	public $maxrank;

	/** @var string $costBurn */
	public $costBurn;

	/** @var string $rangeBurn */
	public $rangeBurn;

	/**
	 *   This field is either a List of Integer or the String 'self' for spells 
	 * that target one's own champion.
	 *
	 * @var Staticobject $range
	 */
	public $range;

	/** @var double[] $cooldown */
	public $cooldown;

	/** @var int[] $cost */
	public $cost;

	/** @var string $key */
	public $key;

	/** @var string $description */
	public $description;

	/** @var string[] $effectBurn */
	public $effectBurn;

	/** @var StaticImageDto[] $altimages */
	public $altimages;

	/** @var string $name */
	public $name;
}
