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
use RiotAPI\Objects;
use RiotAPI\RiotAPI;

class ChampionMasteryEndpointObjectTest extends RiotAPITestCase
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
    public function testGetChampionMastery(RiotAPI $api)
    {
        //  Get library processed results
        /** @var Objects\ChampionMasteryDto $result */
        $result = $api->getChampionMastery(30904166, 61);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\ChampionMasteryDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetChampionMasteryList(RiotAPI $api)
    {
        //  Get library processed results
        /** @var Objects\ChampionMasteryDto[] $result */
        $result = $api->getChampionMasteryList(30904166);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\ChampionMasteryDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetChampionMasteryScore(RiotAPI $api)
    {
        //  Get library processed results
        /** @var Objects\ChampionMasteryDto $result */
        $result = $api->getChampionMasteryScore(30904166);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->assertSame($rawResult, $result, 'Data do not match original request result data!');
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetChampionMasteryTopList(RiotAPI $api)
    {
        //  Get library processed results
        /** @var Objects\ChampionMasteryDto[] $result */
        $result = $api->getChampionMasteryTopList(30904166);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\ChampionMasteryDto::class);
    }
}
