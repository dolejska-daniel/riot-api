<?php

/**
 * Copyright (C) 2016-2020  Daniel Dolejška
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

namespace RiotAPI\LeagueAPI\Definitions;


/**
 *   Interface ICallCacheControl
 *
 * @package RiotAPI\LeagueAPI\Definitions
 */
interface ICallCacheControl
{
	/**
	 *   Checks whether or not is $hash call cached.
	 *
	 * @param string $hash
	 *
	 * @return bool
	 */
	public function isCallCached( string $hash ): bool;

	/**
	 *   Loads cached data for given call.
	 *
	 * @param string $hash
	 *
	 * @return mixed
	 */
	public function loadCallData( string $hash );

	/**
	 *   Saves given data for call.
	 *
	 * @param string $hash
	 * @param        $data
	 * @param int    $length
	 *
	 * @return bool
	 */
	public function saveCallData( string $hash, $data, int $length ): bool;

	/**
	 *   Clears all currently saved data.
	 *
	 * @return bool
	 */
	public function clear(): bool;
}