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

			if (isset($_GET['id']) && is_numeric($_GET['id'])) {
			    $id = (int) $_GET['id'];
			} else {
				echo "Error geting the question id." . PHP_EOL;
			}

			// Perform an SQL query
			$sql = "SELECT id, email, enunciado, respuesta_correcta, respuesta_incorrecta_1, respuesta_incorrecta_2, respuesta_incorrecta_3, complejidad, tema
				FROM preguntas
				WHERE id = $id";

			if (!$result = $conn->query($sql)) {
			    // Oh no! The query failed. 
			    echo "Sorry, the website is experiencing problems.";

			    // Again, do not do this on a public site, but we'll show you how
			    // to get the error information
			    echo "Error: Our query failed to execute and here is why: \n";
			    echo "Query: " . $sql . "\n";
			    echo "Errno: " . $conn->errno . "\n";
			    echo "Error: " . $conn->error . "\n";
			}

			if ($result->num_rows === 0) {
			    echo "We could not find a match for ID $id, sorry about that.";
			}

			$question = $result->fetch_assoc();

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

				<form id="fpreguntas" name="fpreguntas">
					<fieldset>
						<legend>DATOS DE LA PREGUNTA</legend>
						<div>
							<label for="email">Email: <?php echo $question['email'] ?></label>
						</div>
						<div>
							<label for="enunciado">Enunciado de la pregunta: <?php echo $question['enunciado'] ?></label>
							<label type="text" id="enunciado" name="enunciado" size="35" />
						</div>
						<div>
							<label for="respuestacorrecta">Respuesta correcta: <?php echo $question['respuesta_correcta'] ?></label>
						</div>
						<div>
							<label for="respuestaincorrecta">Respuesta incorrecta: <?php echo $question['respuesta_incorrecta_1'] ?></label>
						</div>
						<div>
							<label for="respuestaincorrecta1">Respuesta incorrecta: <?php echo $question['respuesta_incorrecta_2'] ?></label>
						</div>
						<div>
							<label for="respuestaincorrecta2">Respuesta incorrecta:  <?php echo $question['respuesta_incorrecta_3'] ?></label>
						</div>
						<div>
							<label for="complejidad">Complejidad: <?php echo $question['complejidad'] ?></label>
						</div>
						<div>
							<label for="tema">Tema:  <?php echo $question['tema'] ?></label>
						</div>
						<div>
							<input type="button" id="volver" name="volver" value="Volver" onClick="javascript:history.go(-1)"/>
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
