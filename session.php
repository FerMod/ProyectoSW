<?php

include("config.php");

// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = new mysqli($servername, $user, $pass, $database);

session_start();// Starting Session

// Storing Session
$userCheck = $_SESSION['login_user'];

$result = $connection->query("SELECT * FROM usuarios WHERE email='$userCheck'");
$loggedSession = $result->fetch_assoc();

if(!isset($loggedSession['email'])){
	$connection->close(); // Closing Connection
	header('location: index.php'); // Redirecting To Home Page
}

?>
