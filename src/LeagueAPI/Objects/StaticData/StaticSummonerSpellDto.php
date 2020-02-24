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
 *   Class StaticSummonerSpellDto
 * This object contains summoner spell data.
 *
 * @package RiotAPI\LeagueAPI\Objects\StaticData
 */
class StaticSummonerSpellDto extends ApiObject
{
	/** @var StaticSpellVarsDto[] $vars */
	public $vars;

	/** @var StaticImageDto $image */
	public $image;

	/** @var string $costBurn */
	public $costBurn;

	/** @var double[] $cooldown */
	public $cooldown;

	/** @var string[] $effectBurn */
	public $effectBurn;

	/** @var int $id */
	public $id;

	/** @var string $cooldownBurn */
	public $cooldownBurn;

	/** @var string $tooltip */
	public $tooltip;

	/** @var int $maxrank */
	public $maxrank;

	/** @var string $rangeBurn */
	public $rangeBurn;

	/** @var string $description */
	public $description;

	/**
	 *   This field is a List of List of Double.
	 *
	 * @var int[][] $effect
	 */
	public $effect;

	/** @var string $key */
	public $key;

	/** @var StaticLevelTipDto $leveltip */
	public $leveltip;

	/** @var string[] $modes */
	public $modes;

	/** @var string $resource */
	public $resource;

	/** @var string $name */
	public $name;

	/** @var string $costType */
	public $costType;

	/**
	 *   This field is either a List of Integer or the String 'self' for spells 
	 * that target one's own champion.
	 *
	 * @var int[] $range
	 */
	public $range;

	/** @var int[] $cost */
	public $cost;

	/** @var int $summonerLevel */
	public $summonerLevel;
}
