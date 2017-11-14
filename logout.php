<?php
session_start();
if(session_destroy()) { // Destroy all sessions
	header("location: layout.php"); // Redirect to home page
}
?>
