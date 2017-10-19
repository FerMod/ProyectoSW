<?php

include "config.php";
$control = null;
$urlid = null;

	function connectToDB () {

	// Create connection
		$conn = new mysqli($servername, $username, $password, $database);
 
		// Check connection
		if ($conn->connect_error) {
		 	trigger_error("Database connection failed: "  . $conn->connect_error, E_USER_ERROR);
		} else {
			echo "Connection success." . PHP_EOL; // PHP_EOL The correct 'End Of Line' symbol for this platform
			echo "Host information: " . $conn->host_info . PHP_EOL;
		}
			
	}

	function insertDataDB() {
		//Insert data of quizes.php
		$email = formatInput($_POST['email']) ?? '';
		$enunciado = formatInput($_POST['enunciado']) ?? '';
		$respuestaCorrecta = formatInput($_POST['respuestacorrecta']) ?? '';
		$respuestaIncorrecta = formatInput($_POST['respuestaincorrecta']) ?? '';
		$respuestaIncorrecta1 = formatInput($_POST['respuestaincorrecta1']) ?? '';
		$respuestaIncorrecta2 = formatInput($_POST['respuestaincorrecta2']) ?? '';
		$complejidad = formatInput($_POST['complejidad']) ?? '';
		$tema = formatInput($_POST['tema']) ?? '';

		$sql = "INSERT INTO preguntas(email, enunciado, respuesta_correcta, respuesta_incorrecta_1, respuesta_incorrecta_2, respuesta_incorrecta_3, complejidad, tema)
			VALUES('$email', '$enunciado', '$respuestaCorrecta', '$respuestaIncorrecta', '$respuestaIncorrecta1', '$respuestaIncorrecta2', $complejidad, '$tema')";
			
		if (!$result = $conn->query($sql)) {
			// Oh no! The query failed. 
			$control = "La pregunta no se ha insertado correctamente debido a un error con la base de datos. Inténtelo de nuevo.";
		} else {
			$sql = "SELECT id FROM preguntas WHERE preguntas.enunciado='$enunciado'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$control = "La pregunta se ha insertado correctamente. Para verla haga click ";
			$urlid = "/VerPreguntas.php?id=" . $row["id"];
		}
	}
	
	// Format the input for security reasons
	function formatInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
			return $data;
	}
	
	function echoControl() {
		echo $control;
	}
	
	function echoUrl() {
		echo $urlid;
	}
?>