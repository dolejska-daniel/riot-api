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
 *   Class UnitDto
 *
 * Used in:
 *   tft-match (v1)
 *     @link https://developer.riotgames.com/apis#tft-match-v1/GET_getMatchIdsByPUUID
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class UnitDto extends ApiObject
{
	/**
	 *   A list of the unit's items. Please refer to the Teamfight Tactics 
	 * documentation for item ids.
	 *
	 * @var int[] $items
	 */
	public $items;

	/**
	 *   This field was introduced in patch 9.22 with data_version 2.
	 *
	 * @var string $character_id
	 */
	public $character_id;

	/**
	 *   Unit name.
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 *   Unit rarity. This doesn't equate to the unit cost.
	 *
	 * @var int $rarity
	 */
	public $rarity;

	/**
	 *   Unit tier.
	 *
	 * @var int $tier
	 */
	public $tier;
}
