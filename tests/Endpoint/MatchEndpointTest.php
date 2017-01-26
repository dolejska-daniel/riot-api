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
use RiotAPI\RiotAPI;

class MatchEndpointTest extends RiotAPITestCase
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
    public function testGetMatch(RiotAPI $api)
    {
        $this->assertTrue(true);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetTournamentMatch(RiotAPI $api)
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
    public function testGetTournamentMatchIds(RiotAPI $api)
    {
        //  TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
