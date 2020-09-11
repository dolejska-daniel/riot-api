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

namespace RiotAPI\LeagueAPI\Objects;


/**
 *   Class Service
 *
 * Used in:
 *   lol-status (v3)
 *     @link https://developer.riotgames.com/apis#lol-status-v3/GET_getShardData
 *
 * @iterable $incidents
 *
 * @package RiotAPI\LeagueAPI\Objects
 */
class Service extends ApiObjectIterable
{
	/** @var Incident[] $incidents */
	public $incidents;

	/** @var string $name */
	public $name;

	/** @var string $slug */
	public $slug;

	/** @var string $status */
	public $status;
}
