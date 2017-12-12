<?php

$config = include("config.php");

// @ChromePhp::log(session_id());
// @ChromePhp::log("Session timeout countdown: " . ($_SESSION['expires'] - time()));
// @ChromePhp::log("Session id regenerate countdown: " . ($_SESSION['ID_expires'] - time()));
// @ChromePhp::table($_SESSION);

function refreshSessionTimeout() {

	global $config;	

	if(!isValidSession()) {
		session_unset();
		session_destroy();
	} else {
		$_SESSION['expires'] = time() + $config["session"]["expiration_time"]; // update last activity time stamp
		$_SESSION['obsolete'] = haveSessionExpired();
	}

}

function haveSessionExpired() {
	return isset($_SESSION['expires']) && ($_SESSION['expires'] < time());
}

function haveSessionIdExpired() {
	return isset($_SESSION['ID_expires']) && ($_SESSION['ID_expires'] < time());
}

function isValidSession() {
	try{

		if(!isset($_SESSION['logged_user'])) {
			throw new Exception('Session not started.');
		}

		$_SESSION['obsolete'] = haveSessionExpired();

		if($_SESSION['obsolete']) {
			throw new Exception('Attempt to use a obsolete session.');
		}

		if(!isset($_SESSION['IPaddress'])) {
			throw new Exception('IP address mixmatch (value not set).');
		} else if($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR']) {
			throw new Exception('IP address mixmatch (possible session hijacking attempt).');
		}

		if(!isset($_SESSION['userAgent'])) {
			throw new Exception('UserAgent mixmatch (value not set).');
		} else if($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']) {
			throw new Exception('UserAgent mixmatch (possible session hijacking attempt).');
		}

		// if(!$this->loadUser($_SESSION['user_id'])) {
		// 	throw new Exception('Attempted to log in user that does not exist with ID: ' . $_SESSION['user_id']);
		// }

		// If session is not obsolete, there is a chance to regenerate the session
		if(!$_SESSION['obsolete'] && mt_rand(1, 100) == 1) {
			regenerateSession();
		}

		if(haveSessionExpired()){
			//ChromePhp::log($_SESSION['obsolete']);
			regenerateSession($_SESSION['obsolete']);
		}
		
		return true;

	} catch(Exception $e) {
		//ChromePhp::info($e);
		return false;
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
	$_SESSION['ID_expires'] = time() + $config["session"]["id_expiration_time"]; // update creation time

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

?>
