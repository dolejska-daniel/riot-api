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
 *   Class MatchDto
 *
 * Used in:
 *   match (v3)
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatch
 *     @link https://developer.riotgames.com/api-methods/#match-v3/GET_getMatchByTournamentCode
 *
 * @package RiotAPI\Objects
 */
class MatchDto extends ApiObject
{
	/** @var int $seasonId */
	public $seasonId;

	/** @var int $queueId */
	public $queueId;

	/** @var int $gameId */
	public $gameId;

	/** @var ParticipantIdentityDto[] $participantIdentities */
	public $participantIdentities;

	/** @var string $gameVersion */
	public $gameVersion;

	/** @var string $platformId */
	public $platformId;

	/** @var string $gameMode */
	public $gameMode;

	/** @var int $mapId */
	public $mapId;

	/** @var string $gameType */
	public $gameType;

	/** @var TeamStatsDto[] $teams */
	public $teams;

	/** @var ParticipantDto[] $participants */
	public $participants;

	/** @var int $gameDuration */
	public $gameDuration;

	/** @var int $gameCreation */
	public $gameCreation;
}
