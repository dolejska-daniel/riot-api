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

namespace RiotAPI\LeagueAPICLI;

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPICLI\Commands\InvokeMethodLeagueAPI;
use RiotAPI\LeagueAPI\Utils\MethodDescriptor;

use Symfony\Component\Console\Application as SymfonyAplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 *   Class Application
 *
 * @package RiotAPI\LeagueAPICLI
 */
class Application extends SymfonyAplication
{
	public function __construct()
	{
		parent::__construct("LeagueAPI CLI mode");

		// Create API library reflection
		$apiRef = new ReflectionClass(LeagueAPI::class);
		// Get available public methods
		$methods = $apiRef->getMethods(ReflectionProperty::IS_PUBLIC);

		// For each existing method, create command
		$commands = [];
		foreach ($methods as $method)
			$commands[] = $this->createCommandFromReflectionMethod($method);

		foreach ($commands as $command)
			if ($command)
				$this->add($command);
	}

	/**
	 * @param ReflectionMethod $method
	 *
	 * @return InvokeMethodLeagueAPI|void
	 * @throws \ReflectionException
	 */
	protected function createCommandFromReflectionMethod(ReflectionMethod $method)
	{
		$descriptor = MethodDescriptor::fromReflectionMethod($method);
		if (!$descriptor->isCLIMethod())
			return;

		$namespace = $descriptor->getProp($descriptor::CLI_METHOD_NAMESPACE);
		$name = $descriptor->getProp($descriptor::CLI_METHOD_NAME);

		// Create method-call command
		$command = new InvokeMethodLeagueAPI();
		$command->setName("$namespace:$name");
		$command->setDescription($descriptor->description);
		$command->setAliases([$method->getName()]);
		$command->setMethodRef($method);

		$this->addArgumentsToCommandFromReflectionMethod($command, $method);
		$this->addGlobalOptionsToCommand($command);

		return $command;
	}

	/**
	 * @param Command $command
	 * @param ReflectionMethod $method
	 *
	 * @throws \ReflectionException
	 */
	protected function addArgumentsToCommandFromReflectionMethod(Command $command, ReflectionMethod $method)
	{
		// Get method parameters and register them as command's arguments
		$parameters = $method->getParameters();
		foreach ($parameters as $parameter)
		{
			$name    = $parameter->getName();
			$mode    = $parameter->isOptional() ? InputArgument::OPTIONAL : InputArgument::REQUIRED;
			$default = $parameter->isOptional() ? $parameter->getDefaultValue() : null;

			$command->addArgument($name, $mode, "", $default);
		}
	}

	/**
	 * @param Command $command
	 */
	protected function addGlobalOptionsToCommand(Command $command)
	{
		// Register library related options for each command
		$command->addOption("config", "c", InputOption::VALUE_REQUIRED,
			"Path to JSON file containing library configuration.\n" .
			"https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI%3A-CLI-support#config-file"
		);

		$command->addOption("output", "o", InputOption::VALUE_REQUIRED,
			"Path to save JSON response output.\n" .
			"https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI%3A-CLI-support#output-file"
		);

		$command->addOption("extend", "x", InputOption::VALUE_NONE,
			"Use extended result format.\n" .
			"https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI%3A-CLI-support#extended-format"
		);

		$command->addOption("pretty", null, InputOption::VALUE_NONE,
			"Format request output."
		);
	}
}
