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
 *   Interface IRateLimitControl
 *
 * @package RiotAPI\LeagueAPI\Definitions
 */
interface IRateLimitControl
{
	/**
	 *   IRateLimitControl constructor.
	 *
	 * @param IRegion $region
	 */
	public function __construct( IRegion $region );

	/**
	 *   Returns currently stored status of limits for given API key, region and endpoint.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 *
	 * @return array
	 */
	public function getCurrentStatus(string $api_key, string $region, string $endpoint): array;

	/**
	 *   Determines whether or not API call can be made.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $resource
	 * @param string $endpoint
	 *
	 * @return bool
	 */
	public function canCall( string $api_key, string $region, string $resource, string $endpoint): bool;

	/**
	 *   Registers that new API call has been made.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 * @param string $app_header
	 * @param string $method_header
	 *
	 * @return
	 */
	public function registerLimits( string $api_key, string $region, string $endpoint, string $app_header, string $method_header );

	/**
	 *   Registers that new API call has been made.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 * @param string $app_header
	 * @param string $method_header
	 *
	 * @return
	 */
	public function registerCall( string $api_key, string $region, string $endpoint, string $app_header, string $method_header );

	/**
	 *   Clears all currently saved data.
	 *
	 * @return bool
	 */
	public function clear(): bool;
}