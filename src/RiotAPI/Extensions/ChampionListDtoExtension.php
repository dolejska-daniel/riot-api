<?php

/**
 * Copyright (C) 2016-2018  Daniel Dolejška
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

namespace RiotAPI\Extensions;

use RiotAPI\Objects\ChampionDto;
use RiotAPI\Objects\ChampionListDto;
use RiotAPI\Objects\IApiObject;
use RiotAPI\Objects\IApiObjectExtension;
use RiotAPI\RiotAPI;


/**
 *   Class GeneralException
 *
 * @package RiotAPI\Exception
 */
class ChampionListDtoExtension implements IApiObjectExtension
{
	/** @var ChampionListDto $object */
	protected $object;

	/**
	 *   MasteryPagesDtoExtension constructor.
	 *
	 * @param IApiObject|ChampionListDto $apiObject
	 * @param RiotAPI                    $api
	 */
	public function __construct( IApiObject &$apiObject, RiotAPI &$api )
	{
		$this->object = $apiObject;
	}

	public function getById( int $champion_id )
	{
		/** @var ChampionDto $page */
		foreach ($this->object->champions as $champion)
			if ($champion->id == $champion_id)
				return $champion;

		return null;
	}

	public function isActive( int $champion_id )
	{
		/** @var ChampionDto $page */
		foreach ($this->object->champions as $champion)
			if ($champion->id == $champion_id)
				return $champion->active;

		return null;
	}

	public function isFreeToPlay( int $champion_id )
	{
		/** @var ChampionDto $page */
		foreach ($this->object->champions as $champion)
			if ($champion->id == $champion_id)
				return $champion->freeToPlay;

		return null;
	}

	public function isRankedEnabled( int $champion_id )
	{
		/** @var ChampionDto $page */
		foreach ($this->object->champions as $champion)
			if ($champion->id == $champion_id)
				return $champion->rankedPlayEnabled;

		return null;
	}
}