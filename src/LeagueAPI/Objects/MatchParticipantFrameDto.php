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
 *   Class MatchParticipantFrameDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchTimeline
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class MatchParticipantFrameDto extends ApiObject
{
	/** @var int $participantId */
	public $participantId;

	/** @var int $minionsKilled */
	public $minionsKilled;

	/** @var int $teamScore */
	public $teamScore;

	/** @var int $dominionScore */
	public $dominionScore;

	/** @var int $totalGold */
	public $totalGold;

	/** @var int $level */
	public $level;

	/** @var int $xp */
	public $xp;

	/** @var int $currentGold */
	public $currentGold;

	/** @var MatchPositionDto $position */
	public $position;

	/** @var int $jungleMinionsKilled */
	public $jungleMinionsKilled;
}
