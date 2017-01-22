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
 *   Class Participant
 * This object contains match participant information
 *
 * @package RiotAPI\Objects
 */
class Participant extends ApiObject
{
	/** @var bool $bot */
	public $bot;

	/** @var int $championId */
	public $championId;

	/**
	 * Highest ranked tier achieved for the previous season, if any, otherwise null. Used to display border in game loading screen.
	 * (Legal values: CHALLENGER, MASTER, DIAMOND, PLATINUM, GOLD, SILVER, BRONZE, UNRANKED).
	 * @var string $highestAchievedSeasonTier
	 */
	public $highestAchievedSeasonTier;

	/**
	 * List of mastery information.
	 * @var Mastery[] $masteries
	 */
	public $masteries;

	/**
	 * Participant ID.
	 * @var int $participantId
	 */
	public $participantId;

	/** @var int $profileIconId */
	public $profileIconId;

	/**
	 * List of rune information.
	 * @var Rune[] $runes
	 */
	public $runes;

	/** @var int $spell1Id */
	public $spell1Id;

	/** @var int $spell2Id */
	public $spell2Id;

	/**
	 * Participant statistics.
	 * @var ParticipantStats $stats
	 */
	public $stats;

	/** @var string $summonerName */
	public $summonerName;

	/** @var int $teamId */
	public $teamId;

	/**
	 * Timeline data. Delta fields refer to values for the specified period (e.g., the gold per minute over the first 10 minutes
	 * of the game versus the second 20 minutes of the game. Diffs fields refer to the deltas versus the calculated lane opponent(s).
	 * @var ParticipantTimeline $timeline
	 */
	public $timeline;
}