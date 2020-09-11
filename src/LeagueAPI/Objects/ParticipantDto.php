<?php

/**
 * Copyright (C) 2016-2020  Daniel Dolejška
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
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatch
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
 *   tft-match (v1)
 *     @link https://developer.riotgames.com/apis#tft-match-v1/GET_getMatchIdsByPUUID
 *
 * @linkable getStaticChampion($championId)
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class ParticipantDto extends ApiObjectLinkable
{
	/** @var int $participantId */
	public $participantId;

	/** @var int $championId */
	public $championId;

	/**
	 *   List of legacy Rune information. Not included for matches played with 
	 * Runes Reforged.
	 *
	 * @var RuneDto[] $runes
	 */
	public $runes;

	/**
	 *   Participant statistics.
	 *
	 * @var ParticipantStatsDto $stats
	 */
	public $stats;

	/**
	 *   100 for blue side. 200 for red side.
	 *
	 * @var int $teamId
	 */
	public $teamId;

	/**
	 *   Participant timeline data.
	 *
	 * @var ParticipantTimelineDto $timeline
	 */
	public $timeline;

	/**
	 *   First Summoner Spell id.
	 *
	 * @var int $spell1Id
	 */
	public $spell1Id;

	/**
	 *   Second Summoner Spell id.
	 *
	 * @var int $spell2Id
	 */
	public $spell2Id;

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
	 *   List of legacy Mastery information. Not included for matches played with 
	 * Runes Reforged.
	 *
	 * @var MasteryDto[] $masteries
	 */
	public $masteries;

	/**
	 *   Participant's companion.
	 *
	 * @var CompanionDto $companion
	 */
	public $companion;

	/**
	 *   Gold left after participant was eliminated.
	 *
	 * @var int $gold_left
	 */
	public $gold_left;

	/**
	 *   The round the participant was eliminated in. Note: If the player was 
	 * eliminated in stage 2-1 their last_round would be 5.
	 *
	 * @var int $last_round
	 */
	public $last_round;

	/**
	 *   Participant Little Legend level. Note: This is not the number of active 
	 * units.
	 *
	 * @var int $level
	 */
	public $level;

	/**
	 *   Participant placement upon elimination.
	 *
	 * @var int $placement
	 */
	public $placement;

	/**
	 *   Number of players the participant eliminated.
	 *
	 * @var int $players_eliminated
	 */
	public $players_eliminated;

	/**
	 *   Encrypted PUUID.
	 *
	 * @var string $puuid
	 */
	public $puuid;

	/**
	 *   The number of seconds before the participant was eliminated.
	 *
	 * @var float $time_eliminated
	 */
	public $time_eliminated;

	/**
	 *   Damage the participant dealt to other players.
	 *
	 * @var int $total_damage_to_players
	 */
	public $total_damage_to_players;

	/**
	 *   A complete list of traits for the participant's active units.
	 *
	 * @var TraitDto[] $traits
	 */
	public $traits;

	/**
	 *   A list of active units for the participant.
	 *
	 * @var UnitDto[] $units
	 */
	public $units;
}
