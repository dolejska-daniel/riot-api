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

class BaseObject extends Objects\ApiObject {}


class ApiObjectTest extends RiotAPITestCase
{
	public function testGetIterablePropertyName()
	{
		$propName = Objects\ApiObject::getIterablePropertyName('/** @iterable $property */');
		$this->assertSame('property', $propName);
	}

	public function testGetIterablePropertyName_False()
	{
		$propName = Objects\ApiObject::getIterablePropertyName('/** @no-iterable-here */');
		$this->assertFalse($propName);
	}

	public function testGetPropertyDataType()
	{
		$dataType = Objects\ApiObject::getPropertyDataType('/** @var SpecialClass $property */');
		$this->assertAttributeSame('SpecialClass', 'class', $dataType);
		$this->assertAttributeSame(false, 'isArray', $dataType);
	}

	public function testGetPropertyDataType_Array()
	{
		$dataType = Objects\ApiObject::getPropertyDataType('/** @var SpecialClass[] $property */');
		$this->assertAttributeSame('SpecialClass', 'class', $dataType);
		$this->assertAttributeSame(true, 'isArray', $dataType);
	}

	public function testGetPropertyDataType_False()
	{
		$dataType = Objects\ApiObject::getPropertyDataType('/** @var int $property */');
		$this->assertFalse($dataType);
	}

	public function testGetData()
	{
		$array = [ 'd', 'u', 'm', 'm', 'y', '_', 'd', 'a', 't', 'a' ];
		$obj = new BaseObject($array);
		$this->assertSame($array, $obj->getData());
	}
}
