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

namespace RiotAPI\Definitions;


/**
 *   Interface ICacheProvider
 *
 * @package RiotAPI\Definition
 */
interface ICacheProvider
{
	/**
	 *   Loads data stored in cache memory.
	 *
	 * @param string $name
	 */
	public function load( string $name );

	/**
	 *   Saves data to cache memory.
	 *
	 * @param string $name
	 * @param        $data
	 * @param int    $length
	 *
	 * @return bool
	 */
	public function save( string $name, $data, int $length): bool;

	/**
	 *   Checks whether or not is saved in cache.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isSaved( string $name ): bool;
}