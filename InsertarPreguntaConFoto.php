<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">	

	<?php

	function uploadQuestion() {

		include "config.php";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $database);

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
					$dataCheckMessage .= "El formato del email no es correcto.<br>Debe cumplir el formato de la UPV/EHU.<br>";
				}
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "El campo de \"Email\" no puede ser vacio.<br>";
			}

			if(isset($_POST['enunciado']) && !empty($_POST['enunciado'])) { 
				$enunciado = formatInput($_POST['enunciado']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "El campo de \"Enunciado\" no puede ser vacio.<br>";
			}

			if(isset($_POST['respuestacorrecta']) && !empty($_POST['respuestacorrecta'])) { 
				$respuestaCorrecta = formatInput($_POST['respuestacorrecta']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "El campo de \"Respuesta correcta\" no puede ser vacio.<br>";
			}

			if(isset($_POST['respuestaincorrecta1']) && !empty($_POST['respuestaincorrecta1'])) { 
				$respuestaIncorrecta = formatInput($_POST['respuestaincorrecta1']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "El campo de \"Respuesta incorrecta 1\" no puede ser vacio.<br>";
			}

			if(isset($_POST['respuestaincorrecta2']) && !empty($_POST['respuestaincorrecta2'])) { 
				$respuestaIncorrecta1 = formatInput($_POST['respuestaincorrecta2']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "El campo de \"Respuesta incorrecta 2\" no puede ser vacio.<br>";
			}

			if(isset($_POST['respuestaincorrecta3']) && !empty($_POST['respuestaincorrecta3'])) { 
				$respuestaIncorrecta2 = formatInput($_POST['respuestaincorrecta3']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "El campo de \"Respuesta incorrecta 3\" no puede ser vacio. <br>";
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
			if(isset($_POST['complejidad']) && !empty($_POST['complejidad']) || $_POST['complejidad'] != '') { 
				$complejidad = formatInput($_POST['complejidad']) ?? '';
				if(!is_numeric($complejidad)) {
					$uploadOk = false;
					$dataCheckMessage .= "El valor del campo \"Complejidad\" debe ser un número.<br>";
				} else if($complejidad < 1 || $complejidad > 5){
					$uploadOk = false;
					$dataCheckMessage .= "El valor del campo \"Complejidad\" debe estar entre el 1 y el 5, ambos inclusive.<br>";
				}
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "El campo de \"Complejidad\" no puede ser vacio.<br>";
			}

			if(isset($_POST['tema']) && !empty($_POST['tema'])) { 
				$tema = formatInput($_POST['tema']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "El campo de \"Tema\" no puede ser vacio.<br>";
			}

			// Check if everything is ok
			if (!$uploadOk) {
				throw new RuntimeException($dataCheckMessage);
			}

			// Undefined | Multiple Files | $_FILES Corruption Attack
			// If this request falls under any of them, treat it invalid.
			if (!isset($_FILES['imagen']['error']) || is_array($_FILES['imagen']['error'])) {
				throw new RuntimeException('Parametros inválidos.');
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
				throw new RuntimeException('Tamaño de archivo excedido.');
				default:
				throw new RuntimeException('Error desconocido.');
			}

			if($containsImage) {

				// You should also check filesize here. 
				if ($_FILES['imagen']['size'] > 1000000) {
					throw new RuntimeException('Tamaño de archivo excedido.');
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
					throw new RuntimeException('Formato de archivo inválido.');
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
					throw new RuntimeException('Fallo al mover el archivo.');
				}

				$imagenPregunta = sprintf('%s%s.%s', $imageUploadFolder, $sha1Name, $ext);
				$operationMessage .= 'Archivo subido de forma correcta.';

			}

		} catch (RuntimeException $e) {

			$uploadOk = false;
			$operationMessage .= $e->getMessage();

		}

		if($uploadOk) {
			$sql = "INSERT INTO preguntas(email, enunciado, respuesta_correcta, respuesta_incorrecta_1, respuesta_incorrecta_2, respuesta_incorrecta_3, complejidad, tema, imagen)
			VALUES('$email', '$enunciado', '$respuestaCorrecta', '$respuestaIncorrecta', '$respuestaIncorrecta1', '$respuestaIncorrecta2', $complejidad, '$tema',  '$imagenPregunta')";

			if (!$result = $conn->query($sql)) {
				// Oh no! The query failed. 
				$operationMessage .= "<br>La pregunta no se ha insertado correctamente debido a un error con la base de datos. <br>Presione el botón de volver e inténtelo de nuevo.";
			} else {
				//$last_id = $conn->insert_id;
				$operationMessage .= "<br>La pregunta se ha insertado correctamente. <br>Para verla haga click <a href='VerPreguntasConFoto.php' target='_self'>aquí</a>";
			}

			// Close connection
			$conn->close();

		} else {
			$operationMessage .= "<br>Presione el botón de volver e inténtelo de nuevo.";
		}

		return $operationMessage;

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

	?>
</head>

<body>
	<header>
		<span ><a href="Registrar.php">Registrarse</a></span>
		<span><a href="login">Login</a></span>
		<span style="display:none;"><a href="/logout">Logout</a></span>
		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<span><a href='layout.html'>Inicio</a></span>
			<span><a href='quizes.php'>Preguntas</a></span>
			<span><a href='creditos.html'>Creditos</a></span>
		</nav>
		<article class="content">
			<label>
				<?php
				echo uploadQuestion();
				?>
			</label>
			<div>
				<input type="button" value="Volver" style="height: 20px; width: 41px;" onClick="javascript:history.go(-1)"/>
			</div>
		</article>		
		<aside class="sidebar">
			Sidebar contents<br/>(sidebar)
		</aside>
	</div>
	<footer>
		<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank">¿Qué es un Quiz?</a></p>
		<a href='https://github.com/FerMod/ProyectoSW'>Link GITHUB</a>
	</footer>

</body>
</html>
