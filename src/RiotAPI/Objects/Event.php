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
 *   Class Event
 * This object contains game event information. Note that not all legal type values documented below are valid for all games. Event data evolves over time and certain values may be relevant only for older or newer games.
 *
 * Used in:
 *   match (v2.2)
 *
 *     @link https://developer.riotgames.com/api/methods#!/1224/4756
 */
class Event extends ApiObject
{
    /**
     *   The ascended type of the event. Only present if relevant. Note that
     * CLEAR_ASCENDED refers to when a participants kills the ascended player. (Legal values:
     * CHAMPION_ASCENDED, CLEAR_ASCENDED, MINION_ASCENDED).
     *
     * @var string
     */
    public $ascendedType;

    /**
     *   The assisting participant IDs of the event. Only present if relevant.
     *
     * @var int[]
     */
    public $assistingParticipantIds;

    /**
     *   The building type of the event. Only present if relevant. (Legal values:
     * INHIBITOR_BUILDING, TOWER_BUILDING).
     *
     * @var string
     */
    public $buildingType;

    /**
     *   The creator ID of the event. Only present if relevant.
     *
     * @var int
     */
    public $creatorId;

    /**
     *   Event type. (Legal values: ASCENDED_EVENT, BUILDING_KILL, CAPTURE_POINT,
     * CHAMPION_KILL, ELITE_MONSTER_KILL, ITEM_DESTROYED, ITEM_PURCHASED, ITEM_SOLD, ITEM_UNDO,
     * PORO_KING_SUMMON, SKILL_LEVEL_UP, WARD_KILL, WARD_PLACED).
     *
     * @var string
     */
    public $eventType;

    /**
     *   The ending item ID of the event. Only present if relevant.
     *
     * @var int
     */
    public $itemAfter;

    /**
     *   The starting item ID of the event. Only present if relevant.
     *
     * @var int
     */
    public $itemBefore;

    /**
     *   The item ID of the event. Only present if relevant.
     *
     * @var int
     */
    public $itemId;

    /**
     *   The killer ID of the event. Only present if relevant. Killer ID 0
     * indicates a minion.
     *
     * @var int
     */
    public $killerId;

    /**
     *   The lane type of the event. Only present if relevant. (Legal values:
     * BOT_LANE, MID_LANE, TOP_LANE).
     *
     * @var string
     */
    public $laneType;

    /**
     *   The level up type of the event. Only present if relevant. (Legal values:
     * EVOLVE, NORMAL).
     *
     * @var string
     */
    public $levelUpType;

    /**
     *   The monster subtype of the event. Only present if relevant.
     *
     * @var string
     */
    public $monsterSubType;

    /**
     *   The monster type of the event. Only present if relevant. (Legal values:
     * BARON_NASHOR, BLUE_GOLEM, DRAGON, RED_LIZARD, RIFTHERALD, VILEMAW).
     *
     * @var string
     */
    public $monsterType;

    /**
     *   The participant ID of the event. Only present if relevant.
     *
     * @var int
     */
    public $participantId;

    /**
     *   The point captured in the event. Only present if relevant. (Legal values:
     * POINT_A, POINT_B, POINT_C, POINT_D, POINT_E).
     *
     * @var string
     */
    public $pointCaptured;

    /**
     *   The position of the event. Only present if relevant.
     *
     * @var Position
     */
    public $position;

    /**
     *   The skill slot of the event. Only present if relevant.
     *
     * @var int
     */
    public $skillSlot;

    /**
     *   The team ID of the event. Only present if relevant.
     *
     * @var int
     */
    public $teamId;

    /**
     *   Represents how many milliseconds into the game the event occurred.
     *
     * @var int
     */
    public $timestamp;

    /**
     *   The tower type of the event. Only present if relevant. (Legal values:
     * BASE_TURRET, FOUNTAIN_TURRET, INNER_TURRET, NEXUS_TURRET, OUTER_TURRET,
     * UNDEFINED_TURRET).
     *
     * @var string
     */
    public $towerType;

    /**
     *   The victim ID of the event. Only present if relevant.
     *
     * @var int
     */
    public $victimId;

    /**
     *   The ward type of the event. Only present if relevant. (Legal values:
     * BLUE_TRINKET, SIGHT_WARD, TEEMO_MUSHROOM, UNDEFINED, VISION_WARD, YELLOW_TRINKET,
     * YELLOW_TRINKET_UPGRADE).
     *
     * @var string
     */
    public $wardType;
}
