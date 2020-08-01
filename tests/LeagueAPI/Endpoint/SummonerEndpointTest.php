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

use RiotAPI\LeagueAPI\Exceptions\RequestParameterException;
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects;
use RiotAPI\LeagueAPI\Definitions\Region;


class SummonerEndpointTest extends RiotAPITestCase
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

	public static function randomString( $length = 8 ): string
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charsLength = strlen($chars);

		$s = '';
		for ($i = 0; $i < 8; $i++)
			$s.= $chars[rand(0, $charsLength-1)];
		return $s;
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetSummonerByAccountId(LeagueAPI $api )
	{
		$accountId = "tGSPHbasiCOgRM_MuovMKfXw7oh6pfXmGiPDnXcxJDohrQ";

		//  Get library processed results
		/** @var Objects\SummonerDto $result */
		$result = $api->getSummonerByAccountId($accountId);

		$this->assertSame("KnNZNuEVZ5rZry3IyWwYSVuikRe0y3qTWSkr1wxcmV5CLJ8", $result->id);
		$this->assertSame($accountId, $result->accountId);
		$this->assertSame("rNmb6Rq8CQUqOHzMsFihMCUy4Pd201vDaRW9djAoJ9se7myXrDprvng9neCanq7yGNmz7B3Wri4Elw", $result->puuid);
		$this->assertSame('I am TheKronnY', $result->name);
		$this->assertInternalType('integer', $result->summonerLevel);
		$this->assertInternalType('integer', $result->profileIconId);
		$this->assertInternalType('integer', $result->revisionDate);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetSummonerByName(LeagueAPI $api )
	{
		$summonerName = 'I am TheKronnY';

		//  Get library processed results
		/** @var Objects\SummonerDto $result */
		$result = $api->getSummonerByName($summonerName);

		$this->assertSame("KnNZNuEVZ5rZry3IyWwYSVuikRe0y3qTWSkr1wxcmV5CLJ8", $result->id);
		$this->assertSame("tGSPHbasiCOgRM_MuovMKfXw7oh6pfXmGiPDnXcxJDohrQ", $result->accountId);
		$this->assertSame("rNmb6Rq8CQUqOHzMsFihMCUy4Pd201vDaRW9djAoJ9se7myXrDprvng9neCanq7yGNmz7B3Wri4Elw", $result->puuid);
		$this->assertSame($summonerName, $result->name);
		$this->assertInternalType('integer', $result->summonerLevel);
		$this->assertInternalType('integer', $result->profileIconId);
		$this->assertInternalType('integer', $result->revisionDate);
	}

	public function emptySummonerNameProvider(): array
	{
		return [
			'Empty string' => [
				'summonerName' => ''
			],
			'String containing one space' => [
				'summonerName' => ' '
			],
			'String containing two space' => [
				'summonerName' => '  '
			],
			'String containing multiple spaces' => [
				'summonerName' => '       '
			],
		];
	}

	/**
	 * @depends testInit
	 * @dataProvider emptySummonerNameProvider
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetSummonerByNameThrowsAnExceptionInCaseNoNameIsSubmitted(string $summonerName, LeagueAPI $api)
	{
		$this->expectException(RequestParameterException::class);
		$this->expectExceptionMessage('Provided summoner name must not be empty');

		$api->getSummonerByName($summonerName);
	}

	/**
	 * @depends testInit
	 *
	 * @param LeagueAPI $api
	 */
	public function testGetSummoner(LeagueAPI $api )
	{
		$summonerId = "KnNZNuEVZ5rZry3IyWwYSVuikRe0y3qTWSkr1wxcmV5CLJ8";

		//  Get library processed results
		/** @var Objects\SummonerDto $result */
		$result = $api->getSummoner($summonerId);

		$this->assertSame($summonerId, $result->id);
		$this->assertSame("tGSPHbasiCOgRM_MuovMKfXw7oh6pfXmGiPDnXcxJDohrQ", $result->accountId);
		$this->assertSame("rNmb6Rq8CQUqOHzMsFihMCUy4Pd201vDaRW9djAoJ9se7myXrDprvng9neCanq7yGNmz7B3Wri4Elw", $result->puuid);
		$this->assertSame('I am TheKronnY', $result->name);
		$this->assertInternalType('integer', $result->summonerLevel);
		$this->assertInternalType('integer', $result->profileIconId);
		$this->assertInternalType('integer', $result->revisionDate);
	}
}
