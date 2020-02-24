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

namespace RiotAPI\LeagueAPI\Objects\StaticData;

use RiotAPI\LeagueAPI\Objects\ApiObject;


/**
 *   Class StaticChampionSpellDto
 * This object contains champion spell data.
 *
 * @package RiotAPI\LeagueAPI\Objects\StaticData
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

	/**
	 *   This field is a List of List of Double.
	 *
	 * @var int[][] $effect
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
	 * @var int[] $range
	 */
	public $range;

	/** @var double[] $cooldown */
	public $cooldown;

	/** @var int[] $cost */
	public $cost;

	/** @var string $id */
	public $id;

	/** @var string $description */
	public $description;

	/** @var string[] $effectBurn */
	public $effectBurn;

	/** @var string $name */
	public $name;
}
