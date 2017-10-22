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
			$image = $_FILES["imagen"] ?? '';
			uploadImage($imagePath, $image);

			$imagenPregunta = null;
			if ($image != '') {
				$imagenPregunta = $imagePath . $image["name"];
			}

			echo $imagenPregunta;
			

			$sql = "INSERT INTO preguntas(email, enunciado, respuesta_correcta, respuesta_incorrecta_1, respuesta_incorrecta_2, respuesta_incorrecta_3, complejidad, tema, imagen)
				VALUES('$email', '$enunciado', '$respuestaCorrecta', '$respuestaIncorrecta', '$respuestaIncorrecta1', '$respuestaIncorrecta2', $complejidad, '$tema',  '$imagenPregunta')";
			
			if (!$result = $conn->query($sql)) {
				// Oh no! The query failed. 
				$control = "La pregunta no se ha insertado correctamente debido a un error con la base de datos. <br>Presione el botón de volver e inténtelo de nuevo.";
			} else {
				//$last_id = $conn->insert_id;
				$control = "La pregunta se ha insertado correctamente. <br>Para verla haga click <a href='VerPreguntas.php' target='_self'>aquí</a>";
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

			function uploadImage($target_dir, $imageFile) {

				$target_file = $target_dir . basename($imageFile["name"]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

				// Check if image file is a actual image or fake image
				// if(isset($_POST["subirImagen"])) {
				    $check = getimagesize($imageFile["tmp_name"]);
				    if($check !== false) {
				        echo "File is an image - " . $check["mime"] . ".";
				        $uploadOk = 1;
				    } else {
				        echo "File is not an image.";
				        $uploadOk = 0;
				    }
				// }

				// Check if file already exists
				if (file_exists($target_file)) {
				    echo "Sorry, file already exists.";
				    $uploadOk = 0;
				}

				// Check file size
				if ($imageFile["size"] > 500000) {
				    echo "Sorry, your file is too large.";
				    $uploadOk = 0;
				}

				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				    $uploadOk = 0;
				}

				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
				    echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
				} else {
				    if (move_uploaded_file($imageFile["tmp_name"], $target_file)) {
				        echo "The file ". basename($imageFile["name"]). " has been uploaded.";
				    } else {
				        echo "Sorry, there was an error uploading your file.";
				    }
				}

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
					echo $control;
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
