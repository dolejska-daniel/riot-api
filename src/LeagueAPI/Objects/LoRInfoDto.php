<?php

/**
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
 *   Class LoRInfoDto
 *
 * Used in:
 *   lor-match (v1)
 *     @link https://developer.riotgames.com/apis#lor-match-v1/GET_getMatch
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class LoRInfoDto extends ApiObject
{
	/**
	 *   (Legal values: Constructed, Expeditions, Tutorial)
     *
	 * @var string $game_mode
	 */
	public $game_mode;

	/**
	 *   (Legal values: Ranked, Normal, AI, Tutorial, VanillaTrial, Singleton, StandardGauntlet)
     *
	 * @var string $game_type
	 */
	public $game_type;

	/**
	 * @var string $game_start_time_utc
	 */
	public $game_start_time_utc;

	/**
	 *   Game client version.
	 *
	 * @var string $game_version
	 */
	public $game_version;

	/**
	 * @var LoRPlayerDto[] $players
	 */
	public $players;

	/**
	 *   Total turns taken by both players.
     *
	 * @var int $total_turn_count
	 */
	public $total_turn_count;
}
