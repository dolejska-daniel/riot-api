<?php

/**
 * Copyright (C) 2016-2017  Daniel Dolejška
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

use RiotAPI\Objects\IApiObject;
use RiotAPI\Objects\IApiObjectExtension;
use RiotAPI\Objects\MasteryPageDto;
use RiotAPI\Objects\MasteryPagesDto;
use RiotAPI\RiotAPI;


/**
 *   Class GeneralException
 *
 * @package RiotAPI\Exception
 */
class MasteryPagesDtoExtension implements IApiObjectExtension
{
	/** @var MasteryPagesDto $object */
	protected $object;

	/**
	 *   MasteryPagesDtoExtension constructor.
	 *
	 * @param IApiObject|MasteryPagesDto $apiObject
	 * @param RiotAPI                    $api
	 */
	public function __construct( IApiObject &$apiObject, RiotAPI &$api )
	{
		$this->object = $apiObject;
	}

	public function pageExists( string $pageName )
	{
		/** @var MasteryPageDto $page */
		foreach ($this->object->pages as $page)
			if ($page->name == $pageName)
				return true;

		return false;
	}

	public function getPageByName( string $pageName )
	{
		/** @var MasteryPageDto $page */
		foreach ($this->object->pages as $page)
			if ($page->name == $pageName)
				return $page;

		return null;
	}
}