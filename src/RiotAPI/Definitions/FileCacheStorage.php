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
 *   Class FileCacheStorage
 *
 * @package RiotAPI\Definition
 */
class FileCacheStorage
{
	/** @var int $created_at */
	public $created_at;

	/** @var int $expires_at */
	public $expires_at;

	/** @var mixed $data */
	public $data;


	public function __construct( $data, int $time )
	{
		$this->created_at = time();
		$this->expires_at = time() + $time;
		$this->data = $data;
	}

	public function __sleep()
	{
		return [ 'created_at', 'expires_at', 'data' ];
	}
}