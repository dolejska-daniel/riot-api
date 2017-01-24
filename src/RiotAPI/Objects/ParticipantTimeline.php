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
 *   Class ParticipantTimeline
 * This object contains all timeline information
 *
 * Used in:
 *   match (v2.2)
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 *
 * @package RiotAPI\Objects
 */
class ParticipantTimeline extends ApiObject
{
	/**
	 *   Ancient golem assists per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $ancientGolemAssistsPerMinCounts
	 */
	public $ancientGolemAssistsPerMinCounts;

	/**
	 *   Ancient golem kills per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $ancientGolemKillsPerMinCounts
	 */
	public $ancientGolemKillsPerMinCounts;

	/**
	 *   Assisted lane deaths per minute timeline data.
	 *
	 * @var ParticipantTimelineData $assistedLaneDeathsPerMinDeltas
	 */
	public $assistedLaneDeathsPerMinDeltas;

	/**
	 *   Assisted lane kills per minute timeline data.
	 *
	 * @var ParticipantTimelineData $assistedLaneKillsPerMinDeltas
	 */
	public $assistedLaneKillsPerMinDeltas;

	/**
	 *   Baron assists per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $baronAssistsPerMinCounts
	 */
	public $baronAssistsPerMinCounts;

	/**
	 *   Baron kills per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $baronKillsPerMinCounts
	 */
	public $baronKillsPerMinCounts;

	/**
	 *   Creeps per minute timeline data.
	 *
	 * @var ParticipantTimelineData $creepsPerMinDeltas
	 */
	public $creepsPerMinDeltas;

	/**
	 *   Creep score difference per minute timeline data.
	 *
	 * @var ParticipantTimelineData $csDiffPerMinDeltas
	 */
	public $csDiffPerMinDeltas;

	/**
	 *   Damage taken difference per minute timeline data.
	 *
	 * @var ParticipantTimelineData $damageTakenDiffPerMinDeltas
	 */
	public $damageTakenDiffPerMinDeltas;

	/**
	 *   Damage taken per minute timeline data.
	 *
	 * @var ParticipantTimelineData $damageTakenPerMinDeltas
	 */
	public $damageTakenPerMinDeltas;

	/**
	 *   Dragon assists per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $dragonAssistsPerMinCounts
	 */
	public $dragonAssistsPerMinCounts;

	/**
	 *   Dragon kills per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $dragonKillsPerMinCounts
	 */
	public $dragonKillsPerMinCounts;

	/**
	 *   Elder lizard assists per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $elderLizardAssistsPerMinCounts
	 */
	public $elderLizardAssistsPerMinCounts;

	/**
	 *   Elder lizard kills per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $elderLizardKillsPerMinCounts
	 */
	public $elderLizardKillsPerMinCounts;

	/**
	 *   Gold per minute timeline data.
	 *
	 * @var ParticipantTimelineData $goldPerMinDeltas
	 */
	public $goldPerMinDeltas;

	/**
	 *   Inhibitor assists per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $inhibitorAssistsPerMinCounts
	 */
	public $inhibitorAssistsPerMinCounts;

	/**
	 *   Inhibitor kills per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $inhibitorKillsPerMinCounts
	 */
	public $inhibitorKillsPerMinCounts;

	/**
	 *   Participant's lane (Legal values: MID, MIDDLE, TOP, JUNGLE, BOT, BOTTOM).
	 *
	 * @var string $lane
	 */
	public $lane;

	/**
	 *   Participant's role (Legal values: DUO, NONE, SOLO, DUO_CARRY, 
	 * DUO_SUPPORT).
	 *
	 * @var string $role
	 */
	public $role;

	/**
	 *   Tower assists per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $towerAssistsPerMinCounts
	 */
	public $towerAssistsPerMinCounts;

	/**
	 *   Tower kills per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $towerKillsPerMinCounts
	 */
	public $towerKillsPerMinCounts;

	/**
	 *   Tower kills per minute timeline data.
	 *
	 * @var ParticipantTimelineData $towerKillsPerMinDeltas
	 */
	public $towerKillsPerMinDeltas;

	/**
	 *   Vilemaw assists per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $vilemawAssistsPerMinCounts
	 */
	public $vilemawAssistsPerMinCounts;

	/**
	 *   Vilemaw kills per minute timeline counts.
	 *
	 * @var ParticipantTimelineData $vilemawKillsPerMinCounts
	 */
	public $vilemawKillsPerMinCounts;

	/**
	 *   Wards placed per minute timeline data.
	 *
	 * @var ParticipantTimelineData $wardsPerMinDeltas
	 */
	public $wardsPerMinDeltas;

	/**
	 *   Experience difference per minute timeline data.
	 *
	 * @var ParticipantTimelineData $xpDiffPerMinDeltas
	 */
	public $xpDiffPerMinDeltas;

	/**
	 *   Experience per minute timeline data.
	 *
	 * @var ParticipantTimelineData $xpPerMinDeltas
	 */
	public $xpPerMinDeltas;
}
