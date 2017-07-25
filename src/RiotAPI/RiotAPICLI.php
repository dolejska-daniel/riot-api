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
	ARG_CONFIG  => [
		[ true, "string", null, "path" ],
	],
	"-c"        => ARG_CONFIG,
	ARG_HELP    => [],
	"-h"        => ARG_HELP,
	ARG_OUTPUT  => [
		[ true, "string", null, "path" ],
	],
	"-o"        => ARG_OUTPUT,
	/*
	ARG_VERBOSE => [],
	"-v"        => ARG_VERBOSE,
	*/
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
	fprintf($target, "\nUsage:\n  php RiotAPI_CLI.php {method} {param 1} ... {param x} {cli arg 1} {cli arg param 1} ... \n");
	fprintf($target, "\nCLI args:");
	foreach (ARGS as $argNname => $params)
	{
		$s = "\n  ";
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
					$p.= ' {' . $dataType . ' ' . $name . '}';
				else
					$p.= " [$dataType $name ($default)]";
			}
		}

		fprintf($target, "$s$argNname$p");
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

use RiotAPI\RiotAPI;

//  check for help arg
if ($argv[1] === "--help" || $argv[1] === "-h")
{
	printUsage();
	die();
}

//  get main arguments
$script       = $argv[0];
$methodName   = $argv[1];
$methodParams = [];

//  setup reflections
$apiRef = new ReflectionClass(RiotAPI::class);
$method = $apiRef->getMethod($methodName);
$params = $method->getParameters();

//  check method params
$argIndex   = 2;
$paramIndex = 0;
for ($argIndex; $argIndex < $argc; $argIndex++)
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
			throw new Exception("Required parameter '{$param->getName()}' for method '{$method->getName()}' is missing.");
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

	$expectedDataType = (string)$param->getType();
	$dataType = gettype($arg);

	if (strpos($dataType, $expectedDataType) === false)
		throw new Exception("Parameter '{$param->getName()}' for method '{$method->getName()}' is invalid. Expected '{$param->getType()}' got '$dataType'.");

	$methodParams[] = $arg;

	$paramIndex++;
}

//  check cli args
for ($argIndex; $argIndex < $argc; $argIndex++)
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
					throw new Exception("Required parameter '$name' for CLI argument '$argOrig' is missing.");
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
				throw new Exception("Parameter '$name' for CLI argument '$argOrig' is invalid (data type).");

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
	throw new Exception('Config file could not be loaded.');

$api    = new RiotAPI($cfg);
$result = $method->invokeArgs($api, $methodParams);
$data   = json_encode($result, JSON_PRETTY_PRINT);

$outputPath = @$argVals[ARG_OUTPUT];
if ($outputPath)
{
	$f = fopen($outputPath, 'w');
	if ($f == false)
		throw new Exception("Requested data failed to be saved to output file '$outputPath'.");

	fwrite($f, $data);
	fclose($f);
}
else
	fprintf(STDOUT, $data);