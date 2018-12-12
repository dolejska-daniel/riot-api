<?php

/**
 * Copyright (C) 2016-2018  Daniel Dolejška
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
 *   Class ParticipantTimelineDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/api-methods/#match-v4/GET_getMatchIdsByTournamentCode
 *     @link https://developer.riotgames.com/api-methods/#match-v4/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\Objects
 */
class ParticipantTimelineDto extends ApiObject
{
	/**
	 *   Participant's calculated lane. MID and BOT are legacy values. (Legal 
	 * values: MID, MIDDLE, TOP, JUNGLE, BOT, BOTTOM).
	 *
	 * @var string $lane
	 */
	public $lane;

	/** @var int $participantId */
	public $participantId;

	/**
	 *   Creep score difference versus the calculated lane opponent(s) for a 
	 * specified period.
	 *
	 * @var double[] $csDiffPerMinDeltas
	 */
	public $csDiffPerMinDeltas;

	/**
	 *   Gold for a specified period.
	 *
	 * @var double[] $goldPerMinDeltas
	 */
	public $goldPerMinDeltas;

	/**
	 *   Experience difference versus the calculated lane opponent(s) for a 
	 * specified period.
	 *
	 * @var double[] $xpDiffPerMinDeltas
	 */
	public $xpDiffPerMinDeltas;

	/**
	 *   Creeps for a specified period.
	 *
	 * @var double[] $creepsPerMinDeltas
	 */
	public $creepsPerMinDeltas;

	/**
	 *   Experience change for a specified period.
	 *
	 * @var double[] $xpPerMinDeltas
	 */
	public $xpPerMinDeltas;

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
	 * @var double[] $damageTakenDiffPerMinDeltas
	 */
	public $damageTakenDiffPerMinDeltas;

	/**
	 *   Damage taken for a specified period.
	 *
	 * @var double[] $damageTakenPerMinDeltas
	 */
	public $damageTakenPerMinDeltas;
}
