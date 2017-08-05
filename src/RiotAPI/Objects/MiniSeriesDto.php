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
 *   Class MiniSeriesDto
 *
 * Used in:
 *   league (v3)
 *     @link https://developer.riotgames.com/api-methods/#league-v3/GET_getChallengerLeague
 *     @link https://developer.riotgames.com/api-methods/#league-v3/GET_getAllLeaguesForSummoner
 *     @link https://developer.riotgames.com/api-methods/#league-v3/GET_getMasterLeague
 *     @link https://developer.riotgames.com/api-methods/#league-v3/GET_getAllLeaguePositionsForSummoner
 *
 * @package RiotAPI\Objects
 */
class MiniSeriesDto extends ApiObject
{
	/** @var int $wins */
	public $wins;

	/** @var int $losses */
	public $losses;

	/** @var int $target */
	public $target;

	/** @var string $progress */
	public $progress;
}
