<?php

/**
 * Copyright (C) 2016  Daniel Dolejška.
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

namespace RiotAPI\Definition;

use RiotAPI\Exception\SettingsException;

/**
 *   Class RateLimitControl.
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
    public function __construct(IRegion $region)
    {
        $this->storage = new RateLimitStorage($region);
    }

    /**
     *   Sets limits for provided API key.
     *
     * @param string $api_key
     * @param array  $limits
     *
     * @throws SettingsException
     */
    public function setLimits(string $api_key, array $limits)
    {
        $intervals = [
            IRateLimitControl::INTERVAL_1S,
            IRateLimitControl::INTERVAL_10S,
            IRateLimitControl::INTERVAL_10M,
            IRateLimitControl::INTERVAL_1H,
        ];

        foreach ($limits as $interval => $limit) {
            if (!in_array($interval, $intervals, true) || !is_int($limit)) {
                throw new SettingsException('Invalid rate limit interval settings provided.');
            }
            $limits[$interval] = [
                'used'    => 0,
                'limit'   => $limit,
                'expires' => 0,
            ];
        }

        $this->storage->init($api_key, $limits);
    }

    /**
     *   Determines whether or not API call can be made.
     *
     * @param string $api_key
     * @param string $region
     *
     * @return bool
     */
    public function canCall(string $api_key, string $region): bool
    {
        return $this->storage->canCall($api_key, $region);
    }

    /**
     *   Registers that new API call has been made.
     *
     * @param string $api_key
     * @param string $region
     * @param string $header
     */
    public function registerCall(string $api_key, string $region, string $header)
    {
        $this->storage->registerCall($api_key, $region, $header);
    }
}
