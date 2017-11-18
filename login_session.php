<?php

// Adapted from the following source: https://www.formget.com/login-form-in-php/
include("config.php");

session_start(); // Starting Session

$errorMessage=""; // Variable To Store Error Message

if(isset($_POST['submit'])) {

	try {

		if(!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['password']) || empty($_POST['password'])) {
			throw new RuntimeException("<div class=\"serverInfoMessage\">Tanto el email como la contraseña deben ser introducidas para poder continuar.</div>");
		} else {

			// Establishing connection with server
			$connection = new mysqli($servername, $user, $pass, $database);

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
			if(password_verify($password, $loggedSession['password']) && mysqli_num_rows($result) == 1) {
				$_SESSION['login_user'] = $email; // Initializing session
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
