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
 *   Class MiniSeriesDto
 * This object contains mini series information.
 *
 * @package RiotAPI\Objects
 */
class MiniSeriesDto extends ApiObject
{
	/**
	 * Number of current losses in the mini series.
	 * @var int $losses
	 */
	public $losses;

	/**
	 * String showing the current, sequential mini series progress where 'W' represents a win, 'L' represents a loss, and 'N'
	 * represents a game that hasn't been played yet.
	 * @var string $progress
	 */
	public $progress;

	/**
	 * Number of wins required for promotion.
	 * @var int $target
	 */
	public $target;

	/**
	 * Number of current wins in the mini series.
	 * @var int $wins
	 */
	public $wins;
}