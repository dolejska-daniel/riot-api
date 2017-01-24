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
 *   Class SImageDto
 * This object contains image data.
 *
 * Used in:
 *   lol-static-data (v1.2)
 *     @link https://developer.riotgames.com/api/methods#!/1055/3633
 *     @link https://developer.riotgames.com/api/methods#!/1055/3622
 *     @link https://developer.riotgames.com/api/methods#!/1055/3621
 *     @link https://developer.riotgames.com/api/methods#!/1055/3627
 *     @link https://developer.riotgames.com/api/methods#!/1055/3635
 *     @link https://developer.riotgames.com/api/methods#!/1055/3625
 *     @link https://developer.riotgames.com/api/methods#!/1055/3626
 *     @link https://developer.riotgames.com/api/methods#!/1055/3623
 *     @link https://developer.riotgames.com/api/methods#!/1055/3629
 *     @link https://developer.riotgames.com/api/methods#!/1055/3634
 *     @link https://developer.riotgames.com/api/methods#!/1055/3628
 *
 * @package RiotAPI\Objects\StaticData
 */
class SImageDto extends ApiObject
{
	/** @var string $full */
	public $full;

	/** @var string $group */
	public $group;

	/** @var int $h */
	public $h;

	/** @var string $sprite */
	public $sprite;

	/** @var int $w */
	public $w;

	/** @var int $x */
	public $x;

	/** @var int $y */
	public $y;
}
