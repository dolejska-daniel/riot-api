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

class SummonerEndpointObjectTest extends RiotAPITestCase
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
    public function testGetSummonersByName(RiotAPI $api)
    {
        $summonerNames = [
            'IMQ',
            'KuliS',
            'PeterThePunisher',
            'crewbeat',
        ];
        foreach ($summonerNames as $key => $name) {
            $summonerNames[$key] = str_replace(' ', '', strtolower($name));
        }

        //  Get library processed results
        /** @var Objects\SummonerDto[] $result */
        $result = $api->getSummonersByName($summonerNames);
        //  Get raw result
        $rawResult = $api->getResult();

        //  Check result validity
        $this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\SummonerDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummonerByName(RiotAPI $api)
    {
        $summonerName = 'I am TheKronnY';
        $summonerName = str_replace(' ', '', strtolower($summonerName));

        //  Get library processed results
        /** @var Objects\SummonerDto $result */
        $result = $api->getSummonerByName($summonerName);
        //  Get raw result
        $rawResult = $api->getResult();

        //  Check if all the data were successfully processed
        $this->assertArrayHasKey($summonerName, $rawResult);
        $rawResult = $rawResult[$summonerName];

        //  Check result validity
        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\SummonerDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummoners(RiotAPI $api)
    {
        $summonerIds = [
            19827622,
            32473526,
            34937794,
            36615528,
        ];

        //  Get library processed results
        /** @var Objects\SummonerDto[] $result */
        $result = $api->getSummoners($summonerIds);
        //  Get raw result
        $rawResult = $api->getResult();

        //  Check result validity
        $this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\SummonerDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummoner(RiotAPI $api)
    {
        $summonerId = 30904166;

        //  Get library processed results
        /** @var Objects\SummonerDto $result */
        $result = $api->getSummoner($summonerId);
        //  Get raw result
        $rawResult = $api->getResult();

        //  Check if all the data were successfully processed
        $this->assertArrayHasKey($summonerId, $rawResult);
        $rawResult = $rawResult[$summonerId];

        //  Check result validity
        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\SummonerDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummonersMasteries(RiotAPI $api)
    {
        $summonerIds = [
            19827622,
            32473526,
            34937794,
            36615528,
        ];

        //  Get library processed results
        /** @var Objects\MasteryPagesDto[] $result */
        $result = $api->getSummonersMasteries($summonerIds);
        //  Get raw result
        $rawResult = $api->getResult();

        //  Check result validity
        $this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\MasteryPagesDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummonerMasteries(RiotAPI $api)
    {
        $summonerId = 30904166;

        //  Get library processed results
        /** @var Objects\MasteryPagesDto $result */
        $result = $api->getSummonerMasteries($summonerId);
        //  Get raw result
        $rawResult = $api->getResult();

        //  Check if all the data were successfully processed
        $this->assertArrayHasKey($summonerId, $rawResult);
        $rawResult = $rawResult[$summonerId];

        //  Check result validity
        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\MasteryPagesDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummonersNames(RiotAPI $api)
    {
        $summonerIds = [
            19827622,
            32473526,
            34937794,
            36615528,
        ];

        //  Get library processed results
        /** @var string[] $result */
        $result = $api->getSummonersNames($summonerIds);
        //  Get raw results
        $rawResult = $api->getResult();

        //  Check if all the data were successfully processed
        $this->assertSame($rawResult, $result);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummonerName(RiotAPI $api)
    {
        $summonerId = 30904166;

        //  Get library processed results
        /** @var string $result */
        $result = $api->getSummonerName($summonerId);
        //  Get raw results
        $rawResult = $api->getResult();

        //  Check if all the data were successfully processed
        $this->assertArrayHasKey($summonerId, $rawResult);

        //  Check data validity
        $this->assertSame($rawResult[$summonerId], $result);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummonersRunes(RiotAPI $api)
    {
        $summonerIds = [
            19827622,
            32473526,
            34937794,
            36615528,
        ];

        //  Get library processed results
        /** @var Objects\RunePagesDto[] $result */
        $result = $api->getSummonersRunes($summonerIds);
        //  Get raw results
        $rawResult = $api->getResult();

        //  Check result validity
        $this->checkObjectPropertiesAndDataValidityOfObjectList($result, $rawResult, Objects\RunePagesDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetSummonerRunes(RiotAPI $api)
    {
        $summonerId = 30904166;

        //  Get library processed results
        /** @var Objects\RunePagesDto $result */
        $result = $api->getSummonerRunes($summonerId);
        //  Get raw results
        $rawResult = $api->getResult();

        //  Check if all the data were successfully processed
        $this->assertArrayHasKey($summonerId, $rawResult);
        $rawResult = $rawResult[$summonerId];

        //  Check result validity
        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, Objects\RunePagesDto::class);
    }
}
