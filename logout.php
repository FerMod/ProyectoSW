<?php

session_start();

if(session_destroy()) { // destroy session data in storage
	header("location: layout.php"); // Redirect to home page
}

?>
