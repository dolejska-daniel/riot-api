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

use RiotAPI\LeagueAPI\LeagueAPI;


/**
 *   Class RateLimitStorage
 *
 * @package RiotAPI\LeagueAPI\Definitions
 */
class RateLimitStorage
{
	/** @var array $limits */
	protected $limits = [];

	/**
	 *   RateLimitStorage constructor.
	 *
	 * @param IRegion $region
	 */
	public function __construct( IRegion $region )
	{
		foreach ($region->getList() as $regionId => $regionName)
			$this->limits[$regionId] = [];
	}

	/**
	 *   Clears all currently saved data.
	 *
	 * @return bool
	 */
	public function clear(): bool
	{
		$this->limits = [];
		return true;
	}

	protected static function parseLimitHeaders( $header )
	{
		$limits = [];
		foreach (explode(',', $header) as $limitInterval)
		{
			$limitInterval = explode(':', $limitInterval);
			$limit         = (int)$limitInterval[0];
			$interval      = (int)$limitInterval[1];

			$limits[$interval] = $limit;
		}

		return $limits;
	}

	/**
	 *   Initializes limits for providede API key on all regions.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param array  $limits
	 */
	public function initApp( string $api_key, string $region, array $limits )
	{
		$output = [];
		foreach ($limits as $interval => $limit)
		{
			$output[$interval] = [
				'used'    => 0,
				'limit'   => $limit,
				'expires' => time() + $interval,
			];
		}
		$this->limits[$region][$api_key]['app'] = $output;
	}

	/**
	 *   Initializes limits for providede API key on all regions.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 * @param array  $limits
	 */
	public function initMethod( string $api_key, string $region, string $endpoint, array $limits )
	{
		$output = [];
		foreach ($limits as $interval => $limit)
		{
			$output[$interval] = [
				'used'    => 0,
				'limit'   => $limit,
				'expires' => time() + $interval,
			];
		}
		$this->limits[$region][$api_key]['method'][$endpoint] = $output;
	}

	/**
	 *   Returns interval limits for provided API key on provided region.
	 *
	 * @param string $api_key
	 * @param string $region
	 *
	 * @return mixed
	 */
	public function getAppLimits( string $api_key, string $region )
	{
		return @$this->limits[$region][$api_key]['app'];
	}

	/**
	 *   Returns interval limits for provided API key on provided region.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 *
	 * @return mixed
	 */
	public function getMethodLimits( string $api_key, string $region, string $endpoint)
	{
		return @$this->limits[$region][$api_key]['method'][$endpoint];
	}

	/**
	 *   Sets new value for used API calls for provided API key on provided region.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param int    $timeInterval
	 * @param int    $value
	 */
	public function setAppUsed( string $api_key, string $region, int $timeInterval, int $value )
	{
		$this->limits[$region][$api_key]['app'][$timeInterval]['used'] = $value;
		if ($value == 1)
			$this->limits[$region][$api_key]['app'][$timeInterval]['expires'] = time() + $timeInterval;
	}

	/**
	 *   Sets new value for used API calls for provided API key on provided region.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 * @param int    $timeInterval
	 * @param int    $value
	 */
	public function setMethodUsed( string $api_key, string $region, string $endpoint, int $timeInterval, int $value )
	{
		$this->limits[$region][$api_key]['method'][$endpoint][$timeInterval]['used'] = $value;
		if ($value == 1)
			$this->limits[$region][$api_key]['method'][$endpoint][$timeInterval]['expires'] = time() + $timeInterval;
	}

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
	public function canCall( string $api_key, string $region, string $resource, string $endpoint): bool
	{
		$appLimits = $this->getAppLimits($api_key, $region);
		if (is_array($appLimits) && $resource != LeagueAPI::RESOURCE_STATICDATA)
		{
			foreach ($appLimits as $timeInterval => $limits)
			{
				//  Check all saved intervals for this region
				if ($limits['used'] >= $limits['limit'] && $limits['expires'] > time())
					return false;
			}
		}

		$methodLimits = $this->getMethodLimits($api_key, $region, $endpoint);
		if (is_array($methodLimits))
		{
			foreach ($methodLimits as $timeInterval => $limits)
			{
				//  Check all saved intervals for this endpoint
				if ($limits['used'] >= $limits['limit'] && $limits['expires'] > time())
					return false;
			}
		}

		return true;
	}

	/**
	 *   Registers that new API call has been made.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $app_header
	 */
	public function registerAppLimits( string $api_key, string $region, string $app_header )
	{
		$limits = self::parseLimitHeaders($app_header);
		$this->initApp($api_key, $region, $limits);
	}

	/**
	 *   Registers that new API call has been made.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 * @param string $method_header
	 */
	public function registerMethodLimits( string $api_key, string $region, string $endpoint, string $method_header )
	{
		$limits = self::parseLimitHeaders($method_header);
		$this->initMethod($api_key, $region, $endpoint, $limits);
	}

	/**
	 *   Registers that new API call has been made.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $endpoint
	 * @param string $app_header
	 * @param string $method_header
	 */
	public function registerCall( string $api_key, string $region, string $endpoint, string $app_header = null, string $method_header = null )
	{
		if ($app_header)
		{
			$limits = self::parseLimitHeaders($app_header);
			foreach ($limits as $interval => $used)
				$this->setAppUsed($api_key, $region, $interval, $used);
		}

		if ($method_header)
		{
			$limits = self::parseLimitHeaders($method_header);
			foreach ($limits as $interval => $used)
				$this->setMethodUsed($api_key, $region, $endpoint, $interval, $used);
		}
	}
}