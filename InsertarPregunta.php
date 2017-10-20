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

			$sql = "INSERT INTO preguntas(email, enunciado, respuesta_correcta, respuesta_incorrecta_1, respuesta_incorrecta_2, respuesta_incorrecta_3, complejidad, tema)
				VALUES('$email', '$enunciado', '$respuestaCorrecta', '$respuestaIncorrecta', '$respuestaIncorrecta1', '$respuestaIncorrecta2', $complejidad, '$tema')";
			
			if (!$result = $conn->query($sql)) {
				// Oh no! The query failed. 
				$control = "La pregunta no se ha insertado correctamente debido a un error con la base de datos. Presione el botón de volver e inténtelo de nuevo.";
			} else {
				$last_id = $conn->insert_id;
				$control = "La pregunta se ha insertado correctamente. Para verla haga click ";
				$urlid = "VerPreguntas.php?id=" . $last_id;
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
				<a href=
				<?php
					if($result) { 
						echo $urlid;
					}
				?>
				target="_self">
				<?php
					if($result) { 
						echo "aquí";
					}
				?>
				</a>
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
