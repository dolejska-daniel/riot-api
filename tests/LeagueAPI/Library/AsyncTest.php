<?php

/**
 * Copyright (C) 2016-2019  Daniel Dolejška
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

use GuzzleHttp\Client;

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects\SummonerDto;
use RiotAPI\LeagueAPI\Definitions\Region;
use RiotAPI\LeagueAPI\Definitions\AsyncRequest;


class AsyncTestCustomLeagueAPI extends LeagueAPI
{
	/** @var AsyncRequest $next_async_request */
	public $next_async_request;

	/** @var AsyncRequest[] $async_requests */
	public $async_requests;

	/** @var Client[] $async_clients */
	public $async_clients;
}


class AsyncTest extends RiotAPITestCase
{
	const GROUP = "async_group";

	public static $onFulfilledCalls = 0;
	public static $onRejectedCalls = 0;
	public static $summonerList = [];

	public function testInit()
	{
		$api = new AsyncTestCustomLeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA  => true,
			LeagueAPI::SET_SAVE_DUMMY_DATA => getenv('SAVE_DUMMY_DATA') ?? false,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	public function _onFulfilled($data)
	{
		/** @var SummonerDto $data */
		$this->assertInstanceOf(SummonerDto::class, $data);

		self::$onFulfilledCalls++;
		self::$summonerList[] = $data->name;
	}

	public function _onRejected()
	{
		self::$onRejectedCalls++;
	}

	/**
	 * @depends testInit
	 *
	 * @param AsyncTestCustomLeagueAPI $api
	 */
	public function testNextAsync( AsyncTestCustomLeagueAPI $api )
	{
		$this->assertNull($api->next_async_request);
		$this->assertEmpty($api->async_requests);
		$this->assertEmpty($api->async_clients);

		$api->nextAsync([$this, "_onFulfilled"], [$this, "_onRejected"], self::GROUP);
		$this->assertInstanceOf(AsyncRequest::class, $api->next_async_request);
		$this->assertSame($api->next_async_request->onFulfilled, [$this, "_onFulfilled"]);
		$this->assertSame($api->next_async_request->onRejected, [$this, "_onRejected"]);

		$this->assertArrayHasKey(self::GROUP, $api->async_clients);
		$this->assertArrayHasKey(self::GROUP, $api->async_requests);
		$this->assertCount(1, $api->async_requests[self::GROUP]);

		$result = $api->getSummonerByName("I am TheKronnY");
		$this->assertNull($api->next_async_request);
		$this->assertNull($result, "Library method call returned value instead of enqueuing promise.");
	}

	/**
	 * @depends testInit
	 *
	 * @param AsyncTestCustomLeagueAPI $api
	 */
	public function testNextAsync_Extended( AsyncTestCustomLeagueAPI $api )
	{
		$api->nextAsync([$this, "_onFulfilled"], [$this, "_onRejected"], self::GROUP)->getSummonerByName("KuliS");
		$api->nextAsync([$this, "_onFulfilled"], [$this, "_onRejected"], self::GROUP)->getSummonerByName("PeterThePunisher");
		$this->assertCount(3, $api->async_requests[self::GROUP]);
	}

	/**
	 * @depends testInit
	 * @depends testNextAsync
	 *
	 * @param AsyncTestCustomLeagueAPI $api
	 */
	public function testCommitAsync( AsyncTestCustomLeagueAPI $api )
	{
		$this->assertArrayHasKey(self::GROUP, $api->async_clients);
		$this->assertArrayHasKey(self::GROUP, $api->async_requests);
		$this->assertCount(3, $api->async_requests[self::GROUP]);

		$api->commitAsync(self::GROUP);

		$this->assertArrayNotHasKey(self::GROUP, $api->async_clients, "Request client for given async call grop still exists.");
		$this->assertArrayNotHasKey(self::GROUP, $api->async_requests, "Request array for given async call grop still exists.");
		$this->assertEquals(0, self::$onRejectedCalls, "onRejected callback was invoked");
		$this->assertEquals(3, self::$onFulfilledCalls, "onFulfilled callback was not invoked");
		$this->assertCount(3, self::$summonerList);
		$this->assertSame([
			"I am TheKronnY",
			"KuliS",
			"PeterThePunisher",
		], self::$summonerList);
	}


}
