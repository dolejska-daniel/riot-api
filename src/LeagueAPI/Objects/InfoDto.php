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
 *   Class InfoDto
 *
 * Used in:
 *   tft-match (v1)
 *     @link https://developer.riotgames.com/apis#tft-match-v1/GET_getMatchIdsByPUUID
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class InfoDto extends ApiObject
{
	/**
	 *   Unix timestamp.
	 *
	 * @var int $game_datetime
	 */
	public $game_datetime;

	/**
	 *   Game length in seconds.
	 *
	 * @var float $game_length
	 */
	public $game_length;

	/**
	 *   Game variation key. Game variations documented in TFT static data.
	 *
	 * @var string $game_variation
	 */
	public $game_variation;

	/**
	 *   Game client version.
	 *
	 * @var string $game_version
	 */
	public $game_version;

	/**
	 *   Participants.
	 *
	 * @var ParticipantDto[] $participants
	 */
	public $participants;

	/**
	 *   Please refer to the League of Legends documentation.
	 *
	 * @var int $queue_id
	 */
	public $queue_id;

	/**
	 *   Teamfight Tactics set number.
	 *
	 * @var int $tft_set_number
	 */
	public $tft_set_number;
}
