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
 *   Class MasteryPageDto
 * This object contains mastery page information.
 *
 * Used in:
 *   masteries (v3)
 *     @link https://developer.riotgames.com/api-methods/#masteries-v3/GET_getMasteryPagesBySummonerId
 *
 * @iterable $masteries
 *
 * @package RiotAPI\Objects
 */
class MasteryPageDto extends ApiObjectIterable
{
	/**
	 *   Indicates if the mastery page is the current mastery page.
	 *
	 * @var bool $current
	 */
	public $current;

	/**
	 *   Collection of masteries associated with the mastery page.
	 *
	 * @var MasteryDto[] $masteries
	 */
	public $masteries;

	/**
	 *   Mastery page name.
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 *   Mastery page ID.
	 *
	 * @var int $id
	 */
	public $id;
}
