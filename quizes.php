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
		<script
			src="https://code.jquery.com/jquery-3.2.1.js"
			integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
			crossorigin="anonymous"></script>
		<script src="js/script.js"></script>

		<?php

			include "prueba.php";
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

			// Close connection
			$conn->close();

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

				<form id="fpreguntas" name="fpreguntas" method="post" action="InsertarPreguntaConFoto.php" enctype="multipart/form-data">
					<fieldset>
						<legend>DATOS DE LA PREGUNTA</legend>
						<div>
							<label for="email">Email*:</label>
							<input type="text" id="email" name="email"/>
						</div>
						<div>
							<label for="enunciado">Enunciado de la pregunta*:</label>
							<input type="text" id="enunciado" name="enunciado" size="35" />
						</div>
						<div>
							<label for="respuestacorrecta">Respuesta correcta*:</label>
							<input type="text" id="respuestacorrecta" name="respuestacorrecta" size="35" />
						</div>
						<div>
							<label for="respuestaincorrecta">Respuesta incorrecta*:</label>
							<input type="text" id="respuestaincorrecta" name="respuestaincorrecta" size="35" />
						</div>
						<div>
							<label for="respuestaincorrecta1">Respuesta incorrecta*:</label>
							<input type="text" id="respuestaincorrecta1" name="respuestaincorrecta1" size="35" />
						</div>
						<div>
							<label for="respuestaincorrecta2">Respuesta incorrecta*:</label>
							<input type="text" id="respuestaincorrecta2" name="respuestaincorrecta2" size="35" />
						</div>
						<div>
							<label for="complejidad">Complejidad (1..5)*:</label>
							<input type="text" id="complejidad" name="complejidad" size="10" />
						</div>
						<div>
							<label for="tema">Tema (subject)*:</label>
							<input type="text" id="tema" name="tema" size="10" />
						</div>
						<div>
							<label for="imagen">Subir imagen:</label>
							<input type="file" name="imagen" id="imagen"/>
						</div>
						<div>
							<input type="submit" id="enviar" name="enviar" value="Enviar solicitud"/>
							<input type="button" id="ver preguntas" value="Ver preguntas ya realizadas" OnClick="window.location='VerPreguntasConFoto.php';"/>
						</div>
					</fieldset>
				</form>
			</section>
			<footer class='main' id='f1'>
				<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank">¿Qué es un Quiz?</a></p>
				<a href='https://github.com/FerMod/ProyectoSW'>Link GITHUB</a>
			</footer>
		</div>
		
	</body>
</html>