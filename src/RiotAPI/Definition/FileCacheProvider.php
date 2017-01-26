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

namespace RiotAPI\Definition;

use RiotAPI\Exception\SettingsException;


/**
 *   Class FileCacheProvider
 *
 * @package RiotAPI\Definition
 */
class FileCacheProvider implements ICacheProvider
{
	/** @var string */
	protected $cacheDir;

	/**
	 *   FileCacheProvider constructor.
	 *
	 * @param string $cacheDir
	 *
	 * @throws SettingsException
	 */
	public function __construct( string $cacheDir )
	{
		if (!is_dir($cacheDir) && !@mkdir($cacheDir, 0777, true))
			throw new SettingsException("Provided cache directory path '$cacheDir' is invalid/failed to be created.");
		elseif (!@is_writable($cacheDir))
			throw new SettingsException("Provided cache directory path '$cacheDir' is not writable.");

		$this->cacheDir = realpath($cacheDir);
	}


	/**
	 *   Loads data stored in cache memory.
	 *
	 * @param string $name
	 * @param bool   $returnStorage
	 *
	 * @return mixed
	 * @throws SettingsException
	 */
	public function load( string $name, bool $returnStorage = false )
	{
		$path = $this->cacheDir . DIRECTORY_SEPARATOR . $name;
		$res  = @fopen($path, 'r');

		if ($res == false)
			throw new SettingsException("Loading - Cache file ($path) failed to be opened/created.");

		/** @var FileCacheStorage $storage */
		$storage = @unserialize(fread($res, filesize($path)));

		fclose($res);
		if ($returnStorage)
			return $storage;

		return $storage->data;
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
	public function save( string $name, $data, int $length ): bool
	{
		if ($length <= 0)
			throw new SettingsException("Expiration time has to be greater than 0.");

		$path = $this->cacheDir . DIRECTORY_SEPARATOR . $name;
		$res  = @fopen($path, 'w+');

		if ($res == false)
			throw new SettingsException("Saving - Cache file ($path) failed to be opened/created.");

		$storage = new FileCacheStorage($data, $length);
		$written = fwrite($res, serialize($storage));
		fclose($res);

		return boolval($written);
	}

	/**
	 *   Checks whether or not is saved in cache.
	 *
	 * @param string $name
	 *
	 * @return bool
	 * @throws SettingsException
	 */
	public function isSaved( string $name ): bool
	{
		if (realpath($this->cacheDir . DIRECTORY_SEPARATOR . $name) == false)
			return false;

		/** @var FileCacheStorage $storage */
		$storage = $this->load($name, true);
		return $storage->expires_at > time();
	}
}