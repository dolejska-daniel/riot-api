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
 *   Class StaticChampionDto
 * This object contains champion data.
 *
 * @package RiotAPI\LeagueAPI\Objects\StaticData
 */
class StaticChampionDto extends ApiObject
{
	/** @var StaticInfoDto $info */
	public $info;

	/** @var string[] $enemytips */
	public $enemytips;

	/** @var StaticStatsDto $stats */
	public $stats;

	/** @var string $name */
	public $name;

	/** @var string $title */
	public $title;

	/** @var StaticImageDto $image */
	public $image;

	/** @var string[] $tags */
	public $tags;

	/** @var string $partype */
	public $partype;

	/** @var StaticSkinDto[] $skins */
	public $skins;

	/** @var StaticPassiveDto $passive */
	public $passive;

	/** @var StaticRecommendedDto[] $recommended */
	public $recommended;

	/** @var string[] $allytips */
	public $allytips;

	/** @var string $key */
	public $key;

	/** @var string $lore */
	public $lore;

	/** @var int $id */
	public $id;

	/** @var string $blurb */
	public $blurb;

	/** @var StaticChampionSpellDto[] $spells */
	public $spells;
}
