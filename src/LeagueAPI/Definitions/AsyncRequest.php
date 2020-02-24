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

namespace RiotAPI\LeagueAPI\Definitions;


use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;

/**
 *   Class AsyncRequest
 *
 * @package RiotAPI\LeagueAPI\Definitions
 */
class AsyncRequest
{
	/** @var Client $client */
	public $client;

	/** @var callable $onFulfilled */
	public $onFulfilled;

	/** @var callable $onRejected */
	public $onRejected;

	/** @var PromiseInterface $promise */
	protected $promise;


	/**
	 *   AsyncRequest constructor.
	 *
	 * @param Client        $client
	 * @param callable|null $onFulfilled
	 * @param callable|null $onRejected
	 */
	public function __construct( Client $client, callable $onFulfilled = null, callable $onRejected = null )
	{
		$this->client = $client;
		$this->onFulfilled = $onFulfilled;
		$this->onRejected  = $onRejected;
	}


	/**
	 *   Promise setter.
	 *
	 * @param PromiseInterface $promise
	 *
	 * @return AsyncRequest
	 */
	public function setPromise( PromiseInterface $promise ): self
	{
		$this->promise = $promise;
		$promise->then($this->onFulfilled, $this->onRejected);
		return $this;
	}

	/**
	 *   Promise getter.
	 *
	 * @return PromiseInterface
	 */
	public function getPromise(): PromiseInterface
	{
		return $this->promise;
	}
}