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

namespace RiotAPI\LeagueAPI\Extensions;

use RiotAPI\LeagueAPI\Objects\IApiObject;
use RiotAPI\LeagueAPI\Objects\IApiObjectExtension;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticReforgedRuneDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticReforgedRunePathDto;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticReforgedRunePathList;
use RiotAPI\LeagueAPI\Objects\StaticData\StaticReforgedRuneSlotDto;
use RiotAPI\LeagueAPI\LeagueAPI;


/**
 *   Class StaticReforgedRunePathListExtension
 *
 * @package RiotAPI\LeagueAPI\Extensions
 */
class StaticReforgedRunePathListExtension implements IApiObjectExtension
{
	/** @var StaticReforgedRunePathList $object */
	protected $object;

	/** @var array $rune_list */
	protected $rune_list = [];

	/**
	 *   StaticReforgedRunePathListExtension constructor.
	 *
	 * @param IApiObject|StaticReforgedRunePathList $apiObject
	 * @param LeagueAPI                               $api
	 */
	public function __construct(IApiObject &$apiObject, LeagueAPI &$api )
	{
		$this->object = $apiObject;

		/** @var StaticReforgedRunePathDto $path */
		foreach ($this->object as $path)
		{
			/** @var StaticReforgedRuneSlotDto $slot */
			foreach ($path as $slot)
			{
				/** @var StaticReforgedRuneDto $rune */
				foreach ($slot as $rune)
				{
					$this->rune_list[$rune->id] = $rune;
				}
			}
		}
	}

	public function getRuneById( int $rune_id )
	{
		return @$this->rune_list[$rune_id];
	}
}