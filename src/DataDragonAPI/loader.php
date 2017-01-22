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

//  PHP version check
if (PHP_VERSION_ID < 70000)
	trigger_error('This library requires PHP version 7.0.0 or newer!', E_USER_ERROR);

//  Exceptions
require_once __DIR__ . '/Exceptions/GeneralException.php';

//  Nette Utilities
require_once __DIR__ . '/Utils/IHtmlString.php';
require_once __DIR__ . '/Utils/StaticClass.php';
require_once __DIR__ . '/Utils/Callback.php';
require_once __DIR__ . '/Utils/ObjectMixin.php';
require_once __DIR__ . '/Utils/SmartObject.php';
require_once __DIR__ . '/Utils/Html.php';

//  Definitions
require_once __DIR__ . '/Definitions/Map.php';
require_once __DIR__ . '/Definitions/UI.php';

//  Core class
require_once __DIR__ . '/DataDragonAPI.php';