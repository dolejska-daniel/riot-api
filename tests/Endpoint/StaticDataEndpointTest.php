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
use RiotAPI\Objects\StaticData;
use RiotAPI\RiotAPI;

class StaticDataEndpointTest extends RiotAPITestCase
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
    public function testGetStaticChampions_ChampionData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SChampionListDto $result */
        $result = $api->getStaticChampions(null, null, null, ['skins', 'image']);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SChampionListDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticChampion_ChampionData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SChampionDto $result */
        $result = $api->getStaticChampion(61, null, null, ['skins', 'image']);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SChampionDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticItems_ItemListData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SItemListDto $result */
        $result = $api->getStaticItems(null, null, ['gold', 'image']);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SItemListDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticItem_ItemListData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SItemDto $result */
        $result = $api->getStaticItem(3089, null, null, ['gold', 'image']); //  RABADON YAY
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SItemDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticLanguageStrings(RiotAPI $api)
    {
        $this->assertTrue(true);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticLanguages(RiotAPI $api)
    {
        $this->assertTrue(true);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticMaps(RiotAPI $api)
    {
        $this->assertTrue(true);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticMasteries_MasteryListData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SMasteryListDto $result */
        $result = $api->getStaticMasteries(null, null, ['masteryTree', 'image']);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SMasteryListDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticMastery_MasteryListData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SMasteryDto $result */
        $result = $api->getStaticMastery(6362, null, null, ['masteryTree', 'image']); //  THE LORD OF THUNDER
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SMasteryDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticRealm(RiotAPI $api)
    {
        $this->assertTrue(true);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticRunes_RuneListData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SRuneListDto $result */
        $result = $api->getStaticRunes(null, null, ['stats', 'image']);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SRuneListDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticRune_RuneListData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SRuneDto $result */
        $result = $api->getStaticRune(5357, null, null, ['stats', 'image']); //  GIMME MOAR AP
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SRuneDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticSummonerSpells_SpellListData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SSummonerSpellListDto $result */
        $result = $api->getStaticSummonerSpells(null, null, false, ['vars', 'image']);
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SSummonerSpellListDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticSummonerSpell_SpellListData(RiotAPI $api)
    {
        //  Get library processed results
        /** @var StaticData\SSummonerSpellDto $result */
        $result = $api->getStaticSummonerSpell(4, null, null, ['vars', 'image']); //  JUST IN CASE?
        //  Get raw result
        $rawResult = $api->getResult();

        $this->checkObjectPropertiesAndDataValidity($result, $rawResult, StaticData\SSummonerSpellDto::class);
    }

    /**
     * @depends      testInit
     * @dataProvider testInit
     *
     * @param RiotAPI $api
     */
    public function testGetStaticVersions(RiotAPI $api)
    {
        $this->assertTrue(true);
    }
}
