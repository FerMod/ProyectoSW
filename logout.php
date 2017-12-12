<?php

session_start();

session_unset();
if(session_destroy()) { // destroy session data in storage
	header("location: layout.php"); // Redirect to home page
}

?>
