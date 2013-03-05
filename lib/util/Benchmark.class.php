<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
 
namespace skies\util;

/**
 * Util class for timings
 */
class Benchmark {

	/**
	 * Start of generation
	 *
	 * @var float
	 */
	protected static $start = 0.0;

	public static function start() {

		self::$start = microtime(true);

	}

	/**
	 * How much time has passed since we started the benchmark?
	 *
	 * @return float
	 */
	public static function getGenerationTime() {

		return microtime(true) - self::$start;

	}

}
