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
 *   Class RateLimitControl
 *
 * @package RiotAPI\Definitions
 */
class RateLimitControl implements IRateLimitControl
{
	/** @var RateLimitStorage[] $storages */
	protected $storages;


	/**
	 *   Determines whether or not API call can be made
	 *
	 * @param string $api_key
	 *
	 * @return bool
	 */
	public function canCall( string $api_key ): bool
	{
		if (!isset($this->storages[$api_key]))
			$this->storages[$api_key] = new RateLimitStorage($api_key);

		return $this->storages[$api_key]->canCall();
	}

	/**
	 *   Registers that new API call has been made
	 *
	 * @param string $api_key
	 * @param string $header
	 */
	public function registerCall( string $api_key, string $header )
	{
		if (!isset($this->storages[$api_key]))
			$this->storages[$api_key] = new RateLimitStorage($api_key);

		$this->storages[$api_key]->registerCall($header);
	}
}