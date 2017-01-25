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

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use RiotAPI\RiotAPI;
use RiotAPI\Definition\Region;

use RiotAPI\Exception\SettingsException;


class LibraryInitTests extends TestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY    => getenv('API_KEY'),
			RiotAPI::SET_REGION => Region::EUROPE_EAST,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);
	}

	public function testRequiredSettings()
	{
		$this->expectException(SettingsException::class);

		new RiotAPI([]);
	}

	public function testCustomRegionDataProvider()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testCustomPlatformDataProvider()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testInvalidCacheProvider()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Failed to initialize CacheProvider class: Class Yasuo does not exist.");

		new RiotAPI([
			RiotAPI::SET_KEY             => getenv('API_KEY'),
			RiotAPI::SET_REGION          => Region::EUROPE_EAST,
			RiotAPI::SET_CACHE_RATELIMIT => true,
			RiotAPI::SET_CACHE_PROVIDER  => "Yasuo",
		]);
	}

	public function testFileCacheProviderSettings()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testFileCacheProviderInvalidSettings()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Provided cache directory path '' is not writable.");

		new RiotAPI([
			RiotAPI::SET_KEY                   => getenv('API_KEY'),
			RiotAPI::SET_REGION                => Region::EUROPE_EAST,
			RiotAPI::SET_CACHE_RATELIMIT       => true,
			RiotAPI::SET_CACHE_PROVIDER        => RiotAPI::CACHE_PROVIDER_FILE,
			RiotAPI::SET_CACHE_PROVIDER_PARAMS => [ '' ],
		]);
	}

	/**
	 * @requires extension memcached
	 */
	public function testMemcachedCacheProviderSettings()
	{
		if (!extension_loaded('memcached'))
			$this->markTestSkipped('The Memcached PHP extension is not available.');

		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @requires extension memcached
	 */
	public function testMemcachedCacheProviderInvalidSettings()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Memcached servers failed to be added.");

		new RiotAPI([
			RiotAPI::SET_KEY                   => getenv('API_KEY'),
			RiotAPI::SET_REGION                => Region::EUROPE_EAST,
			RiotAPI::SET_CACHE_RATELIMIT       => true,
			RiotAPI::SET_CACHE_PROVIDER        => RiotAPI::CACHE_PROVIDER_MEMCACHED,
			RiotAPI::SET_CACHE_PROVIDER_PARAMS => [[ '',0 ]],
		]);
	}
}
