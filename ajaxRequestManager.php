<?php

include_once('login_session.php');
include_once('session_timeout.php');

$config = include("config.php");

if(isset($_POST['action']) && !empty($_POST['action'])) {
	$action = $_POST['action'];
	$ajaxResult = array();
	switch($action) {
		case 'uploadQuestion':
		$ajaxResult = uploadQuestion();
		break;

		case 'getOnlineUsers':
		$ajaxResult = getOnlineUsers(); //TODO
		break;

		case 'getQuestionsStats':
		$ajaxResult = getQuestionsStats();
		break;

		case 'isVIPUser':
		$ajaxResult = isVIPUser();
		break;

		case 'showQuestions':
		$ajaxResult = showQuestions(); //TODO
		break;
		
		case 'checkPassword':
		$ajaxResult = checkPassword();
		break;

		case 'editQuestion':
		$ajaxResult = editQuestion();
		break;
	}

	if($action != "getQuestionsStats" && $action != "getOnlineUsers") {
		refreshSessionTimeout();
	}

	$ajaxResult["sessionTimeout"] = isSessionTimedout();

	// Encode array to JSON format
	echo json_encode($ajaxResult);

}

function uploadQuestion() {

	global $config;

	// Create connection
	$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

	// Check connection
	if ($conn->connect_error) {
		trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
	}

	$operationMessage = "";
	$uploadOk = true;
	$imagenPregunta = null;

	try {

		$dataCheckMessage = "";

			//Insert data of quizes.php
		if(isset($_POST['email']) && !empty($_POST['email'])) { 
			$email = formatInput($_POST['email']) ?? '';
			if(!isValidEmail($email)) {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El formato del email no es correcto.<br>Debe cumplir el formato de la UPV/EHU.</div><br>";
			}
		} else {
			$uploadOk = false;
			$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Email\" no puede ser vacío.</div>";
		}

		if(isset($_POST['enunciado']) && !empty($_POST['enunciado'])) { 
			$enunciado = formatInput($_POST['enunciado']) ?? '';
		} else {
			$uploadOk = false;
			$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Enunciado\" no puede ser vacío.</div>";
		}

		if(isset($_POST['respuestacorrecta']) && !empty($_POST['respuestacorrecta'])) { 
			$respuestaCorrecta = formatInput($_POST['respuestacorrecta']) ?? '';
		} else {
			$uploadOk = false;
			$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta correcta\" no puede ser vacio.</div>";
		}

		if(isset($_POST['respuestaincorrecta1']) && !empty($_POST['respuestaincorrecta1'])) { 
			$respuestaIncorrecta1 = formatInput($_POST['respuestaincorrecta1']) ?? '';
		} else {
			$uploadOk = false;
			$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta incorrecta 1\" no puede ser vacio.</div>";
		}

		if(isset($_POST['respuestaincorrecta2']) && !empty($_POST['respuestaincorrecta2'])) { 
			$respuestaIncorrecta2 = formatInput($_POST['respuestaincorrecta2']) ?? '';
		} else {
			$uploadOk = false;
			$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta incorrecta 2\" no puede ser vacio.</div>";
		}

		if(isset($_POST['respuestaincorrecta3']) && !empty($_POST['respuestaincorrecta3'])) { 
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
		if(isset($_POST['complejidad']) && !empty($_POST['complejidad'])) {
			$complejidad = formatInput($_POST['complejidad']) ?? 0;
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
			throw new RuntimeException($dataCheckMessage);
		}

		// Undefined | Multiple Files | $_FILES Corruption Attack
		// If this request falls under any of them, treat it invalid.
		if (!isset($_FILES['imagen']['error']) || is_array($_FILES['imagen']['error'])) {
			throw new RuntimeException("<div class=\"serverErrorMessage\">Parametros inválidos.</div>");
		}

		$containsImage = false;

		// Check $_FILES['imagen']['error'] value.
		switch ($_FILES['imagen']['error']) {
			case UPLOAD_ERR_OK:
			$containsImage = true;
			case UPLOAD_ERR_NO_FILE:
			//Nothing to do here, the file upload is optional
			break;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
			throw new RuntimeException("<div class=\"serverErrorMessage\">Tamaño de archivo excedido.</div>");
			default:
			throw new RuntimeException("<div class=\"serverErrorMessage\">Error desconocido.</div>");
		}

		if($containsImage) {

			// You should also check filesize here. 
			if ($_FILES['imagen']['size'] > 1000000) {
				throw new RuntimeException("<div class=\"serverErrorMessage\">Tamaño de archivo excedido.");
			}

			// DO NOT TRUST $_FILES['imagen']['mime'] VALUE !!
			// Check MIME Type by yourself.
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if (false === $ext = array_search(
				$finfo->file($_FILES['imagen']['tmp_name']),
				array(
					'jpg' => 'image/jpeg',
					'png' => 'image/png',
					'gif' => 'image/gif',
				),
				true
			)) {
				throw new RuntimeException("<div class=\"serverErrorMessage\">Formato de archivo inválido.</div>");
			}

			// You should name it uniquely.
			// DO NOT USE $_FILES['imagen']['name'] WITHOUT ANY VALIDATION !!
			// On this example, obtain safe unique name from its binary data.
			$sha1Name = sha1_file($_FILES['imagen']['tmp_name']);
			if (!move_uploaded_file(
				$_FILES['imagen']['tmp_name'],
				sprintf('%s%s.%s',
					$imageUploadFolder,
					$sha1Name,
					$ext
				)
			)) {
				throw new RuntimeException("<div class=\"serverErrorMessage\">Fallo al mover el archivo.</div>");
			}

			$imagenPregunta = sprintf("%s%s.%s", $imageUploadFolder, $sha1Name, $ext);

		}

	} catch (RuntimeException $e) {

		$uploadOk = false;
		$operationMessage .= $e->getMessage();

	}

	if($uploadOk) {
		$sql = "INSERT INTO preguntas(email, enunciado, respuesta_correcta, respuesta_incorrecta_1, respuesta_incorrecta_2, respuesta_incorrecta_3, complejidad, tema, imagen)
		VALUES('$email', '$enunciado', '$respuestaCorrecta', '$respuestaIncorrecta1', '$respuestaIncorrecta2', '$respuestaIncorrecta3', $complejidad, '$tema',  '$imagenPregunta')";

		if (!$result = $conn->query($sql)) {
			// Oh no! The query failed. 
			$operationMessage .= "<div class=\"serverErrorMessage\">La pregunta no se ha insertado correctamente debido a un error con la base de datos.</div>Presione el botón de volver e inténtelo de nuevo.";
		} else {
			$filePath = sprintf("%s%s", $config["folders"]["xml"], "preguntas.xml");
			$last_id = $conn->insert_id;
			insertElement($filePath, $last_id, $email, $enunciado, $respuestaCorrecta, $respuestaIncorrecta1, $respuestaIncorrecta2, $respuestaIncorrecta3, $complejidad, $tema, $imagenPregunta);
			$operationMessage .= "<div class=\"serverInfoMessage\">La pregunta se ha insertado correctamente. 
			<br>Para verla haga click <a href='VerPreguntasConFoto.php' target='_self'>aquí</a>. 
			<br><br>O si prefiere ver el archivo '.xml' generado haga click <a href='$filePath' target='_blank'>aquí</a>.</div>";
		}

		// Close connection
		$conn->close();

	} else {
		$operationMessage .= "<br>Revise los datos introducidos e inténtelo de nuevo.";
	}

	// Create array with the operation information
	$array = array(
		"operationSuccess" => $uploadOk,
		"operationMessage" => $operationMessage,
	);
	
	// Encode array to JSON format
	return $array;
}

// Format the input for security reasons
function formatInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[a-zA-Z]+\\d{3}@ikasle\.ehu\.(eus|es)$/', $email);
}

