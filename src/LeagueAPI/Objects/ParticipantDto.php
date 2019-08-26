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
 *   Class ParticipantDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchIdsByTournamentCode
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
 *
 * @linkable getStaticChampion($championId)
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class ParticipantDto extends ApiObjectLinkable
{
	/**
	 *   Participant statistics.
	 *
	 * @var ParticipantStatsDto $stats
	 */
	public $stats;

	/** @var int $participantId */
	public $participantId;

	/**
	 *   List of legacy Rune information. Not included for matches played with 
	 * Runes Reforged.
	 *
	 * @var RuneDto[] $runes
	 */
	public $runes;

	/**
	 *   Participant timeline data.
	 *
	 * @var ParticipantTimelineDto $timeline
	 */
	public $timeline;

	/**
	 *   100 for blue side. 200 for red side.
	 *
	 * @var int $teamId
	 */
	public $teamId;

	/**
	 *   Second Summoner Spell id.
	 *
	 * @var int $spell2Id
	 */
	public $spell2Id;

	/**
	 *   List of legacy Mastery information. Not included for matches played with 
	 * Runes Reforged.
	 *
	 * @var MasteryDto[] $masteries
	 */
	public $masteries;

	/**
	 *   Highest ranked tier achieved for the previous season in a specific subset 
	 * of queueIds, if any, otherwise null. Used to display border in game loading 
	 * screen. Please refer to the Ranked Info documentation. (Legal values: CHALLENGER, 
	 * MASTER, DIAMOND, PLATINUM, GOLD, SILVER, BRONZE, UNRANKED).
	 *
	 * @var string $highestAchievedSeasonTier
	 */
	public $highestAchievedSeasonTier;

	/**
	 *   First Summoner Spell id.
	 *
	 * @var int $spell1Id
	 */
	public $spell1Id;

	/** @var int $championId */
	public $championId;
}
