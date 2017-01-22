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

use RiotAPI\Exceptions\SettingsException;


/**
 *   Class MemcachedCacheProvider
 *
 * @package RiotAPI\Definitions
 */
class MemcachedCacheProvider
{
	const MEMCACHED_ID = 'RiotAPI\CacheProvider';

	/** @var \Memcached */
	protected $memcached;

	/**
	 *   MemcachedCacheProvider constructor.
	 *
	 * @param array $servers
	 *
	 * @throws SettingsException
	 */
	public function __construct( array $servers = array() )
	{
		$this->memcached = new \Memcached(self::MEMCACHED_ID);

		if (!empty($servers))
			$this->memcached->addServers($servers);
		else
			$this->memcached->addServer('127.0.0.1', 11211);
	}


	/**
	 *   Loads data stored in cache memory.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 * @throws SettingsException
	 */
	public function load( string $name )
	{
		return $this->memcached->get($name);
	}

	/**
	 *   Saves data to cache memory.
	 *
	 * @param string $name
	 * @param        $data
	 * @param int    $length
	 *
	 * @return bool
	 * @throws SettingsException
	 */
	public function save( string $name, $data, int $length): bool
	{
		return $this->memcached->set($name, $data, $length);
	}

	/**
	 *   Checks whether or not is saved in cache.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isSaved( string $name ): bool
	{
		return $this->load($name) !== false || $this->memcached->getResultCode() !== \Memcached::RES_NOTFOUND;
	}
}