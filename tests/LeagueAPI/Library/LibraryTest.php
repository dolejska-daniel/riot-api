<?php

/**
 * Copyright (C) 2016-2020  Daniel Dolejška
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

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;
use RiotAPI\LeagueAPI\Definitions\Platform;
use RiotAPI\LeagueAPI\Exceptions\RequestException;
use RiotAPI\LeagueAPI\Exceptions\ServerException;
use RiotAPI\LeagueAPI\Exceptions\ServerLimitException;
use RiotAPI\LeagueAPI\Exceptions\SettingsException;

use Symfony\Component\Cache\Adapter\MemcachedAdapter;


class LibraryTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY            => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION         => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA => true,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	public function testInit_cachingDefaults()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_TOURNAMENT_KEY  => RiotAPITestCase::getApiTournamentKey(),
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_CACHE_RATELIMIT => true,
			LeagueAPI::SET_CACHE_CALLS     => true,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);
	}

	public function testInit_customDataProviders()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
		], new Region(), new Platform());

		$this->assertInstanceOf(LeagueAPI::class, $api);
	}

	public function testInit_settings_invalid_missingRequired()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("is missing!");

		new LeagueAPI([]);
	}

	public function testInit_settings_invalid_keyIncludeType()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("is not valid.");

		new LeagueAPI([
			LeagueAPI::SET_KEY              => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION           => Region::EUROPE_EAST,
			LeagueAPI::SET_KEY_INCLUDE_TYPE => 'THIS_IS_INVALID_INCLUDE_TYPE',
		]);
	}

	public function testInit_settings_invalid_cacheProvider()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Provided CacheProvider does not implement Psr\Cache\CacheItemPoolInterface (PSR-6)");

		new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_CACHE_RATELIMIT => true,
			LeagueAPI::SET_CACHE_PROVIDER  => new stdClass(),
		]);
	}

	public function testInit_settings_invalid_cacheProvider_uninstantiable()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Failed to initialize CacheProvider class:");

		new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_CACHE_RATELIMIT => true,
			LeagueAPI::SET_CACHE_PROVIDER  => "Orianna",
		]);
	}

	/**
	 * @requires extension memcached
	 */
	public function testInit_settings_invalid_cacheProviderSettings()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("CacheProvider class failed to be initialized:");

		new LeagueAPI([
			LeagueAPI::SET_KEY                   => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION                => Region::EUROPE_EAST,
			LeagueAPI::SET_CACHE_RATELIMIT       => true,
			LeagueAPI::SET_CACHE_PROVIDER        => MemcachedAdapter::class,
			LeagueAPI::SET_CACHE_PROVIDER_PARAMS => [ '' ],
		]);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testChangeRegion(LeagueAPI $api )
	{
		$this->assertSame(Region::EUROPE_EAST, $api->getSetting(LeagueAPI::SET_REGION));
		$api->setRegion(Region::EUROPE_WEST);
		$this->assertSame(Region::EUROPE_WEST, $api->getSetting(LeagueAPI::SET_REGION));
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testChangeSettings_single(LeagueAPI $api )
	{
		$this->assertSame(RiotAPITestCase::getApiKey(), $api->getSetting(LeagueAPI::SET_KEY));
		$api->setSetting(LeagueAPI::SET_KEY, "NOT_REALLY_A_API_KEY");
		$this->assertSame("NOT_REALLY_A_API_KEY", $api->getSetting(LeagueAPI::SET_KEY));
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testChangeSettings_array(LeagueAPI $api )
	{
		$api->setSettings([
			LeagueAPI::SET_KEY    => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION => Region::EUROPE_EAST,
		]);
		$this->assertSame(RiotAPITestCase::getApiKey(), $api->getSetting(LeagueAPI::SET_KEY));
		$this->assertSame(Region::EUROPE_EAST, $api->getSetting(LeagueAPI::SET_REGION));
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testChangeSettings_initOnly(LeagueAPI $api )
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("can only be set on initialization of the library");

		$api->setSetting(LeagueAPI::SET_API_BASEURL, "http://google.com");
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
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_503(LeagueAPI $api )
	{
		$this->expectException(ServerException::class);
		$this->expectExceptionMessage("LeagueAPI: Service is temporarily unavailable.");

		$api->makeTestEndpointCall(503);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_500(LeagueAPI $api )
	{
		$this->expectException(ServerException::class);
		$this->expectExceptionMessage("LeagueAPI: Internal server error occured.");

		$api->makeTestEndpointCall(500);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_429(LeagueAPI $api )
	{
		$this->expectException(ServerLimitException::class);
		$this->expectExceptionMessage("LeagueAPI: Rate limit for this API key was exceeded.");

		$api->makeTestEndpointCall(429);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_415(LeagueAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("LeagueAPI: Unsupported media type.");

		$api->makeTestEndpointCall(415);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_404(LeagueAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("LeagueAPI: Not Found.");

		$api->makeTestEndpointCall(404);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_403(LeagueAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("LeagueAPI: Forbidden.");

		$api->makeTestEndpointCall(403);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_401(LeagueAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("LeagueAPI: Unauthorized.");

		$api->makeTestEndpointCall(401);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_400(LeagueAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("LeagueAPI: Request is invalid.");

		$api->makeTestEndpointCall(400);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_4xx(LeagueAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("LeagueAPI: Unspecified error occured");

		$api->makeTestEndpointCall(498);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_test_Versions(LeagueAPI $api )
	{
		$data = $api->makeTestEndpointCall('versions');

		$this->assertSame($data, $api->getResult());
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_test_PUT(LeagueAPI $api )
	{
		$data = $api->makeTestEndpointCall('put', null, LeagueAPI::METHOD_PUT);

		$this->assertSame($data, $api->getResult());
	}

	public function testCurlException()
	{
		$this->expectException(RequestException::class);

		$api = new LeagueAPI([
			LeagueAPI::SET_KEY         => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION      => Region::EUROPE_EAST,
			LeagueAPI::SET_API_BASEURL => '.invalid.api.url.riotgames.com',
		]);

		$api->makeTestEndpointCall('versions');
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_NoDummyData(LeagueAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("No DummyData available for call.");

		$api->makeTestEndpointCall("no-dummy-data");
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testMakeCall_DummyDataEmpty(LeagueAPI $api )
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage("No DummyData available for call.");

		$api->makeTestEndpointCall("empty");
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testSaveDummyData(LeagueAPI $api )
	{
		$api->setSetting(LeagueAPI::SET_SAVE_DUMMY_DATA, false);

		try
		{
			$api->makeTestEndpointCall("save");
		}
		catch (RequestException $ex) {}

		$this->assertFileNotExists($api->_getDummyDataFileName());
		$api->_saveDummyData();
		$this->assertFileExists($api->_getDummyDataFileName(), "DummyData file was not created correctly.");

		// Removes the dummy data file on subsequent runs
		if (file_exists($api->_getDummyDataFileName()))
			unlink($api->_getDummyDataFileName());
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testDestruct(LeagueAPI $api )
	{
		$api->__destruct();
		$this->assertTrue(true);
	}
}
