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
 *   Class MatchList
 * This object contains match list information
 *
 * @package RiotAPI\Objects
 */
class MatchList extends ApiObject
{
	/** @var int $endIndex */
	public $endIndex;

	/** @var MatchReference[] $matches */
	public $matches;

	/** @var int $startIndex */
	public $startIndex;

	/** @var int $totalGames */
	public $totalGames;
}