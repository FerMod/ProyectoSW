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

			function createQuestionTable() {

				include "config.php";

				// Create connection
				$conn = new mysqli($servername, $username, $password, $database);
	 
				// Check connection
				if ($conn->connect_error) {
				 	trigger_error("Database connection failed: "  . $conn->connect_error, E_USER_ERROR);
				}

				// Perform an SQL query
				$sql = "SELECT *
					FROM preguntas";

				if (!$result = $conn->query($sql)) {
				    
				    echo "Sorry, the website is experiencing problems.";

				    // Again, do not do this on a public site, but we'll show you how
				    // to get the error information
				    echo "Error: Our query failed to execute and here is why: \n";
				    echo "Query: " . $sql . "\n";
				    echo "Errno: " . $conn->errno . "\n";
				    echo "Error: " . $conn->error . "\n";
				} else {

					if ($result->num_rows != 0) {

						echo "<table>";
						echo "<tr>";
						echo "<th>id</th>";
						echo "<th>email</th>";
						echo "<th>enunciado</th>";
						echo "<th>respuesta_correcta</th>";
						echo "<th>respuesta_incorrecta_1</th>";
						echo "<th>respuesta_incorrecta_2</th>";
						echo "<th>respuesta_incorrecta_3</th>";
						echo "<th>complejidad</th>";
						echo "<th>tema</th>";
						echo "<th>imagen</th>";
						echo "</tr>";

						while ($question = $result->fetch_assoc()) {
							echo "<tr>";
						    echo "<td>$question[id]</td>";
						    echo "<td>$question[email]</td>";
						    echo "<td>$question[enunciado]</td>";
						    echo "<td>$question[respuesta_correcta]</td>";
						    echo "<td>$question[respuesta_incorrecta_1]</td>";
						    echo "<td>$question[respuesta_incorrecta_2]</td>";
						    echo "<td>$question[respuesta_incorrecta_3]</td>";
						    echo "<td>$question[complejidad]</td>";
						    echo "<td>$question[tema]</td>";

						    $image = "img/no_image_available.gif";
						    if (file_exists($question["imagen"])) {
						    	$image = $question["imagen"];
						    }

						    echo "<td><img src='$image'style='max-width: 100%; height: auto; object-fit: cover;'></td>";
						  	echo "</tr>";
						}
						echo "</table>\n";
						$result->free();
					} else {
						echo "We could not find any values, sorry about that.";
					}

				}

				// Close connection
				$conn->close();

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

				<form id="fpreguntas" name="fpreguntas">
					<fieldset>
						<legend>DATOS DE LA PREGUNTA</legend>
						<div>
							<?php createQuestionTable()?>
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
