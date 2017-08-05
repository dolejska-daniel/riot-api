<?php

/**
 * Copyright (C) 2016-2017  Daniel Dolejška
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
 *   Class ParticipantDto
 *
 * Used in:
 *   match (v3)
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatch
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchByTournamentCode
 *
 * @linkable $championId (getStaticChampion)
 *
 * @package RiotAPI\Objects
 */
class ParticipantDto extends ApiObjectLinkable
{
	/** @var ParticipantStatsDto $stats */
	public $stats;

	/** @var int $participantId */
	public $participantId;

	/** @var RuneDto[] $runes */
	public $runes;

	/** @var ParticipantTimelineDto $timeline */
	public $timeline;

	/** @var int $teamId */
	public $teamId;

	/** @var int $spell2Id */
	public $spell2Id;

	/** @var MasteryDto[] $masteries */
	public $masteries;

	/** @var string $highestAchievedSeasonTier */
	public $highestAchievedSeasonTier;

	/** @var int $spell1Id */
	public $spell1Id;

	/** @var int $championId */
	public $championId;
}
