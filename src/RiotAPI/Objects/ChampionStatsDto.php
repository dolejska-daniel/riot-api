<?php

/**
 * Copyright (C) 2016  Daniel Dolejška
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
 *   Class ChampionStatsDto
 * This object contains a collection of champion stats information.
 *
 * @package RiotAPI\Objects
 */
class ChampionStatsDto extends ApiObject
{
	/**
	 * Champion ID. Note that champion ID 0 represents the combined stats for all champions. For static information correlating
	 * to champion IDs, please refer to the LoL Static Data API.
	 * @var int $id
	 */
	public $id;

	/**
	 * Aggregated stats associated with the champion.
	 * @var AggregatedStatsDto[] $stats
	 */
	public $stats;
}