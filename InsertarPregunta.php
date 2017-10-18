<?php

include "config.php";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $database);
 
			// Check connection
			if ($conn->connect_error) {
			 	trigger_error("Database connection failed: "  . $conn->connect_error, E_USER_ERROR);
			} else {
				echo "Connection success." . PHP_EOL; // PHP_EOL The correct 'End Of Line' symbol for this platform
				echo "Host information: " . $conn->host_info . PHP_EOL;
			}
			
			$email = formatInput($_POST['email']) ?? '';
			$enunciado = formatInput($_POST['enunciado']) ?? '';
			$respuestaCorrecta = formatInput($_POST['respuestacorrecta']) ?? '';
			$respuestaIncorrecta = formatInput($_POST['respuestaincorrecta']) ?? '';
			$respuestaIncorrecta1 = formatInput($_POST['respuestaincorrecta1']) ?? '';
			$respuestaIncorrecta2 = formatInput($_POST['respuestaincorrecta2']) ?? '';
			$complejidad = formatInput($_POST['complejidad']) ?? '';
			$tema = formatInput($_POST['tema']) ?? '';

			$sql = "INSERT INTO preguntas('email', 'enunciado', 'respuesta_correcta', 'respuesta_incorrecta_1', 'respuesta_incorrecta_2', 'respuesta_incorrecta_3', 'complejidad', 'tema') VALUES( 
				'$email', '$enunciado', '$respuestaCorrecta',
				'$respuestaIncorrecta', '$respuestaIncorrecta1',
				$respuestaIncorrecta2, '$complejidad',
				'$tema')";
				
			if (!$result = $conn->query($sql)) {
				// Oh no! The query failed. 
				echo "Sorry, the website is experiencing problems." . PHP_EOL;
				echo "Error: Our query failed to execute and here is why: " . PHP_EOL;
				echo "Query: " . $sql . PHP_EOL;
				echo "Errno: " . $conn->errno . PHP_EOL;
				echo "Error: " . $conn->error . PHP_EOL;
			} else {
				echo "ERES EL PUTO AMO JODER";
				echo "Datos insertados."; 
			}
            

			// Close connection
			$conn->close();
			
			// Format the input for security reasons
	function formatInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

?>