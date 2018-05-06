<?php

/**
 * Copyright (C) 2016-2018  Daniel Dolejška
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

use RiotAPI\Exceptions\GeneralException;
use RiotAPI\Exceptions\ServerLimitException;
use RiotAPI\Extensions\MasteryPagesDtoExtension;
use RiotAPI\Objects\IApiObject;
use RiotAPI\Objects\MasteryPageDto;
use RiotAPI\Objects\MasteryPagesDto;
use RiotAPI\RiotAPI;
use RiotAPI\Definitions\Region;

use RiotAPI\Exceptions\SettingsException;


class LiveTest extends TestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY                => getenv('API_KEY'),
			RiotAPI::SET_TOURNAMENT_KEY     => getenv('API_TOURNAMENT_KEY'),
			RiotAPI::SET_REGION             => Region::EUROPE_EAST,
			RiotAPI::SET_VERIFY_SSL         => false,
			RiotAPI::SET_CACHE_RATELIMIT    => true,
			RiotAPI::SET_CACHE_CALLS        => true,
			RiotAPI::SET_CACHE_CALLS_LENGTH => 600,
			RiotAPI::SET_USE_DUMMY_DATA     => false,
			RiotAPI::SET_SAVE_DUMMY_DATA    => true,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param RiotAPI $api
	 *
	 * @return RiotAPI
	 */
	public function testLiveCall( RiotAPI $api )
	{
		$this->markAsRisky();

		$summoner = $api->getSummonerByName("KuliS");
		$this->assertSame("KuliS", $summoner->name);
		$this->assertSame(32473526, $summoner->id);
		$this->assertSame(35079181, $summoner->accountId);

		return $api;
	}

	/**
	 * @depends testLiveCall
	 */
	public function testLiveCall_cached()
	{
		$this->markAsRisky();

		$api = new RiotAPI([
			RiotAPI::SET_KEY         => "INVALID_KEY",
			RiotAPI::SET_REGION      => Region::EUROPE_EAST,
			RiotAPI::SET_CACHE_CALLS => true,
			RiotAPI::SET_CACHE_CALLS_LENGTH => 60,
		]);

		$summoner = $api->getSummonerByName("KuliS");
		$this->assertSame("KuliS", $summoner->name);
		$this->assertSame(32473526, $summoner->id);
		$this->assertSame(35079181, $summoner->accountId);
	}
}
