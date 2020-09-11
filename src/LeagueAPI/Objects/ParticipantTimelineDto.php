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
 *   Class ParticipantTimelineDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatch
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class ParticipantTimelineDto extends ApiObject
{
	/** @var int $participantId */
	public $participantId;

	/**
	 *   Creep score difference versus the calculated lane opponent(s) for a 
	 * specified period.
	 *
	 * @var float[] $csDiffPerMinDeltas
	 */
	public $csDiffPerMinDeltas;

	/**
	 *   Damage taken for a specified period.
	 *
	 * @var float[] $damageTakenPerMinDeltas
	 */
	public $damageTakenPerMinDeltas;

	/**
	 *   Participant's calculated role. (Legal values: DUO, NONE, SOLO, DUO_CARRY, 
	 * DUO_SUPPORT).
	 *
	 * @var string $role
	 */
	public $role;

	/**
	 *   Damage taken difference versus the calculated lane opponent(s) for a 
	 * specified period.
	 *
	 * @var float[] $damageTakenDiffPerMinDeltas
	 */
	public $damageTakenDiffPerMinDeltas;

	/**
	 *   Experience change for a specified period.
	 *
	 * @var float[] $xpPerMinDeltas
	 */
	public $xpPerMinDeltas;

	/**
	 *   Experience difference versus the calculated lane opponent(s) for a 
	 * specified period.
	 *
	 * @var float[] $xpDiffPerMinDeltas
	 */
	public $xpDiffPerMinDeltas;

	/**
	 *   Participant's calculated lane. MID and BOT are legacy values. (Legal 
	 * values: MID, MIDDLE, TOP, JUNGLE, BOT, BOTTOM).
	 *
	 * @var string $lane
	 */
	public $lane;

	/**
	 *   Creeps for a specified period.
	 *
	 * @var float[] $creepsPerMinDeltas
	 */
	public $creepsPerMinDeltas;

	/**
	 *   Gold for a specified period.
	 *
	 * @var float[] $goldPerMinDeltas
	 */
	public $goldPerMinDeltas;
}
