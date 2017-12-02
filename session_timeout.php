<?php

include('DebugTools/ChromePhp.php');

$config = include("config.php");

// if (!function_exists('refreshSessionTimeout')) {

function refreshSessionTimeout() {

	if (isSessionTimedout()) {
		// last request was more than the 'timeout' time value ago
		session_unset();
		session_destroy();
	}

	refreshSessionCreationTime();

}

// }

function isSessionTimedout() {

	ChromePhp::log($_SESSION);

	global $config;

	if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $config["session"]["timeout"])) {
		return true;
	}

	$_SESSION['last_activity'] = time(); // update last activity time stamp

	return false;
}

function refreshSessionCreationTime($delete_old_session = false) {

	global $config;

	if (!isset($_SESSION['creation_time'])) {
		$_SESSION['creation_time'] = time();
	} else if (time() - $_SESSION['creation_time'] > $config["session"]["timeout"]) {
		// session started more than the 'timeout' time ago		
		if (!headers_sent()) {
			session_regenerate_id($delete_old_session);// change session ID for the current session and invalidate old session ID
		} else {
			@session_regenerate_id($delete_old_session);
		}
		$_SESSION['creation_time'] = time(); // update creation time
	}

}


?>