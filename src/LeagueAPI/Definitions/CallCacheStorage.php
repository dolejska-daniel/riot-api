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
 *   Class CallCacheStorage
 *
 * @package RiotAPI\LeagueAPI\Definitions
 */
class CallCacheStorage
{
	/** @var array $cache */
	protected $cache = [];

	/**
	 *   CallCacheStorage constructor.
	 */
	public function __construct() {}

	/**
	 *   Clears all currently saved data.
	 *
	 * @return bool
	 */
	public function clear(): bool
	{
		$this->cache = [];
		return true;
	}


	/**
	 *   Checks whether or not is $hash call cached.
	 *
	 * @param string $hash
	 *
	 * @return bool
	 */
	public function isCached( string $hash ): bool
	{
		if (isset($this->cache[$hash]) == false)
			return false;

		if ($this->cache[$hash]['expires'] < time())
		{
			unset($this->cache[$hash]);
			return false;
		}

		return true;
	}

	/**
	 *   Loads cached data for given call.
	 *
	 * @param string $hash
	 *
	 * @return mixed
	 */
	public function load( string $hash )
	{
		return $this->isCached($hash)
			? $this->cache[$hash]['data']
			: false;
	}

	/**
	 *   Saves given data for call.
	 *
	 * @param string $hash
	 * @param        $data
	 * @param int    $length
	 */
	public function save( string $hash, $data, int $length )
	{
		$this->cache[$hash] = [
			'expires' => time() + $length,
			'data'    => $data,
		];
	}
}