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
 *   Class MatchEventDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchTimeline
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class MatchEventDto extends ApiObject
{
	/** @var string $eventType */
	public $eventType;

	/** @var string $towerType */
	public $towerType;

	/** @var int $teamId */
	public $teamId;

	/** @var string $ascendedType */
	public $ascendedType;

	/** @var int $killerId */
	public $killerId;

	/** @var string $levelUpType */
	public $levelUpType;

	/** @var string $pointCaptured */
	public $pointCaptured;

	/** @var int[] $assistingParticipantIds */
	public $assistingParticipantIds;

	/** @var string $wardType */
	public $wardType;

	/** @var string $monsterType */
	public $monsterType;

	/**
	 *   (Legal values: CHAMPION_KILL, WARD_PLACED, WARD_KILL, BUILDING_KILL, 
	 * ELITE_MONSTER_KILL, ITEM_PURCHASED, ITEM_SOLD, ITEM_DESTROYED, ITEM_UNDO, SKILL_LEVEL_UP, 
	 * ASCENDED_EVENT, CAPTURE_POINT, PORO_KING_SUMMON).
	 *
	 * @var string $type
	 */
	public $type;

	/** @var int $skillSlot */
	public $skillSlot;

	/** @var int $victimId */
	public $victimId;

	/** @var int $timestamp */
	public $timestamp;

	/** @var int $afterId */
	public $afterId;

	/** @var string $monsterSubType */
	public $monsterSubType;

	/** @var string $laneType */
	public $laneType;

	/** @var int $itemId */
	public $itemId;

	/** @var int $participantId */
	public $participantId;

	/** @var string $buildingType */
	public $buildingType;

	/** @var int $creatorId */
	public $creatorId;

	/** @var MatchPositionDto $position */
	public $position;

	/** @var int $beforeId */
	public $beforeId;
}
