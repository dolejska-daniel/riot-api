<?php

/**
 * Copyright (C) 2016  Daniel Dolejška
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

use RiotAPI\Extensions\MasteryPagesDtoExtension;
use RiotAPI\Objects\IApiObject;
use RiotAPI\Objects\MasteryPagesDto;
use RiotAPI\RiotAPI;
use RiotAPI\Definitions\Region;

use RiotAPI\Exceptions\SettingsException;


class ExtensionsTest extends TestCase
{
	public function testInit()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_EXTENSIONS     => [
				MasteryPagesDto::class => MasteryPagesDtoExtension::class,
			],
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	public function testInit_noExtension()
	{
		$api = new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
		]);

		$this->assertInstanceOf(RiotAPI::class, $api);

		return $api;
	}

	public function testInit_invalid_settings()
	{
		$this->expectException(SettingsException::class);
		$this->expectExceptionMessage("Value of settings parameter");

		new RiotAPI([
			RiotAPI::SET_KEY            => getenv('API_KEY'),
			RiotAPI::SET_REGION         => Region::EUROPE_EAST,
			RiotAPI::SET_USE_DUMMY_DATA => true,
			RiotAPI::SET_EXTENSIONS     => IApiObject::class,
		]);
	}
}
