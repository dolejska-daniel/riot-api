<?php

/**
 * Copyright (C) 2016  Daniel Dolejška.
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

use RiotAPI\Definition\Region;
use RiotAPI\Exception\RequestParameterException;
use RiotAPI\RiotAPI;

class LeagueEndpointTest extends RiotAPITestCase
{
    public function testInit()
    {
        $api = new RiotAPI([
            RiotAPI::SET_KEY            => getenv('API_KEY'),
            RiotAPI::SET_REGION         => Region::EUROPE_EAST,
            RiotAPI::SET_USE_DUMMY_DATA => true,
        ]);

        $this->assertInstanceOf(RiotAPI::class, $api);

        return $api;
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetLeagueMappingBySummoners_Exception(RiotAPI $api)
    {
        $this->expectException(RequestParameterException::class);
        $this->expectExceptionMessage('Maximum allowed summoner ID count is 10.');

        $summonerIds = range(30904166, 30904266);
        $api->getLeagueMappingBySummoners($summonerIds);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetLeagueMappingBySummoner(RiotAPI $api)
    {
        $this->assertTrue(true);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetLeagueEntryBySummoners_Exception(RiotAPI $api)
    {
        $this->expectException(RequestParameterException::class);
        $this->expectExceptionMessage('Maximum allowed summoner ID count is 10.');

        $summonerIds = range(30904166, 30904266);
        $api->getLeagueEntryBySummoners($summonerIds);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetLeagueEntryBySummoner(RiotAPI $api)
    {
        $this->assertTrue(true);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetLeagueMappingChallenger(RiotAPI $api)
    {
        $this->assertTrue(true);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetLeagueMappingMaster(RiotAPI $api)
    {
        $this->assertTrue(true);
    }
}
