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