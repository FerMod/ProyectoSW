<?php
	$config = include("config.php");

	// Create connection
	$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

	// Check connection
	if ($conn->connect_error) {
		trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
	}

	$operationMessage = "";
	$uploadOk = true;

	$dataCheckMessage = "";

	$email = formatInput($_POST['emailed']) ?? '';
	$id = $_POST['ided'];

	if(isset($_POST['enunciadoed']) && !empty($_POST['enunciadoed'])) { 
		$enunciado = formatInput($_POST['enunciadoed']) ?? '';
	} else {
		$uploadOk = false;
		$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Enunciado\" no puede ser vacío.</div>";
	}

	if(isset($_POST['respuestacorrectaed']) && !empty($_POST['respuestacorrectaed'])) { 
		$respuestaCorrecta = formatInput($_POST['respuestacorrectaed']) ?? '';
	} else {
		$uploadOk = false;
		$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta correcta\" no puede ser vacio.</div>";
	}

	if(isset($_POST['respuestaincorrecta1ed']) && !empty($_POST['respuestaincorrecta1ed'])) { 
		$respuestaIncorrecta1 = formatInput($_POST['respuestaincorrecta1ed']) ?? '';
	} else {
		$uploadOk = false;
		$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta incorrecta 1\" no puede ser vacio.</div>";
	}

	if(isset($_POST['respuestaincorrecta2ed']) && !empty($_POST['respuestaincorrecta2ed'])) { 
		$respuestaIncorrecta2 = formatInput($_POST['respuestaincorrecta2']) ?? '';
	} else {
		$uploadOk = false;
		$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta incorrecta 2\" no puede ser vacio.</div>";
	}

	if(isset($_POST['respuestaincorrecta3ed']) && !empty($_POST['respuestaincorrecta3ed'])) { 
		$respuestaIncorrecta3 = formatInput($_POST['respuestaincorrecta3']) ?? '';
	} else {
		$uploadOk = false;
		$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta incorrecta 3\" no puede ser vacio.</div>";
	}

	/*
	 * In the next variable, will be checked differently because of the following reasons:
	 *
	 * (http://php.net/empty)
	 * The following things are considered to be empty:
	 *
	 * "" (an empty string)
	 * 0 (0 as an integer)
	 * 0.0 (0 as a float)
	 * "0" (0 as a string)
	 * NULL
	 * FALSE
	 * array() (an empty array)
	 * $var; (a variable declared, but without a value)
	 *
	 */
	if(isset($_POST['complejidaded']) && !empty($_POST['complejidaded']) || $_POST['complejidaded'] != 0) { 
		$complejidad = formatInput($_POST['complejidaded']) ?? '';
		if(!is_numeric($complejidad)) {
			$uploadOk = false;
			$dataCheckMessage .= "<div class=\"serverMessage\">El valor del campo \"Complejidad\" debe ser un número.</div>";
		} else if($complejidad < 1 || $complejidad > 5){
			$uploadOk = false;
			$dataCheckMessage .= "<div class=\"serverMessage\">El valor del campo \"Complejidad\" debe estar entre el 1 y el 5, ambos inclusive.</div>";
		}
	} else {
		$uploadOk = false;
		$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Complejidad\" no puede ser vacio.</div>";
	}
		

	if(isset($_POST['tema']) && !empty($_POST['tema'])) { 
		$tema = formatInput($_POST['tema']) ?? '';
	} else {
		$uploadOk = false;
		$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Tema\" no puede ser vacio.</div>";
	}

	// Check if everything is ok
	if (!$uploadOk) {
		echo $dataCheckMessage;
	}

	if($uploadOk) {
		$sql = "UPDATE preguntas
			SET enunciado = '$enunciado', respuesta_correcta = '$respuestaCorrecta', respuesta_incorrecta_1 = '$respuestaIncorrecta1', respuesta_incorrecta_2 = '$respuestaIncorrecta2', respuesta_incorrecta_3 = '$respuestaIncorrecta3', complejidad = '$complejidad', tema = '$tema' 
				WHERE email = '$email' AND id = '$id'";

		if (!$result = $conn->query($sql)) {
			// Oh no! The query failed. 
			$operationMessage .= "<div class=\"serverErrorMessage\">La pregunta no se ha actualizado correctamente debido a un error con la base de datos.</div>Presione el botón de volver e inténtelo de nuevo.";
		} else {
			$preguntas = simplexml_load_file('xml/preguntas.xml');

			foreach ($preguntas->assessmentItem as $pregunta) {
				if($id == $pregunta['id']) {
					$pregunta['complexity'] = $complejidad;
					$pregunta['subject'] = $tema;
					$pregunta->itemBody->p = $enunciado;
					$pregunta->correctResponse->value = $respuestaCorrecta;
					$incorrect = $pregunta->incorrectResponses;
					$j = 1;
					$incorrect->value[0] = $respuestaIncorrecta1;
					$incorrect->value[1] = $respuestaIncorrecta2;
					$incorrect->value[2] = $respuestaIncorrecta3;
					break;
				}
			}

			$preguntas->asXML('xml/preguntas.xml');
			$operationMessage .= "<div class=\"serverInfoMessage\">La pregunta se ha actualizado correctamente.</div>";
			$operationMessage .=
			 '<script>
			 	$("#'.$id.'comp").html("Complejidad: '.$complejidad.' | Tema: '.$tema.' | Autor: '.$email.'");
			 	$("#'.$id.'preg").html("Enunciado: '.$enunciado.'");
			 	$("#'.$id.'cor").html("+Respuesta correcta: '.$respuestaCorrecta.'");
			 	$("#'.$id.'incor1").html("-Respuesta incorrecta 1: '.$respuestaIncorrecta1.'");
			 	$("#'.$id.'incor2").html("-Respuesta incorrecta 2: '.$respuestaIncorrecta2.'");
			 	$("#'.$id.'incor3").html("-Respuesta incorrecta 3: '.$respuestaIncorrecta3.'");
			  </script>';
		}

	} else {
		$operationMessage .= "<br>Actualice la página e inténtelo de nuevo.";
	}

	// Close connection
	$conn->close();

	echo $operationMessage;


	// Format the input for security reasons
	function formatInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?>