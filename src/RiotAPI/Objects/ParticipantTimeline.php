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
 * @package RiotAPI\Objects
 */
class ParticipantTimeline extends ApiObject
{
	/** @var ParticipantTimelineData $ancientGolemAssistsPerMinCounts */
	public $ancientGolemAssistsPerMinCounts;

	/** @var ParticipantTimelineData $ancientGolemKillsPerMinCounts */
	public $ancientGolemKillsPerMinCounts;

	/** @var ParticipantTimelineData $assistedLaneDeathsPerMinDeltas */
	public $assistedLaneDeathsPerMinDeltas;

	/** @var ParticipantTimelineData $assistedLaneKillsPerMinDeltas */
	public $assistedLaneKillsPerMinDeltas;

	/** @var ParticipantTimelineData $baronAssistsPerMinCounts */
	public $baronAssistsPerMinCounts;

	/** @var ParticipantTimelineData $baronKillsPerMinCounts */
	public $baronKillsPerMinCounts;

	/** @var ParticipantTimelineData $creepsPerMinDeltas */
	public $creepsPerMinDeltas;

	/** @var ParticipantTimelineData $csDiffPerMinDeltas */
	public $csDiffPerMinDeltas;

	/** @var ParticipantTimelineData $damageTakenDiffPerMinDeltas */
	public $damageTakenDiffPerMinDeltas;

	/** @var ParticipantTimelineData $damageTakenPerMinDeltas */
	public $damageTakenPerMinDeltas;

	/** @var ParticipantTimelineData $dragonAssistsPerMinCounts */
	public $dragonAssistsPerMinCounts;

	/** @var ParticipantTimelineData $dragonKillsPerMinCounts */
	public $dragonKillsPerMinCounts;

	/** @var ParticipantTimelineData $elderLizardAssistsPerMinCounts */
	public $elderLizardAssistsPerMinCounts;

	/** @var ParticipantTimelineData $elderLizardKillsPerMinCounts */
	public $elderLizardKillsPerMinCounts;

	/** @var ParticipantTimelineData $goldPerMinDeltas */
	public $goldPerMinDeltas;

	/** @var ParticipantTimelineData $inhibitorAssistsPerMinCounts */
	public $inhibitorAssistsPerMinCounts;

	/** @var ParticipantTimelineData $inhibitorKillsPerMinCounts */
	public $inhibitorKillsPerMinCounts;

	/** @var string $lane */
	public $lane;

	/** @var string $role */
	public $role;

	/** @var ParticipantTimelineData $towerAssistsPerMinCounts */
	public $towerAssistsPerMinCounts;

	/** @var ParticipantTimelineData $towerKillsPerMinCounts */
	public $towerKillsPerMinCounts;

	/** @var ParticipantTimelineData $towerKillsPerMinDeltas */
	public $towerKillsPerMinDeltas;

	/** @var ParticipantTimelineData $vilemawAssistsPerMinCounts */
	public $vilemawAssistsPerMinCounts;

	/** @var ParticipantTimelineData $vilemawKillsPerMinCounts */
	public $vilemawKillsPerMinCounts;

	/** @var ParticipantTimelineData $wardsPerMinDeltas */
	public $wardsPerMinDeltas;

	/** @var ParticipantTimelineData $xpDiffPerMinDeltas */
	public $xpDiffPerMinDeltas;

	/** @var ParticipantTimelineData $xpPerMinDeltas */
	public $xpPerMinDeltas;
}