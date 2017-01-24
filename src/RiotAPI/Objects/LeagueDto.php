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
 *   Class LeagueDto
 * This object contains league information.
 *
 * Used in:
 *   league (v2.5)
 *     @link https://developer.riotgames.com/api/methods#!/1215/4701
 *     @link https://developer.riotgames.com/api/methods#!/1215/4705
 *     @link https://developer.riotgames.com/api/methods#!/1215/4704
 *     @link https://developer.riotgames.com/api/methods#!/1215/4706
 *
 * @package RiotAPI\Objects
 */
class LeagueDto extends ApiObject
{
	/**
	 *   The requested league entries.
	 *
	 * @var LeagueEntryDto[] $entries
	 */
	public $entries;

	/**
	 *   This name is an internal place-holder name only. Display and localization 
	 * of names in the game client are handled client-side.
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 *   Specifies the relevant participant that is a member of this league (i.e., 
	 * a requested summoner ID, a requested team ID, or the ID of a team to which 
	 * one of the requested summoners belongs). Only present when full league is 
	 * requested so that participant's entry can be identified. Not present when individual 
	 * entry is requested.
	 *
	 * @var string $participantId
	 */
	public $participantId;

	/**
	 *   The league's queue type. (Legal values: RANKED_FLEX_SR, RANKED_FLEX_TT, 
	 * RANKED_SOLO_5x5, RANKED_TEAM_3x3, RANKED_TEAM_5x5).
	 *
	 * @var string $queue
	 */
	public $queue;

	/**
	 *   The league's tier. (Legal values: CHALLENGER, MASTER, DIAMOND, PLATINUM, 
	 * GOLD, SILVER, BRONZE).
	 *
	 * @var string $tier
	 */
	public $tier;
}
