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

use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\Exceptions\ServerLimitException;
use RiotAPI\LeagueAPI\Extensions\MasteryPagesDtoExtension;
use RiotAPI\LeagueAPI\Objects\IApiObject;
use RiotAPI\LeagueAPI\Objects\MasteryPageDto;
use RiotAPI\LeagueAPI\Objects\MasteryPagesDto;
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;

use RiotAPI\LeagueAPI\Exceptions\SettingsException;


class LiveTest extends TestCase
{
	public function testInit()
	{
		$this->markTestSkipped("Skipping live tests.");

		if (getenv("BUILD_IS_PULL_REQUEST"))
			$this->markTestSkipped("Skipping live tests in PRs.");

		$api = new LeagueAPI([
			LeagueAPI::SET_KEY                => getenv('API_KEY'),
			LeagueAPI::SET_TOURNAMENT_KEY     => getenv('API_TOURNAMENT_KEY'),
			LeagueAPI::SET_REGION             => Region::EUROPE_EAST,
			LeagueAPI::SET_VERIFY_SSL         => false,
			LeagueAPI::SET_CACHE_RATELIMIT    => true,
			LeagueAPI::SET_CACHE_CALLS        => true,
			LeagueAPI::SET_CACHE_CALLS_LENGTH => 600,
			LeagueAPI::SET_USE_DUMMY_DATA     => false,
			LeagueAPI::SET_SAVE_DUMMY_DATA    => true,
		]);

		$this->assertInstanceOf(LeagueAPI::class, $api);

		return $api;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 *
	 * @return LeagueAPI
	 */
	public function testLiveCall(LeagueAPI $api )
	{
		$this->markAsRisky();

		$summoner = $api->getSummonerByName("KuliS");
		$this->assertSame("KuliS", $summoner->name);
		$this->assertSame("Tl4pOlcp2vxCpPp9wVNjTuoMzpb8N6gLgMZiJwOL2JCkdlY", $summoner->id);
		$this->assertSame("R6fx3_ynno6O06vJb2N1EmfsIIIdJsAFctOSkzsvId5QHA", $summoner->accountId);

		return $api;
	}

	/**
	 * @depends testLiveCall
	 */
	public function testLiveCall_cached()
	{
		$this->markAsRisky();

		$api = new LeagueAPI([
			LeagueAPI::SET_KEY         => "INVALID_KEY",
			LeagueAPI::SET_REGION      => Region::EUROPE_EAST,
			LeagueAPI::SET_CACHE_CALLS => true,
			LeagueAPI::SET_CACHE_CALLS_LENGTH => 60,
		]);

		$summoner = $api->getSummonerByName("KuliS");
		$this->assertSame("KuliS", $summoner->name);
		$this->assertSame("Tl4pOlcp2vxCpPp9wVNjTuoMzpb8N6gLgMZiJwOL2JCkdlY", $summoner->id);
		$this->assertSame("R6fx3_ynno6O06vJb2N1EmfsIIIdJsAFctOSkzsvId5QHA", $summoner->accountId);
	}
}
