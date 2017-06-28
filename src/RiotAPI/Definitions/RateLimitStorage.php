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

namespace RiotAPI\Definitions;


/**
 *   Class RateLimitStorage
 *
 * @package RiotAPI\Definition
 */
class RateLimitStorage
{
	/** @var array $limits */
	protected $limits = array();

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
	 *   Initializes limits for providede API key on all regions.
	 *
	 * @param string $api_key
	 * @param array  $limits
	 */
	public function init( string $api_key, array $limits )
	{
		foreach ($this->limits as $region => $key_limits)
		{
			//  Set limits for each region registered
			if (isset($this->limits[$region][$api_key]))
			{
				//  Some limits are already in place
				foreach ($this->limits[$region][$api_key] as $interval => $saved_limits)
				{
					if (isset($limits[$interval]))
					{
						//  Set new limits for existing $interval of $api_key on $region
						$this->limits[$region][$api_key][$interval]['limit'] = $limits[$interval]['limit'];
					}
					else
					{
						//  Unset existing $interval limit, it's not set in newly provided limits
						unset($this->limits[$region][$api_key][$interval]);
					}
				}

				foreach ($limits as $interval => $new_limits)
				{
					if (isset($this->limits[$region][$api_key][$interval]) == false)
					{
						//  Set new limits for non-existing $interval of $api_key on $region
						$this->limits[$region][$api_key][$interval] = $new_limits;
					}
				}
			}
			else
				//  Set limits for $api_key on $region
				$this->limits[$region][$api_key] = $limits;
		}
	}

	/**
	 *   Returns interval limits for provided API key on provided region.
	 *
	 * @param string $api_key
	 * @param string $region
	 *
	 * @return mixed
	 */
	public function getIntervals( string $api_key, string $region )
	{
		return $this->limits[$region][$api_key];
	}

	/**
	 *   Sets new value for used API calls for provided API key on provided region.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param int    $timeInterval
	 * @param int    $value
	 */
	public function setUsed( string $api_key, string $region, int $timeInterval, int $value )
	{
		$this->limits[$region][$api_key][$timeInterval]['used'] = $value;
		if ($value == 1)
			$this->limits[$region][$api_key][$timeInterval]['expires'] = time() + $timeInterval;
	}

	/**
	 *   Determines whether or not API call can be made.
	 *
	 * @param string $api_key
	 * @param string $region
	 *
	 * @return bool
	 */
	public function canCall( string $api_key, string $region ): bool
	{
		foreach ($this->getIntervals($api_key, $region) as $timeInterval => $limits)
		{
			//  Check all saved intervals for this region
			if ($limits['used'] >= $limits['limit'] && $limits['expires'] > time())
				return false;
		}

		return true;
	}

	/**
	 *   Registers that new API call has been made.
	 *
	 * @param string $api_key
	 * @param string $region
	 * @param string $header
	 */
	public function registerCall( string $api_key, string $region, string $header )
	{
		foreach (explode(',', $header) as $currentLimit)
		{
			$currentLimit = explode(':', $currentLimit);
			$used = (int)$currentLimit[0];
			$timeInterval = (int)$currentLimit[1];

			$this->setUsed($api_key, $region, $timeInterval, $used);
		}
	}
}