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

namespace RiotAPI\LeagueAPICLI\Commands;

use RiotAPI\LeagueAPICLI\Exceptions\InvalidOptionException;

use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\Exceptions\SettingsException;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *   Class InvokeMethodLeagueAPI
 *
 * @package RiotAPI\LeagueAPICLI\Commands
 */
class InvokeMethodLeagueAPI extends Command
{
	/** @var LeagueAPI $api */
	protected $api;

	/** @var \ReflectionMethod $methodRef */
	protected $methodRef;

	/**
	 * @param $methodRef
	 */
	public function setMethodRef(&$methodRef)
	{
		$this->methodRef = $methodRef;
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @throws InvalidOptionException
	 * @throws GeneralException
	 * @throws SettingsException
	 */
	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		$config_path = $input->getOption('config');
		if (!is_file($config_path) || !is_readable($config_path))
			throw new InvalidOptionException("Path to the library configuration ('$config_path') is not valid or the file is not readable.");

		$config = file_get_contents($config_path);
		$config = json_decode($config, true);

		$this->api = new LeagueAPI($config);
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function interact(InputInterface $input, OutputInterface $output)
	{
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @return int|void|null
	 *
	 * @throws InvalidOptionException
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$arguments = $input->getArguments();
		array_shift($arguments);

		$this->methodRef->invokeArgs($this->api, $arguments);
		$data = $this->api->getResult();

		if ($input->hasOption('extend') && $input->getOption('extend'))
			$this->extendResultFormat($data);

		$json_options = null;
		if ($input->hasOption('pretty') && $input->getOption('pretty'))
			$json_options = JSON_PRETTY_PRINT;

		$output_path = $input->getOption('output');
		if ($output_path)
		{
			if (!is_writeable($output_path))
				throw new InvalidOptionException("Path to the output file ('$output_path') is not valid or the file is not writeable.");

			file_put_contents($output_path, json_encode($data, $json_options));
		}

		$output->write(json_encode($data, $json_options));
	}

	/**
	 * @param $data
	 */
	protected function extendResultFormat(&$data)
	{
		$data = [
			"headers" => $this->api->getResultHeaders(),
			"limits" => $this->api->getCurrentLimits(),
			"result" => $data,
		];
	}
}