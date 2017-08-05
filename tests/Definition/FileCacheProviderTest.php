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

declare(strict_types=1);

use RiotAPI\Definitions\FileCacheProvider;
use RiotAPI\Definitions\FileCacheStorage;

use RiotAPI\Exceptions\SettingsException;


class FileCacheProviderTest extends RiotAPITestCase
{
	public static $data = [];

	public static $cacheDir;

	public function testInit()
	{
		self::$cacheDir = $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . "cache-fcp" . DIRECTORY_SEPARATOR;
		self::$data = [
			[
				'key1' => "SECRET_API_KEY",
			],
			[
				'key2' => array(
					1,
					2,
					3,
					"i4" => "test",
					"i5" => array(
						"even", "more", "data"
					)
				),
			],
		];

		self::deleteDir(self::$cacheDir);

		$obj = new FileCacheProvider(self::$cacheDir);

		$this->assertInstanceOf(FileCacheProvider::class, $obj);

		return $obj;
	}

	public function testInit_InvalidPath()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("is invalid/failed to be created.");

		new FileCacheProvider("");
	}

	/**
	 * @depends testInit
	 *
	 * @param FileCacheProvider $provider
	 */
	public function testSave( FileCacheProvider $provider )
	{
		foreach (self::$data as $dataList)
			foreach ($dataList as $key => $data)
				$this->assertTrue($provider->save($key, $data, 10));

		$this->assertTrue($provider->save('keyExpired', microtime(), 2));
	}

	/**
	 * @depends testInit
	 *
	 * @param FileCacheProvider $provider
	 */
	public function testSave_InvalidKey( FileCacheProvider $provider )
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("failed to be opened/created.");

		$provider->save("", "", 10);
	}

	/**
	 * @depends testInit
	 *
	 * @param FileCacheProvider $provider
	 */
	public function testSave_InvalidLength( FileCacheProvider $provider )
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Expiration time has to be greater than 0.");

		foreach (self::$data as $dataList)
			foreach ($dataList as $key => $data)
				$provider->save($key, $data, rand(-100, 0));
	}

	/**
	 * @depends testInit
	 * @depends testSave
	 *
	 * @param FileCacheProvider $provider
	 */
	public function testIsSaved( FileCacheProvider $provider )
	{
		foreach (self::$data as $dataList)
			foreach ($dataList as $key => $data)
				$this->assertTrue($provider->isSaved($key));
	}

	/**
	 * @depends testInit
	 *
	 * @param FileCacheProvider $provider
	 */
	public function testIsSaved_InvalidKey( FileCacheProvider $provider )
	{
		$this->assertFalse($provider->isSaved('key99'));
	}

	/**
	 * @depends testInit
	 *
	 * @runInSeparateProcess
	 * @param FileCacheProvider $provider
	 */
	public function testIsSaved_Expired( FileCacheProvider $provider )
	{
		$this->assertTrue($provider->isSaved('keyExpired'));
		while ($provider->isSaved('keyExpired'));
		$this->assertFalse($provider->isSaved('keyExpired'));
	}

	/**
	 * @depends testInit
	 * @depends testIsSaved
	 *
	 * @param FileCacheProvider $provider
	 */
	public function testLoad( FileCacheProvider $provider )
	{
		foreach (self::$data as $dataList)
			foreach ($dataList as $key => $data)
				$this->assertSame($data, $provider->load($key));
	}

	/**
	 * @depends testInit
	 * @depends testIsSaved
	 *
	 * @param FileCacheProvider $provider
	 */
	public function testLoad_ReturnStorage( FileCacheProvider $provider )
	{
		foreach (self::$data as $dataList)
			foreach ($dataList as $key => $data)
			{
				/** @var FileCacheStorage $storage */
				$storage = $provider->load($key, true);

				$this->assertInstanceOf(FileCacheStorage::class, $storage);
				$this->assertSame($data, $storage->data);
			}
	}

	/**
	 * @depends testInit
	 * @depends testIsSaved
	 *
	 * @param FileCacheProvider $provider
	 */
	public function testLoad_InvalidKey( FileCacheProvider $provider )
	{
		$this->assertFalse($provider->load('key99'));
	}
}
