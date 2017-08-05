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
 *   Class ChampionDto
 * This object contains champion information.
 *
 * Used in:
 *   champion (v3)
 *     @link https://developer.riotgames.com/api-methods/#champion-v3/GET_getChampions
 *     @link https://developer.riotgames.com/api-methods/#champion-v3/GET_getChampionsById
 *
 * @linkable $id (getStaticChampion)
 *
 * @package RiotAPI\Objects
 */
class ChampionDto extends ApiObjectLinkable
{
	/**
	 *   Ranked play enabled flag.
	 *
	 * @var bool $rankedPlayEnabled
	 */
	public $rankedPlayEnabled;

	/**
	 *   Bot enabled flag (for custom games).
	 *
	 * @var bool $botEnabled
	 */
	public $botEnabled;

	/**
	 *   Bot Match Made enabled flag (for Co-op vs. AI games).
	 *
	 * @var bool $botMmEnabled
	 */
	public $botMmEnabled;

	/**
	 *   Indicates if the champion is active.
	 *
	 * @var bool $active
	 */
	public $active;

	/**
	 *   Indicates if the champion is free to play. Free to play champions are 
	 * rotated periodically.
	 *
	 * @var bool $freeToPlay
	 */
	public $freeToPlay;

	/**
	 *   Champion ID. For static information correlating to champion IDs, please 
	 * refer to the LoL Static Data API.
	 *
	 * @var int $id
	 */
	public $id;
}
