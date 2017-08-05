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

namespace RiotAPI\Objects;


/**
 *   Class MatchReferenceDto
 *
 * Used in:
 *   match (v3)
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchlist
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getRecentMatchlist
 *
 * @linkable $champion (getStaticChampion)
 *
 * @package RiotAPI\Objects
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
