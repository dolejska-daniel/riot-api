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

namespace RiotAPI\Objects;


/**
 *   Class MatchReference
 * This object contains match reference information
 *
 * Used in:
 *   matchlist (v2.2)
 *     @link https://developer.riotgames.com/api/methods#!/1223/4754
 *
 * @package RiotAPI\Objects
 */
class MatchReference extends ApiObject
{
	/** @var int $champion */
	public $champion;

	/**
	 *   Legal values: MID, MIDDLE, TOP, JUNGLE, BOT, BOTTOM.
	 *
	 * @var string $lane
	 */
	public $lane;

	/** @var int $matchId */
	public $matchId;

	/** @var string $platformId */
	public $platformId;

	/**
	 *   Legal values: RANKED_FLEX_SR, RANKED_SOLO_5x5, RANKED_TEAM_3x3, 
	 * RANKED_TEAM_5x5, TEAM_BUILDER_DRAFT_RANKED_5x5, TEAM_BUILDER_RANKED_SOLO.
	 *
	 * @var string $queue
	 */
	public $queue;

	/**
	 *   Legal values: br, eune, euw, jp, kr, lan, las, na, oce, ru, tr.
	 *
	 * @var string $region
	 */
	public $region;

	/**
	 *   Legal values: DUO, NONE, SOLO, DUO_CARRY, DUO_SUPPORT.
	 *
	 * @var string $role
	 */
	public $role;

	/**
	 *   Legal values: PRESEASON3, SEASON3, PRESEASON2014, SEASON2014, 
	 * PRESEASON2015, SEASON2015, PRESEASON2016, SEASON2016, PRESEASON2017, SEASON2017.
	 *
	 * @var string $season
	 */
	public $season;

	/** @var int $timestamp */
	public $timestamp;
}
