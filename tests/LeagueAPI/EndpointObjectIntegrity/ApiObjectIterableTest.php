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

use RiotAPI\LeagueAPI\Objects;


/**
 *   Class BaseObjectIterable
 * @iterable $data
 */
class BaseObjectIterable extends Objects\ApiObjectIterable
{
	/** @var array $data */
	public $data;
}


class ApiObjectIterableTest extends RiotAPITestCase
{
	public static $data = [
		'data' => [
			'd', 'u', 'm', 'm', 'y', '_', 'd', 'a', 't', 'a'
		],
	];

	public function testRewind()
	{
		$obj = new BaseObjectIterable(self::$data);

		$this->assertSame('u', $obj->next());
		$obj->rewind();
		$this->assertSame('u', $obj->next());
	}

	public function testCurrent()
	{
		$obj = new BaseObjectIterable(self::$data);

		$this->assertSame('d', $obj->current());
		$obj->next();
		$this->assertSame('u', $obj->current());
	}

	public function testKey()
	{
		$obj = new BaseObjectIterable(self::$data);

		$this->assertSame(0, $obj->key());
		$obj->next();
		$this->assertSame(1, $obj->key());
	}

	public function testNext()
	{
		$obj = new BaseObjectIterable(self::$data);

		$this->assertSame('u', $obj->next());
		$this->assertSame('m', $obj->next());
	}

	public function testValid()
	{
		$obj = new BaseObjectIterable(self::$data);

		$this->assertTrue($obj->valid());
		while ($obj->next() !== false);
		$this->assertFalse($obj->valid());
	}
}
