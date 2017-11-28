<?php

function checkSessionTimeout() {
	
	$config = include("config.php");

	if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $config["session"]["timeout"])) {
		// last request was more than the 'timeout' time value ago
		include("logout.php");
	}
	$_SESSION['last_activity'] = time(); // update last activity time stamp

	if (!isset($_SESSION['creation_time'])) {
		$_SESSION['creation_time'] = time();
	} else if (time() - $_SESSION['creation_time'] > $config["session"]["timeout"]) {
		// session started more than the 'timeout' time ago
		session_regenerate_id(true); // change session ID for the current session and invalidate old session ID
		$_SESSION['creation_time'] = time(); // update creation time
	}
	
}

?>