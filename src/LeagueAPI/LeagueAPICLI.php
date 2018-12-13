<?php

/**
 *  CLI support.
 *
 *  First parameter:  function name (e.g. getChampions, getSummonerByName, …)
 *  Second parameter: first function parameter
 *  Third parameter:  second function parameter
 *  Fourth parameter: third function parameter
 *  …
 *  followed by CLI arguments
 *
 *  Usage example:
 *    php RiotAPI_CLI.php {method} {param 1} … {param x} {param x} {cli arg 1} {cli arg param 1} ...
 */

const
	ARG_CONFIG  = "--config",
	ARG_HELP    = "--help",
	ARG_OUTPUT  = "--output",
	ARG_VERBOSE = "--verbose";

const ARGS = [
	/*
	"argument name" => [
		[val required, "val datatype", "val default", "val name"],
	],
	"alias name"    => "argument name",
	*/
	ARG_HELP    => [],
	"-h"        => ARG_HELP,
	ARG_CONFIG  => [
		[ true, "string", null, "path" ],
	],
	"-c"        => ARG_CONFIG,
	ARG_OUTPUT  => [
		[ true, "string", null, "path" ],
	],
	"-o"        => ARG_OUTPUT,
	ARG_VERBOSE => [],
	"-v"        => ARG_VERBOSE,
];

//  CLI arg list
$args = ARGS;

//  CLI set args & vals
$argVals = [];

/**
 * @param bool|resource $target
 */
function printUsage( $target = STDOUT )
{
	global $argv;

	fprintf($target, "Usage:\n  %s %s <method> <param_1>…<param_N> %s <config_path> [option_1]…[option_N] \n", PHP_BINARY, $argv[0], ARG_CONFIG);
	fprintf($target, "\nFirst CLI script argument is API method name followed by N method arguments. Only method arguments without default value are mandatory, optional method arguments may be provided.");
	fprintf($target, "\nLibrary method name with arguments is followed by CLI script options. Option %s IS REQUIRED for library initialization.", ARG_CONFIG);
	fprintf($target, "\n\nLibrary config file is expected to be JSON array representation containing library settings. For more please see GitHub wiki pages (https://github.com/dolejska-daniel/riot-api/wiki/LeagueAPI:-CLI-support).");
	fprintf($target, "\n\nOptions:");
	$s = "  ";
	foreach (ARGS as $argName => $params)
	{
		$p = "";
		if (is_string($params))
		{
			$s.= "  ";
			$p.= " (alias)";
		}
		else
		{
			$s = "\n" . $s;
			foreach ($params as $argParam)
			{
				$req      = $argParam[0];
				$dataType = $argParam[1];
				$default  = $argParam[2];
				$name     = $argParam[3];

				if ($req)
					$p.= ' <' . $dataType . ' ' . $name . '>';
				else
					$p.= " [$dataType $name ($default)]";
			}
		}

		fprintf($target, "$s$argName$p");
		$s = "\n  ";
	}
	fprintf($target, "\n");
}

//  argument count check
if ($argc < 2 || is_string($argv[1]) == false)
{
	printUsage();
	die();
}

require __DIR__ . "/../../vendor/autoload.php";

use RiotAPI\LeagueAPI\Exceptions\InvalidMethodCLIException;
use RiotAPI\LeagueAPI\Exceptions\InvalidParameterCLIException;
use RiotAPI\LeagueAPI\Exceptions\MissingParameterCLIException;
use RiotAPI\LeagueAPI\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\LeagueAPI;

//  check for help arg
if (in_array("--help", $argv) || in_array("-h", $argv))
{
	printUsage();
	die();
}

//  get main arguments
$script       = $argv[0];
$methodName   = @$argv[1];
$methodParams = [];

//  setup reflections
$apiRef = new ReflectionClass(LeagueAPI::class);
if ($methodName == false || $apiRef->hasMethod($methodName) == false)
{
	throw new InvalidMethodCLIException("LeagueAPI library method name is " . ($methodName ? "invalid. Library method '$methodName' does not exist or is not accessible." : "missing."));
}

$method = $apiRef->getMethod($methodName);
$params = $method->getParameters();