function insertElement($filePath, $id, $author, $question, $correctResponse, $incorrectResponse1, $incorrectResponse2, $incorrectResponse3, $complexity, $subject, $image) {

	$xml = new SimpleXMLElement($filePath, 0, true);

	$assessmentItemElement = $xml->addChild("assessmentItem");
	$assessmentItemElement->addAttribute("id", $id);
	$assessmentItemElement->addAttribute("complexity", $complexity);
	$assessmentItemElement->addAttribute("subject", $subject);
	$assessmentItemElement->addAttribute("author", $author);

	$itemBodyElement = $assessmentItemElement->addChild("itemBody");
	$itemBodyElement->addChild("p", $question);

	$correctResponseElement = $assessmentItemElement->addChild("correctResponse");
	$correctResponseElement->addChild("value", "$correctResponse");
	
	$incorrectResponsesElement = $assessmentItemElement->addChild("incorrectResponses");
	$incorrectResponsesElement->addChild("value", $incorrectResponse1);
	$incorrectResponsesElement->addChild("value", $incorrectResponse2);
	$incorrectResponsesElement->addChild("value", $incorrectResponse3);

	$assessmentItemElement->addChild("image", $image);
	
	$xml->asXML($filePath);

	formatFileStyle($filePath);
}

function formatFileStyle($filePath) {
	$dom = new DOMDocument("1.0", "UTF-8");
	$dom->preserveWhiteSpace = false;		
	$dom->formatOutput = true;
	$dom->load($filePath);
	$dom->save($filePath);
}

