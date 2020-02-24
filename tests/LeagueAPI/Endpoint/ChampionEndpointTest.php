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

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects;
use RiotAPI\LeagueAPI\Definitions\Region;


class ChampionEndpointTest extends RiotAPITestCase
{
	public function testInit()
	{
		$api = new LeagueAPI([
			LeagueAPI::SET_KEY             => RiotAPITestCase::getApiKey(),
			LeagueAPI::SET_REGION          => Region::EUROPE_EAST,
			LeagueAPI::SET_USE_DUMMY_DATA  => true,
			LeagueAPI::SET_CACHE_RATELIMIT => true,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetChampionRotations(LeagueAPI $api )
	{
		//  Get library processed results
		/** @var Objects\ChampionInfo $result */
		$result = $api->getChampionRotations();

		$this->assertEquals([16, 45, 50, 62, 64, 78, 89, 90, 127, 131, 154, 203, 222, 223], $result->freeChampionIds);
		$this->assertEquals([18, 81, 92, 141, 37, 238, 19, 45, 25, 64], $result->freeChampionIdsForNewPlayers);
		$this->assertEquals(10, $result->maxNewPlayerLevel);
	}
}
