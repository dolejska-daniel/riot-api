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
 *   Class Event
 * This object contains game event information. Note that not all legal type values documented below are valid for all games.
 * Event data evolves over time and certain values may be relevant only for older or newer games.
 *
 * @package RiotAPI\Objects
 */
class Event extends ApiObject
{
	/** @var string $ascendedType */
	public $ascendedType;

	/** @var int[] $assistingParticipantIds */
	public $assistingParticipantIds;

	/** @var string $buildingType */
	public $buildingType;

	/** @var int $creatorId */
	public $creatorId;

	/** @var string $eventType */
	public $eventType;

	/** @var int $itemAfter */
	public $itemAfter;

	/** @var int $itemBefore */
	public $itemBefore;

	/** @var int $itemId */
	public $itemId;

	/** @var int $killerId */
	public $killerId;

	/** @var string $laneType */
	public $laneType;

	/** @var string $levelUpType */
	public $levelUpType;

	/** @var string $monsterSubType */
	public $monsterSubType;

	/** @var string $monsterType */
	public $monsterType;

	/** @var int $participantId */
	public $participantId;

	/** @var string $pointCaptured */
	public $pointCaptured;

	/** @var Position $position */
	public $position;

	/** @var int $skillSlot */
	public $skillSlot;

	/** @var int $teamId */
	public $teamId;

	/** @var int $timestamp */
	public $timestamp;

	/** @var string $towerType */
	public $towerType;

	/** @var int $victimId */
	public $victimId;

	/** @var string $wardType */
	public $wardType;
}