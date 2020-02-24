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


/**
 *   Class RateLimitControl
 *
 * @package RiotAPI\LeagueAPI\Definitions
 */
class RateLimitControl implements IRateLimitControl
{
	/** @var RateLimitStorage $storage */
	protected $storage;

	/**
	 *   RateLimitControl constructor.
	 *
	 * @param IRegion $region
	 */
	public function __construct( IRegion $region )
	{
		$this->storage = new RateLimitStorage($region);
	}

	/**
	 *   Clears all currently saved data.
	 *
	 * @return bool
	 */
	public function clear(): bool
	{
		return $this->storage->clear();
	}

	/**
	 *   Returns currently stored status of limits for given API key, region and endpoint.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 *
	 * @return array
	 */
	public function getCurrentStatus(string $api_key, string $region, string $endpoint): array
	{
		return [
			"app" => $this->storage->getAppLimits($api_key, $region),
			"method" => $this->storage->getMethodLimits($api_key, $region, $endpoint),
		];
	}

	/**
	 *   Determines whether or not API call can be made
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $resource
	 * @param string $endpoint
	 *
	 * @return bool
	 */
	public function canCall( string $api_key, string $region, string $resource, string $endpoint ): bool
	{
		return $this->storage->canCall($api_key, $region, $resource, $endpoint);
	}

	/**
	 *   Registers that new API call has been made
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 * @param string $app_limit_header
	 * @param string $method_limit_header
	 */
	public function registerLimits( string $api_key, string $region, string $endpoint, string $app_limit_header = null, string $method_limit_header = null )
	{
		if ($app_limit_header)
			$this->storage->registerAppLimits($api_key, $region, $app_limit_header);

		if ($method_limit_header)
			$this->storage->registerMethodLimits($api_key, $region, $endpoint, $method_limit_header);
	}

	/**
	 *   Registers that new API call has been made
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 * @param string $app_count_header
	 * @param string $method_count_header
	 */
	public function registerCall( string $api_key, string $region, string $endpoint, string $app_count_header = null, string $method_count_header = null )
	{
		$this->storage->registerCall($api_key, $region, $endpoint, $app_count_header, $method_count_header);
	}
}