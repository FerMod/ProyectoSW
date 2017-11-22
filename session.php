<?php

require_once("login_session.php");
if(!isset($_SESSION['logged_user']) || empty($_SESSION['logged_user'])) {
	header("location: layout.php");
}

include("config.php");

// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = new mysqli($servername, $user, $pass, $database);

// Storing Session
$userCheck = $_SESSION['logged_user'];

$result = $connection->query("SELECT * FROM usuarios WHERE email='$userCheck'");
$loggedSession = $result->fetch_assoc();

if(!isset($loggedSession['email'])){
	$connection->close(); // Closing Connection
	header('location: layout.php'); // Redirecting To Home Page
}

?>
