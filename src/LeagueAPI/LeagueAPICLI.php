<?php

/**
 * Copyright (C) 2016-2019  Daniel Dolejška
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

/**
 *  CLI support.
 *
 *  First parameter:  function name (e.g. getChampions, getSummonerByName, …)
 *  Second parameter: first function parameter
 *  Third parameter:  second function parameter
 *  n-th parameter: (n-1)-th function parameter
 *  …
 *  followed by CLI options
 *
 *  Usage example:
 *    php RiotAPI_CLI.php {method} {param 1} ... {param x} {cli opt 1} {cli opt arg 1} ...
 */

require __DIR__ . "/../../vendor/autoload.php";

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\CLI\Commands\InvokeMethodLeagueAPI;
use RiotAPI\LeagueAPI\Utils\MethodDescriptor;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

// Initialize console application
$app = new Application();

// Create API library reflection
$apiRef = new ReflectionClass(LeagueAPI::class);
// Get available public methods
$methods = $apiRef->getMethods(ReflectionProperty::IS_PUBLIC);

// For each existing method, create command
foreach ($methods as $method)
{
	$descriptor = MethodDescriptor::fromReflectionMethod($method);
	if (!$descriptor->isCLIMethod())
		continue;

	$namespace = $descriptor->getProp($descriptor::CLI_METHOD_NAMESPACE);
	$name = $descriptor->getProp($descriptor::CLI_METHOD_NAME);

	// Create method-call command
	$command = new InvokeMethodLeagueAPI();
	$command->setName("$namespace:$name");
	$command->setDescription($descriptor->description);
	$command->setAliases([$method->getName()]);
	$command->setMethodRef($method);

	// Get method parameters and register them as command's arguments
	$parameters = $method->getParameters();
	foreach ($parameters as $parameter)
	{
		$name    = $parameter->getName();
		$mode    = InputArgument::REQUIRED;
		$default = null;
		if ($parameter->isOptional())
		{
			$mode    = InputArgument::OPTIONAL;
			$default = $parameter->getDefaultValue();
		}

		$command->addArgument($name, $mode, "", $default);
	}

	// Register library related options for each command
	$command->addOption("config", "c", InputOption::VALUE_REQUIRED, "Path to JSON file containing library configuration. https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI%3A-CLI-support#config-file");
	$command->addOption("output", "o", InputOption::VALUE_REQUIRED, "Path to save JSON response output.");

	// Register created command
	$app->add($command);
}

// Run application
$app->run();
