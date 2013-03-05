<?php

namespace skies\util;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.util
 */
class SessionUtil {


	public static function cleanUp() {

		$query = \Skies::$db->prepare('DELETE FROM `session` WHERE (`sessionLong` = :true AND (`sessionLastActivity` + :lengthLong) < :now) OR (`sessionLong` = :false AND (`sessionLastActivity` + :lengthShort))');

		// Normal sessions
		$query->execute([
			':true' => true,
			':false' => false,
		    ':lengthLong' => (365 * 86400),
		    ':lengthShort' => (30 * 60),
		    ':now' => NOW
		]);

	}

}

?>
