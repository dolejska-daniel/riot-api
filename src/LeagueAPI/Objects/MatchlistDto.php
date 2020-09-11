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
 *   Class MatchlistDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchlist
 *
 * @iterable $matches
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class MatchlistDto extends ApiObjectIterable
{
	/** @var int $startIndex */
	public $startIndex;

	/**
	 *   There is a known issue that this field doesn't correctly return the total 
	 * number of games that match the parameters of the request. Please paginate using 
	 * beginIndex until you reach the end of a player's matchlist.
	 *
	 * @var int $totalGames
	 */
	public $totalGames;

	/** @var int $endIndex */
	public $endIndex;

	/** @var MatchReferenceDto[] $matches */
	public $matches;
}
