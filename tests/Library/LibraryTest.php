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
use RiotAPI\Definitions\Region;
use RiotAPI\Definitions\Platform;
use RiotAPI\Definitions\IRateLimitControl;

use RiotAPI\Exceptions\SettingsException;


class LibraryTest extends TestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY    => getenv('API_KEY'),
			RiotAPI::SET_REGION => Region::EUROPE_EAST,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return [
			[ $api ],
		];
	}

	public function testInit_cachingDefaults()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY             => getenv('API_KEY'),
			RiotAPI::SET_TOURNAMENT_KEY  => getenv('TOURNAMENT_API_KEY'),
			RiotAPI::SET_REGION          => Region::EUROPE_EAST,
			RiotAPI::SET_CACHE_RATELIMIT => true,
			RiotAPI::SET_CACHE_CALLS     => true,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);
	}

	public function testInit_customDataProviders()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY             => getenv('API_KEY'),
			RiotAPI::SET_REGION          => Region::EUROPE_EAST,
		], new Region(), new Platform());

		$this->assertInstanceOf(RiotAPI::class, $api);
	}

	public function get_invalidRatelimits()
	{
		return [
			[
				[
					getenv('API_KEY') => 30,
				]
			],
			[
				[
					getenv('API_KEY') => [
						IRateLimitControl::INTERVAL_1S => "ASD",
						IRateLimitControl::INTERVAL_10S => 50,
					],
				]
			],
			[
				[
					getenv('API_KEY') => [
						IRateLimitControl::INTERVAL_1S => 10,
						IRateLimitControl::INTERVAL_10S => 50,
					],
					getenv('TOURNAMENT_API_KEY') => [
						IRateLimitControl::INTERVAL_1S => 10,
						IRateLimitControl::INTERVAL_10S => "ASD",
					],
				]
			],
		];
	}

	public function testInit_settings_missingRequired()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("is missing!");

		new RiotAPI([]);
	}

	public function testInit_settings_invalid_keyIncludeType()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("is not valid.");

		new RiotAPI([
			RiotAPI::SET_KEY              => getenv('API_KEY'),
			RiotAPI::SET_REGION           => Region::EUROPE_EAST,
			RiotAPI::SET_KEY_INCLUDE_TYPE => 'THIS_IS_INVALID_INCLUDE_TYPE',
		]);
	}

	public function testInit_settings_invalid_cacheProvider()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Provided CacheProvider does not implement ICacheProvider interface.");

		new RiotAPI([
			RiotAPI::SET_KEY             => getenv('API_KEY'),
			RiotAPI::SET_REGION          => Region::EUROPE_EAST,
			RiotAPI::SET_CACHE_RATELIMIT => true,
			RiotAPI::SET_CACHE_PROVIDER  => new stdClass(),
		]);
	}

	public function testInit_settings_invalid_cacheProvider_uninstantiable()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Failed to initialize CacheProvider class:");

		new RiotAPI([
			RiotAPI::SET_KEY             => getenv('API_KEY'),
			RiotAPI::SET_REGION          => Region::EUROPE_EAST,
			RiotAPI::SET_CACHE_RATELIMIT => true,
			RiotAPI::SET_CACHE_PROVIDER  => "Orianna",
		]);
	}

	public function testFileCacheProviderSettings()
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testInit_settings_invalid_cacheProviderSettings()
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
	 * @dataProvider get_invalidRatelimits
	 *
	 * @param array $ratelimits
	 */
	public function testInit_settings_invalid_rateLimits( array $ratelimits )
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Rate limit settings are not in valid format.");

		new RiotAPI([
			RiotAPI::SET_KEY             => getenv('API_KEY'),
			RiotAPI::SET_REGION          => Region::EUROPE_EAST,
			RiotAPI::SET_CACHE_RATELIMIT => true,
			RiotAPI::SET_CACHE_PROVIDER  => RiotAPI::CACHE_PROVIDER_FILE,
			RiotAPI::SET_RATELIMITS      => $ratelimits,
		]);
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testChangeRegion( RiotAPI $api )
	{
		$this->assertSame(Region::EUROPE_EAST, $api->getSetting(RiotAPI::SET_REGION));
		$api->setRegion(Region::EUROPE_WEST);
		$this->assertSame(Region::EUROPE_WEST, $api->getSetting(RiotAPI::SET_REGION));
	}

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testChangeSettings_single( RiotAPI $api )
	{
		$this->assertSame(getenv('API_KEY'), $api->getSetting(RiotAPI::SET_KEY));
		$api->setSetting(RiotAPI::SET_KEY, "NOT_REALLY_A_API_KEY");
		$this->assertSame("NOT_REALLY_A_API_KEY", $api->getSetting(RiotAPI::SET_KEY));
	}

	/**
	 * @depends      testInit
	 * @depends      testChangeRegion
	 * @depends      testChangeSettings_single
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testChangeSettings_array( RiotAPI $api )
	{
		$api->setSettings([
			RiotAPI::SET_KEY    => getenv('API_KEY'),
			RiotAPI::SET_REGION => Region::EUROPE_EAST,
		]);
		$this->assertSame(getenv('API_KEY'), $api->getSetting(RiotAPI::SET_KEY));
		$this->assertSame(Region::EUROPE_EAST, $api->getSetting(RiotAPI::SET_REGION));
	}

	public function testParseHeaders()
	{
		$headers = "HTTP/1.1 200 OK" . PHP_EOL . "Accept: text/plain" . PHP_EOL . "X-Powered-By: PHP/5.4.0" . PHP_EOL . "";
		$array = RiotAPI::parseHeaders($headers);

		//  We want no empty key/values here
		$this->assertNotSameSize(explode(PHP_EOL, $headers), $array);
		$this->assertSameSize(explode(PHP_EOL, trim($headers)), $array);

		$this->assertArrayHasKey(0, $array);
		$this->assertSame('HTTP/1.1 200 OK', $array[0]);

		$this->assertArrayHasKey('Accept', $array);
		$this->assertSame('text/plain', $array['Accept']);

		$this->assertArrayHasKey('X-Powered-By', $array);
		$this->assertSame('PHP/5.4.0', $array['X-Powered-By']);
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
			RiotAPI::SET_CACHE_PROVIDER_PARAMS => [[ '', 0 ]],
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

	/**
	 * @depends      testInit
	 * @dataProvider testInit
	 *
	 * @param RiotAPI $api
	 */
	public function testDestruct( RiotAPI $api )
	{
		//  TODO
		$this->markTestIncomplete('This test has not been implemented yet.');

		$api->__destruct();
	}
}
