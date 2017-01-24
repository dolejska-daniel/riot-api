<?php

namespace Fixes;


class Functions
{
	public static function fix()
	{
		if (!function_exists('http_parse_headers'))
		{
			function http_parse_headers( $string )
			{
				$r = array();
				foreach (explode("\r\n", $string) as $line)
				{
					if (strpos($line, ':'))
					{
						$e = explode(": ", $line);
						$r[$e[0]] = @$e[1];
					}
					elseif (strlen($line))
						$r[] = $line;
				}
				return $r;
			}
		}
	}
}

Functions::fix();