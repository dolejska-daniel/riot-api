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

use RiotAPI\Exceptions\SettingsException;


/**
 *   Class MemcachedCacheProvider
 *
 * @package RiotAPI\Definition
 */
class MemcachedCacheProvider implements ICacheProvider
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
		if (!class_exists('Memcached'))
			throw new SettingsException('Class Memcached not found, is your Memcached ');

		$this->memcached = $m =  new \Memcached(self::MEMCACHED_ID);

		$m->setOption(\Memcached::OPT_CONNECT_TIMEOUT, 10);
		$m->setOption(\Memcached::OPT_DISTRIBUTION, \Memcached::DISTRIBUTION_CONSISTENT);
		$m->setOption(\Memcached::OPT_SERVER_FAILURE_LIMIT, 2);
		$m->setOption(\Memcached::OPT_REMOVE_FAILED_SERVERS, true);
		$m->setOption(\Memcached::OPT_RETRY_TIMEOUT, 1);

		if (empty($servers))
			$servers = array(
				[ '127.0.0.1', 11211 ],
			);

		if (!$m->addServers($servers))
			throw new SettingsException('Memcached servers failed to be added.');

		$fails = 0;
		$statuses = $m->getStats();
		foreach ($servers as $s)
			if (!isset($statuses[$s[0] . ":" . $s[1]]) || $statuses[$s[0] . ":" . $s[1]]['pid'] <= 0)
				$fails++;

		if ($fails == count($servers))
			throw new SettingsException('Could not connect to any of specified Memcached servers.');
		elseif ($fails > 0); // TODO: Warning to be issued?
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