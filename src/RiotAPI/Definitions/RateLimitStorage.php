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
 *   Class RateLimitStorage
 *
 * @package RiotAPI\Definitions
 */
class RateLimitStorage
{
	/** @var string $api_key */
	protected $api_key;


	/** @var int $called_at */
	public $expires10_at;

	/** @var int $used10 */
	public $used10 = 0;

	/** @var int $limit10 */
	public $limit10 = 10;


	/** @var int $called_at */
	public $expires600_at;

	/** @var int $used600 */
	public $used600 = 0;

	/** @var int $limit600 */
	public $limit600 = 500;


	/**
	 *   RateLimitStorage constructor.
	 *
	 * @param string $api_key
	 */
	public function __construct( string $api_key )
	{
		$this->api_key = $api_key;
	}

	public function __wakeup()
	{
		if ($this->expires10_at < time())
			$this->used10 = 0;

		if ($this->expires600_at < time())
			$this->used600 = 0;
	}

	/**
	 *   Determines whether or not API call can be made
	 *
	 * @return bool
	 */
	public function canCall(): bool
	{
		if ($this->used600 >= $this->limit600 && $this->expires600_at > time())
			//  Long term limit exceeded, no need to check short interval
			return false;

		if ($this->used10 >= $this->limit10 && $this->expires10_at > time())
			//  Long term limit not exceede, but short term limit exceeded - can not make call
			return false;

		//  Let's do it!
		return true;
	}

	/**
	 *   Registers that new API call has been made
	 *
	 * @param string $header
	 */
	public function registerCall( string $header )
	{
		$e = explode(',', $header);
		foreach ($e as $id => $d)
			$e[$id] = explode(':', $d);

		//  10 second timespan
		$this->used10 = intval($e[0][0]);
		$this->limit10 = intval($e[0][1]);
		if ($this->used10 == 1)
			$this->expires10_at = strtotime("+10 seconds");

		//  10 minute timespan
		$this->used600 = intval($e[1][0]);
		$this->limit600 = intval($e[1][1]);
		if ($this->used600 == 1)
			$this->expires600_at = strtotime("+10 minutes");
	}
}