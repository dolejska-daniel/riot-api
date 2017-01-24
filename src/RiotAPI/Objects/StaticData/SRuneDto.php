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

namespace RiotAPI\Objects\StaticData;

use RiotAPI\Objects\ApiObject;


/**
 *   Class SRuneDto
 * This object contains rune data.
 *
 * Used in:
 *   lol-static-data (v1.2)
 *     @link https://developer.riotgames.com/api/methods#!/1055/3623
 *     @link https://developer.riotgames.com/api/methods#!/1055/3629
 *
 * @package RiotAPI\Objects\StaticData
 */
class SRuneDto extends ApiObject
{
	/** @var string $colloq */
	public $colloq;

	/** @var bool $consumeOnFull */
	public $consumeOnFull;

	/** @var bool $consumed */
	public $consumed;

	/** @var int $depth */
	public $depth;

	/** @var string $description */
	public $description;

	/** @var string[] $from */
	public $from;

	/** @var string $group */
	public $group;

	/** @var bool $hideFromAll */
	public $hideFromAll;

	/** @var int $id */
	public $id;

	/** @var SImageDto $image */
	public $image;

	/** @var bool $inStore */
	public $inStore;

	/** @var string[] $into */
	public $into;

	/** @var boolean[] $maps */
	public $maps;

	/** @var string $name */
	public $name;

	/** @var string $plaintext */
	public $plaintext;

	/** @var string $requiredChampion */
	public $requiredChampion;

	/** @var SMetaDataDto $rune */
	public $rune;

	/** @var string $sanitizedDescription */
	public $sanitizedDescription;

	/** @var int $specialRecipe */
	public $specialRecipe;

	/** @var int $stacks */
	public $stacks;

	/** @var SBasicDataStatsDto $stats */
	public $stats;

	/** @var string[] $tags */
	public $tags;
}
