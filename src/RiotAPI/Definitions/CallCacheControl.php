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

namespace RiotAPI\Definitions;


/**
 *   Class CallCacheControl
 *
 * @package RiotAPI\Definition
 */
class CallCacheControl implements ICallCacheControl
{
	/** @var array $storage */
	protected $storage;

	/**
	 *   CallCacheControl constructor.
	 *
	 * @param IRegion $region
	 */
	public function __construct( IRegion $region )
	{
		$this->storage = new CallCacheStorage($region);
	}

	/**
	 *   Checks whether or not is $hash call cached.
	 *
	 * @param string $hash
	 *
	 * @return bool
	 */
	public function isCallCached( string $hash ): bool
	{
		return $this->storage->isCached($hash);
	}

	/**
	 *   Loads cached data for given call.
	 *
	 * @param string $hash
	 *
	 * @return mixed
	 */
	public function loadCallData( string $hash )
	{
		return $this->storage->load($hash);
	}

	/**
	 *   Saves given data for call.
	 *
	 * @param string $hash
	 * @param        $data
	 * @param int    $lenght
	 */
	public function saveCallData( string $hash, $data, int $lenght )
	{
		$this->storage->save($hash, $data, $lenght);
	}
}