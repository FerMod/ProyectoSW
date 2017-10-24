<!DOCTYPE html>
<html>
	<head>
		<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
		<title>Preguntas</title>
		<link rel='stylesheet' type='text/css' href='css/style.css' />
		<link rel='stylesheet' 
			type='text/css' 
			media='only screen and (min-width: 530px) and (min-device-width: 481px)'
			href='css/wide.css' />
		<link rel='stylesheet' 
			type='text/css' 
			media='only screen and (max-width: 480px)'
			href='css/smartphone.css' />	

		<?php

			include "config.php";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $database);
 
			// Check connection
			if ($conn->connect_error) {
			 	trigger_error("Database connection failed: "  . $conn->connect_error, E_USER_ERROR);
			} else {
				//echo "Connection success." . PHP_EOL; // PHP_EOL The correct 'End Of Line' symbol for this platform
				//echo "Host information: " . $conn->host_info . PHP_EOL; //OK
			}
			
			//Insert data of quizes.php
			$email = formatInput($_POST['email']) ?? '';
			$enunciado = formatInput($_POST['enunciado']) ?? '';
			$respuestaCorrecta = formatInput($_POST['respuestacorrecta']) ?? '';
			$respuestaIncorrecta = formatInput($_POST['respuestaincorrecta']) ?? '';
			$respuestaIncorrecta1 = formatInput($_POST['respuestaincorrecta1']) ?? '';
			$respuestaIncorrecta2 = formatInput($_POST['respuestaincorrecta2']) ?? '';
			$complejidad = formatInput($_POST['complejidad']) ?? '';
			$tema = formatInput($_POST['tema']) ?? '';

			$operationMessage = "";
			$uploadOk = true;
			$imagenPregunta = null;

			try {
				 
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

			
			// Format the input for security reasons
			function formatInput($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}

		?>
	</head>
	<body>
		<div id='page-wrap'>
			<header class='main' id='h1'>
				<span class="right"><a href="registro">Registrarse</a></span>
				<span class="right"><a href="login">Login</a></span>
				<span class="right" style="display:none;"><a href="/logout">Logout</a></span>
				<h2>Quiz: el juego de las preguntas</h2>
			</header>
			<nav class='main' id='n1' role='navigation'>
				<span><a href='layout.html'>Inicio</a></span>
				<span><a href='quizes.php'>Preguntas</a></span>
				<span><a href='creditos.html'>Creditos</a></span>
			</nav>
			<section class="main" id="s1">
			<div>
				<label>
				<?php
					echo $operationMessage;
				?>
				</label>
			</div>
			<div>
				<input type="button" value="Volver" style="height: 20px; width: 41px;" onClick="javascript:history.go(-1)"/>
			</div>
			</section>
			<footer class='main' id='f1'>
				<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_self">¿Qué es un Quiz?</a></p>
				<a href='https://github.com/FerMod/ProyectoSW'>Link GITHUB</a>
			</footer>
		</div>
	</body>
</html>