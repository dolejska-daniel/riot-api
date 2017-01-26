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
 *   Class ParticipantFrame
 * This object contains participant frame information.
 *
 * Used in:
 *   match (v2.2)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 */
class ParticipantFrame extends ApiObject
{
    /**
     *   Participant's current gold.
     *
     * @var int
     */
    public $currentGold;

    /**
     *   Dominion score of the participant.
     *
     * @var int
     */
    public $dominionScore;

    /**
     *   Number of jungle minions killed by participant.
     *
     * @var int
     */
    public $jungleMinionsKilled;

    /**
     *   Participant's current level.
     *
     * @var int
     */
    public $level;

    /**
     *   Number of minions killed by participant.
     *
     * @var int
     */
    public $minionsKilled;

    /**
     *   Participant ID.
     *
     * @var int
     */
    public $participantId;

    /**
     *   Participant's position.
     *
     * @var Position
     */
    public $position;

    /**
     *   Team score of the participant.
     *
     * @var int
     */
    public $teamScore;

    /**
     *   Participant's total gold.
     *
     * @var int
     */
    public $totalGold;

    /**
     *   Experience earned by participant.
     *
     * @var int
     */
    public $xp;
}
