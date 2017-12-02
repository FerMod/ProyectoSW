<?php

$config = include("config.php");

ini_set("session.cookie_lifetime", $config["session"]["timeout"]);
ini_set("session.gc_maxlifetime", $config["session"]["timeout"]);

// Adapted from the following source: https://www.formget.com/login-form-in-php/
session_id();
session_start(); // Starting Session

$errorMessage=""; // Variable To Store Error Message

if(isset($_POST['submit'])) {

	try {

		if(!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['password']) || empty($_POST['password'])) {
			throw new RuntimeException("<div class=\"serverInfoMessage\">Tanto el email como la contraseña deben ser introducidas para poder continuar.</div>");
		} else {

			// Establishing connection with server
			$connection = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

			// Check connection
			if ($connection->connect_error) {
				throw new RuntimeException("Database connection failed: " . $connection->connect_error);
			}

			// To protect MySQLi injection, for security purpose
			$email = stripslashes($_POST['email']);
			$password = stripslashes($_POST['password']);

			$email = $connection->real_escape_string($email);
			$password = $connection->real_escape_string($password);

			// SQL query to fetch information of registerd users and finds user match.
			$result = $connection->query("SELECT * FROM usuarios WHERE email='$email'");
			$loggedSession = $result->fetch_assoc();
			if(password_verify(hash("sha256", $password), $loggedSession['password']) && mysqli_num_rows($result) == 1) {

				$_SESSION['logged_user'] = $email; // Initializing session
				
				$_SESSION['user_type'] = ($email != "web000@ehu.es") ? 'student' : 'teacher';

			} else {
				throw new RuntimeException("<div class=\"serverErrorMessage\">El email o la contraseña introducida es incorrecta.</div>");
			}

			$connection->close(); // Closing connection

		}

	} catch (RuntimeException $e) {
		$errorMessage = $e->getMessage();
	}

}

?>