//  check method params
$argIndex   = 2;
$paramIndex = 0;
for (; $argIndex < $argc; $argIndex++)
{
	if (isset($params[$paramIndex]) == false)
		//  method doesn't have more params
		break;

	$arg   = $argv[$argIndex];
	$param = $params[$paramIndex];

	if (strpos($arg, '-') === 0)
	{
		//  this is supposed to be CLI arg
		if ($param->isOptional() === false)
		{
			//  this param is not optional
			printUsage(STDERR);
			throw new MissingParameterCLIException("Required parameter '{$param->getName()}' for method '{$method->getName()}' is missing.");
		}
		else
		{
			//  this param is optional so lets ignore it
			break;
		}
	}

	if ($arg === "true")
		$arg = boolval(true);
	elseif ($arg === "false")
		$arg = (bool)false;
	elseif ($arg === "null")
		$arg = null;
	elseif (is_numeric($arg))
		$arg = (int)$arg;

	if ($param->getType() == 'array')
	{
		//  API expects array, try to decode array
		$data = @json_decode($arg);
		if ($data == false)
			throw new InvalidParameterCLIException("Parameter '{$param->getName()}' for method '{$method->getName()}' failed to be parsed as JSON string. Expected JSON string, got '$arg'");
		$arg = $data;
	}
	elseif ($param->getType() == 'object')
	{
		//  API expects object, try to decode object
		$data = @unserialize($arg);
		if ($data == false)
			throw new InvalidParameterCLIException("Parameter '{$param->getName()}' for method '{$method->getName()}' failed to be parsed as PHP serialized object. Expected PHP serialized object, got '$arg'");
		$arg = $data;
	}

	$expectedDataType = (string)$param->getType();
	$dataType = gettype($arg);

	if (strpos($dataType, $expectedDataType) === false)
		throw new InvalidParameterCLIException("Parameter '{$param->getName()}' for method '{$method->getName()}' is invalid. Expected '{$param->getType()}' got '$dataType'.");

	$methodParams[] = $arg;

	$paramIndex++;
}

//  check cli args
for (; $argIndex < $argc; $argIndex++)
{
	$arg = $argv[$argIndex];

	if (strpos($arg, '-') === false || isset($args[$arg]) === false)
	{
		//  this is supposed to be CLI arg, but isn't or is invalid
		printUsage();
		die();
	}

	$argumentParams = $args[$arg];
	$argument       = $arg;
	if (is_string($argumentParams))
	{
		//  this is just alias
		$argument       = $argumentParams;
		$argumentParams = $args[$argument];
	}

	if (empty($argumentParams))
	{
		//  this argument does not have any params
		$argVals[$argument] = true;
	}
	else
	{
		$argOrig = $arg;
		$argParamCount = count(array_keys($argumentParams));
		foreach ($argumentParams as $argParam)
		{
			$argIndex++;

			if (isset($argv[$argIndex]) == false)
				$arg = null;
			else
				$arg = $argv[$argIndex];

			$req      = $argParam[0];
			$dataType = $argParam[1];
			$default  = $argParam[2];
			$name     = $argParam[3];

			if (strpos($arg, '-') === 0 || is_null($arg))
			{
				//  this is supposed to be CLI arg
				if ($req === true)
				{
					//  this param is not optional
					printUsage(STDERR);
					throw new MissingParameterCLIException("Required parameter '$name' for CLI argument '$argOrig' is missing.");
				}
				else
				{
					//  this param is optional, save it's default value
					if ($argParamCount > 1)
						$argVals[$argument][] = $default;
					else
						$argVals[$argument] = $default;

					$argIndex--;
					continue;
				}
			}

			if (gettype($arg) != $dataType)
				throw new MissingParameterCLIException("Parameter '$name' for CLI argument '$argOrig' is invalid (data type).");

			if ($argParamCount > 1)
				$argVals[$argument][] = $arg;
			else
				$argVals[$argument] = $arg;
		}
	}
}

if (@$argVals[ARG_HELP])
{
	printUsage();
	die();
}

$cfg = @$argVals[ARG_CONFIG];
$cfg = @file_get_contents($cfg);
if ($cfg == false || ($cfg = json_decode($cfg, true)) == false)
	throw new GeneralException('Config file could not be loaded.');

$api    = new LeagueAPI($cfg);
$result = $method->invokeArgs($api, $methodParams);
$data   = json_encode($result, JSON_PRETTY_PRINT);

$outputPath = @$argVals[ARG_OUTPUT];
if ($outputPath)
{
	$f = fopen($outputPath, 'w');
	if ($f == false)
		throw new GeneralException("Requested data failed to be saved to output file '$outputPath'.");

	fwrite($f, $data);
	fclose($f);
}
else
	fprintf(STDOUT, $data);