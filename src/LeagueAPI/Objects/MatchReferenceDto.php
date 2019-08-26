<?php

/**
 * Copyright (C) 2016-2019  Daniel Dolejška
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
 *   Class MatchReferenceDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchlist
 *
 * @linkable getStaticChampion($champion)
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class MatchReferenceDto extends ApiObjectLinkable
{
	/** @var string $lane */
	public $lane;

	/** @var int $gameId */
	public $gameId;

	/** @var int $champion */
	public $champion;

	/** @var string $platformId */
	public $platformId;

	/** @var int $season */
	public $season;

	/** @var int $queue */
	public $queue;

	/** @var string $role */
	public $role;

	/** @var int $timestamp */
	public $timestamp;
}
