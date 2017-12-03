<?php

$config = include("config.php");

@ChromePhp::log(session_id());
// @ChromePhp::log("Session timeout countdown: " . ($_SESSION['expires'] - time()));
// @ChromePhp::log("Session id regenerate countdown: " . ($_SESSION['ID_expires'] - time()));
@ChromePhp::table($_SESSION);

function refreshSessionTimeout() {

	global $config;

	$_SESSION['obsolete'] = isset($_SESSION['expires']) && ($_SESSION['expires'] < time());

	if(!checkSession()) {
		session_unset();
		session_destroy();
	} else {
		$_SESSION['expires'] = time() + $config["session"]["expiration_time"]; // update last activity time stamp
	}

}

function checkSessionId($delete_old_session = false) {

	global $config;

	if (!isset($_SESSION['ID_expires'])) {
		$_SESSION['ID_expires'] = time() + $config["session"]["id_expiration_time"];
	} else if ($_SESSION['ID_expires'] < time()) {
		regenerateSession();
		$_SESSION['ID_expires'] = time() + $config["session"]["id_expiration_time"]; // update creation time
	}

}

function regenerateSession($reload = false) {

	global $config;

    // This token is used by forms to prevent cross site forgery attempts
	if(!isset($_SESSION['nonce']) || $reload) {
		$_SESSION['nonce'] = md5(microtime(true));
	}

	if(!isset($_SESSION['IPaddress']) || $reload) {
		$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
	}

	if(!isset($_SESSION['userAgent']) || $reload) {
		$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
	}

	//$_SESSION['user_id'] = $this->user->getId();

    // Set current session to expire in the time specified in the config file
	$_SESSION['obsolete'] = true;
	$_SESSION['expires'] = time() + $config["session"]["expiration_time"];

    // Create new session without destroying the old one
	session_regenerate_id(false);

    // Grab current session ID and close both sessions to allow other scripts to use them
	$newSession = session_id();
	session_write_close();

    // Set session ID to the new one, and start it back up again
	session_id($newSession);
	session_start();

    // Don't want this one to expire
	// unset($_SESSION['obsolete']);
	// unset($_SESSION['expires']);

}

function checkSession() {
	try{

		if($_SESSION['obsolete'] && ($_SESSION['expires'] < time())) {
			throw new Exception('Attempt to use expired session.');
		}

		// if(!is_numeric($_SESSION['user_id'])) {
		// 	throw new Exception('No session started.');
		// }

		if($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR']) {
			throw new Exception('IP Address mixmatch (possible session hijacking attempt).');
		}

		if($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']) {
			throw new Exception('Useragent mixmatch (possible session hijacking attempt).');
		}

		// if(!$this->loadUser($_SESSION['user_id'])) {
		// 	throw new Exception('Attempted to log in user that does not exist with ID: ' . $_SESSION['user_id']);
		// }

		checkSessionId($_SESSION['obsolete']);

		if(!$_SESSION['obsolete'] && mt_rand(1, 100) == 1) {
			regenerateSession();
		}
		
		return true;

	} catch(Exception $e) {
		ChromePhp::warn($e);
		return false;
	}
}

?>
