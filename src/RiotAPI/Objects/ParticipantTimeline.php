<?php

/**
 * Copyright (C) 2016  Daniel Dolejška.
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
 * This object contains all timeline information.
 *
 * Used in:
 *   match (v2.2)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 */
class ParticipantTimeline extends ApiObject
{
    /**
     *   Ancient golem assists per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $ancientGolemAssistsPerMinCounts;

    /**
     *   Ancient golem kills per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $ancientGolemKillsPerMinCounts;

    /**
     *   Assisted lane deaths per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $assistedLaneDeathsPerMinDeltas;

    /**
     *   Assisted lane kills per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $assistedLaneKillsPerMinDeltas;

    /**
     *   Baron assists per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $baronAssistsPerMinCounts;

    /**
     *   Baron kills per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $baronKillsPerMinCounts;

    /**
     *   Creeps per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $creepsPerMinDeltas;

    /**
     *   Creep score difference per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $csDiffPerMinDeltas;

    /**
     *   Damage taken difference per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $damageTakenDiffPerMinDeltas;

    /**
     *   Damage taken per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $damageTakenPerMinDeltas;

    /**
     *   Dragon assists per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $dragonAssistsPerMinCounts;

    /**
     *   Dragon kills per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $dragonKillsPerMinCounts;

    /**
     *   Elder lizard assists per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $elderLizardAssistsPerMinCounts;

    /**
     *   Elder lizard kills per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $elderLizardKillsPerMinCounts;

    /**
     *   Gold per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $goldPerMinDeltas;

    /**
     *   Inhibitor assists per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $inhibitorAssistsPerMinCounts;

    /**
     *   Inhibitor kills per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $inhibitorKillsPerMinCounts;

    /**
     *   Participant's lane (Legal values: MID, MIDDLE, TOP, JUNGLE, BOT, BOTTOM).
     *
     * @var string
     */
    public $lane;

    /**
     *   Participant's role (Legal values: DUO, NONE, SOLO, DUO_CARRY,
     * DUO_SUPPORT).
     *
     * @var string
     */
    public $role;

    /**
     *   Tower assists per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $towerAssistsPerMinCounts;

    /**
     *   Tower kills per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $towerKillsPerMinCounts;

    /**
     *   Tower kills per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $towerKillsPerMinDeltas;

    /**
     *   Vilemaw assists per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $vilemawAssistsPerMinCounts;

    /**
     *   Vilemaw kills per minute timeline counts.
     *
     * @var ParticipantTimelineData
     */
    public $vilemawKillsPerMinCounts;

    /**
     *   Wards placed per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $wardsPerMinDeltas;

    /**
     *   Experience difference per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $xpDiffPerMinDeltas;

    /**
     *   Experience per minute timeline data.
     *
     * @var ParticipantTimelineData
     */
    public $xpPerMinDeltas;
}
