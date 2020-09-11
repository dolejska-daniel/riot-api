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
 *   Class MatchDto
 *
 * Used in:
 *   match (v4)
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatch
 *     @link https://developer.riotgames.com/apis#match-v4/GET_getMatchByTournamentCode
 *   tft-match (v1)
 *     @link https://developer.riotgames.com/apis#tft-match-v1/GET_getMatchIdsByPUUID
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class MatchDto extends ApiObject
{
	/** @var int $gameId */
	public $gameId;

	/**
	 *   Participant identity information. Participant identity information is 
	 * purposefully excluded for custom games.
	 *
	 * @var ParticipantIdentityDto[] $participantIdentities
	 */
	public $participantIdentities;

	/**
	 *   Please refer to the Game Constants documentation.
	 *
	 * @var int $queueId
	 */
	public $queueId;

	/**
	 *   Please refer to the Game Constants documentation.
	 *
	 * @var string $gameType
	 */
	public $gameType;

	/**
	 *   Match duration in seconds.
	 *
	 * @var int $gameDuration
	 */
	public $gameDuration;

	/**
	 *   Team information.
	 *
	 * @var TeamStatsDto[] $teams
	 */
	public $teams;

	/**
	 *   Platform where the match was played.
	 *
	 * @var string $platformId
	 */
	public $platformId;

	/**
	 *   Designates the timestamp when champion select ended and the loading screen 
	 * appeared, NOT when the game timer was at 0:00.
	 *
	 * @var int $gameCreation
	 */
	public $gameCreation;

	/**
	 *   Please refer to the Game Constants documentation.
	 *
	 * @var int $seasonId
	 */
	public $seasonId;

	/**
	 *   The major.minor version typically indicates the patch the match was played 
	 * on.
	 *
	 * @var string $gameVersion
	 */
	public $gameVersion;

	/**
	 *   Please refer to the Game Constants documentation.
	 *
	 * @var int $mapId
	 */
	public $mapId;

	/**
	 *   Please refer to the Game Constants documentation.
	 *
	 * @var string $gameMode
	 */
	public $gameMode;

	/**
	 *   Participant information.
	 *
	 * @var ParticipantDto[] $participants
	 */
	public $participants;

	/**
	 *   Match metadata.
	 *
	 * @var MetadataDto $metadata
	 */
	public $metadata;

	/**
	 *   Match info.
	 *
	 * @var InfoDto $info
	 */
	public $info;
}
