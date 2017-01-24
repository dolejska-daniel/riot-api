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
 *   Class PlayerStatsSummaryListDto
 * This object contains a collection of player stats summary information.
 *
 * Used in:
 *   stats (v1.3)
 *     @link https://developer.riotgames.com/api/methods#!/1209/4687
 *
 * @package RiotAPI\Objects
 */
class PlayerStatsSummaryListDto extends ApiObject
{
	/**
	 *   Collection of player stats summaries associated with the summoner.
	 *
	 * @var PlayerStatsSummaryDto[] $playerStatSummaries
	 */
	public $playerStatSummaries;

	/**
	 *   Summoner ID.
	 *
	 * @var int $summonerId
	 */
	public $summonerId;
}
