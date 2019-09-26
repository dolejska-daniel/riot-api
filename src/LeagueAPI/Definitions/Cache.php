<?php


namespace RiotAPI\LeagueAPI\Definitions;


/**
 *   Class Cache
 *
 * @package RiotAPI\LeagueAPI\Definitions
 */
class Cache
{
	/**
	 * @var string
	 */
	public const GLOBAL_NAMESPACE = "RiotAPI";

	/**
	 * @var string
	 */
	public const LEAGUEAPI_NAMESPACE = "LeagueAPI.cache";

	/**
	 * @var string
	 */
	public const DATADRAGON_NAMESPACE = "DataDragonAPI.cache";

	/**
	 * @var integer
	 */
	public const LIFETIME = 0;

	/**
	 * @return string
	 */
	public static function getDirectoryPath()
	{
		return sys_get_temp_dir() . "/" . self::GLOBAL_NAMESPACE;
	}
}