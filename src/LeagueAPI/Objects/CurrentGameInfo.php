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
 *   Class CurrentGameInfo
 *
 * Used in:
 *   spectator (v4)
 *     @link https://developer.riotgames.com/apis#spectator-v4/GET_getCurrentGameInfoBySummoner
 *
 * @iterable $participants
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class CurrentGameInfo extends ApiObjectIterable
{
	/**
	 *   The ID of the game.
	 *
	 * @var int $gameId
	 */
	public $gameId;

	/**
	 *   The game type.
	 *
	 * @var string $gameType
	 */
	public $gameType;

	/**
	 *   The game start time represented in epoch milliseconds.
	 *
	 * @var int $gameStartTime
	 */
	public $gameStartTime;

	/**
	 *   The ID of the map.
	 *
	 * @var int $mapId
	 */
	public $mapId;

	/**
	 *   The amount of time in seconds that has passed since the game started.
	 *
	 * @var int $gameLength
	 */
	public $gameLength;

	/**
	 *   The ID of the platform on which the game is being played.
	 *
	 * @var string $platformId
	 */
	public $platformId;

	/**
	 *   The game mode.
	 *
	 * @var string $gameMode
	 */
	public $gameMode;

	/**
	 *   Banned champion information.
	 *
	 * @var BannedChampion[] $bannedChampions
	 */
	public $bannedChampions;

	/**
	 *   The queue type (queue types are documented on the Game Constants page).
	 *
	 * @var int $gameQueueConfigId
	 */
	public $gameQueueConfigId;

	/**
	 *   The observer information.
	 *
	 * @var Observer $observers
	 */
	public $observers;

	/**
	 *   The participant information.
	 *
	 * @var CurrentGameParticipant[] $participants
	 */
	public $participants;
}
