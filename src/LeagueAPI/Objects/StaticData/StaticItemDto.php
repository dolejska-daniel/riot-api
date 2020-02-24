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

namespace RiotAPI\LeagueAPI\Objects\StaticData;

use RiotAPI\LeagueAPI\Objects\ApiObject;


/**
 *   Class StaticItemDto
 * This object contains item data.
 *
 * @package RiotAPI\LeagueAPI\Objects\StaticData
 */
class StaticItemDto extends ApiObject
{
	/** @var StaticGoldDto $gold */
	public $gold;

	/** @var string $plaintext */
	public $plaintext;

	/** @var bool $hideFromAll */
	public $hideFromAll;

	/** @var bool $inStore */
	public $inStore;

	/** @var string[] $into */
	public $into;

	/** @var int $id */
	public $id;

	/** @var StaticInventoryDataStatsDto $stats */
	public $stats;

	/** @var string $colloq */
	public $colloq;

	/** @var bool[] $maps */
	public $maps;

	/** @var int $specialRecipe */
	public $specialRecipe;

	/** @var StaticImageDto $image */
	public $image;

	/** @var string $description */
	public $description;

	/** @var string[] $tags */
	public $tags;

	/** @var string[] $effect */
	public $effect;

	/** @var string $requiredChampion */
	public $requiredChampion;

	/** @var string[] $from */
	public $from;

	/** @var string $group */
	public $group;

	/** @var bool $consumeOnFull */
	public $consumeOnFull;

	/** @var string $name */
	public $name;

	/** @var bool $consumed */
	public $consumed;

	/** @var int $depth */
	public $depth;

	/** @var int $stacks */
	public $stacks;
}