function getOnlineUsers() {

	$onlineUsers = 0;

	echo $onlineUsers;
}

function getQuestionsStats() {

	if(!isSessionTimedout()) {

		include("session.php");

		$xml = new SimpleXMLElement($config["folders"]["xml"] . "preguntas.xml", 0, true);
		$preguntasTotal = count($xml->xpath("/assessmentItems/assessmentItem"));
		$preguntasUsuario = count($xml->xpath("/assessmentItems/assessmentItem[@author=\"" . $loggedSession['email'] . "\"]"));

		// Create array with the operation information
		return array(
			"quizesTotal" => $preguntasTotal,
			"quizesUser" => $preguntasUsuario
		);

	}
	
}

/*
function isVIPUser() {

	require_once("nusoap-0.9.5/src/nusoap.php");

	// Create new NuSoap client. First parameter is the wsdl url and the second parameter is to confirm that is a wsdl url
	$client = new nusoap_client("http://ehusw.es/jav/ServiciosWeb/comprobarmatricula.php?wsdl", true);
	$client->soap_defencoding = "UTF-8";
	$client->decode_utf8 = false;

	// Call and consume service
	$result = strtoupper($client->call("comprobar",  array("x"=>$_POST["email"]))) !== "NO" ? true : false;

	return array(
		"isVip" => $result
	);

}
*/

function checkPassword() {
	
	require_once('nusoap-0.9.5/src/nusoap.php');

	$soapclient = new nusoap_client('http://localhost/ProyectoSW/ComprobarContrasena.php?wsdl', true);

	$result = strtoupper($soapclient->call("checkPass", array('password'=>$_POST["password"]))) !== 'INVALIDA' ? true : false;

	return array(
		"isValid" => $result
	);

}

function editQuestion() {
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
		throw new RuntimeException($dataCheckMessage);
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
		$operationMessage .= "<br>Revise los datos introducidos e inténtelo de nuevo.";
	}

	// Close connection
	$conn->close();

	// Create array with the operation information
	$array = array(
		"operationSuccess" => $uploadOk,
		"operationMessage" => $operationMessage,
	);
	
	// Encode array to JSON format
	return $array;
}
	
?>
