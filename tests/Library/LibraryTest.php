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


class LibraryTest extends TestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY    => getenv('API_KEY'),
			RiotAPI::SET_REGION => Region::EUROPE_EAST,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	public function testRequiredSettings()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Required settings parameter");

		new RiotAPI([]);
	}

	public function testParseHeaders()
	{
		$headers = "HTTP/1.1 200 OK
Accept: text/plain
X-Powered-By: PHP/5.4.0
";
		$array = RiotAPI::parseHeaders($headers);

		//  We want no empty key/values here
		$this->assertNotSameSize(explode(PHP_EOL, $headers), $array);
		$this->assertSameSize(explode(PHP_EOL, trim($headers)), $array);

		$this->assertArrayHasKey(0, $array);
		$this->assertSame('HTTP/1.1 200 OK', $array[0]);

		$this->assertArrayHasKey('Accept', $array);
		$this->assertSame('text/plain', $array['Accept']);
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
		$this->expectExceptionMessage("is invalid/failed to be created.");

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

	public function testMakeCall_InvalidMethod()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_503()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_500()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_429()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_415()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_404()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_403()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_401()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_400()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testMakeCall_4xx()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
