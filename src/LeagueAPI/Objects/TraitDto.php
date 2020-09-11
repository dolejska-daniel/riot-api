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

namespace RiotAPI\LeagueAPI\Objects;


/**
 *   Class TraitDto
 *
 * Used in:
 *   tft-match (v1)
 *     @link https://developer.riotgames.com/apis#tft-match-v1/GET_getMatchIdsByPUUID
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class TraitDto extends ApiObject
{
	/**
	 *   Trait name.
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 *   Number of units with this trait.
	 *
	 * @var int $num_units
	 */
	public $num_units;

	/**
	 *   Current style for this trait. (0 = No style, 1 = Bronze, 2 = Silver, 3 = 
	 * Gold, 4 = Chromatic).
	 *
	 * @var int $style
	 */
	public $style;

	/**
	 *   Current active tier for the trait.
	 *
	 * @var int $tier_current
	 */
	public $tier_current;

	/**
	 *   Total tiers for the trait.
	 *
	 * @var int $tier_total
	 */
	public $tier_total;
}
