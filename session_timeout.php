<?php

// if (!function_exists('refreshSessionTimeout')) {

function refreshSessionTimeout() {

	$config = include("config.php");

	if (isSessionTimedout()) {
		// last request was more than the 'timeout' time value ago
		session_unset();
		session_destroy();
	}
	$_SESSION['last_activity'] = time(); // update last activity time stamp

	if (!isset($_SESSION['creation_time'])) {
		$_SESSION['creation_time'] = time();
	} else if (time() - $_SESSION['creation_time'] > $config["session"]["timeout"]) {
		// session started more than the 'timeout' time ago		
		if (!headers_sent()) {
			session_regenerate_id(true);// change session ID for the current session and invalidate old session ID
		} else {
			@session_regenerate_id(true);
		}
		$_SESSION['creation_time'] = time(); // update creation time
	}

}

// }

function isSessionTimedout() {
	$config = include("config.php");
	return isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $config["session"]["timeout"]);
}


?>