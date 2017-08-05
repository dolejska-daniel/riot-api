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
 *   Class RunePageDto
 * This object contains rune page information.
 *
 * Used in:
 *   runes (v3)
 *     @link https://developer.riotgames.com/api-methods/#runes-v3/GET_getRunePagesBySummonerId
 *
 * @iterable $slots
 *
 * @package RiotAPI\Objects
 */
class RunePageDto extends ApiObjectIterable
{
	/**
	 *   Indicates if the page is the current page.
	 *
	 * @var bool $current
	 */
	public $current;

	/**
	 *   Collection of rune slots associated with the rune page.
	 *
	 * @var RuneSlotDto[] $slots
	 */
	public $slots;

	/**
	 *   Rune page name.
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 *   Rune page ID.
	 *
	 * @var int $id
	 */
	public $id;
}
