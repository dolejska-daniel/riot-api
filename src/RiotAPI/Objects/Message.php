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

namespace RiotAPI\Objects;


/**
 *   Class Message
 *
 * @package RiotAPI\Objects
 */
class Message extends ApiObject
{
	/** @var string $author */
	public $author;

	/** @var string $content */
	public $content;

	/** @var string $created_at */
	public $created_at;

	/** @var string $id */
	public $id;

	/** @var string $severity */
	public $severity;

	/** @var Translation[] $translations */
	public $translations;

	/** @var int $updated_at */
	public $updated_at;
}