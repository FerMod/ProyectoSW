
<?php
include_once('login_session.php'); // Includes login script
if(!isset($_SESSION['login_user']) || empty($_SESSION['login_user'])) {
	header("location: layout.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"/></script>	
	<script src="js/sortElements.js"/></script>

	<link rel="stylesheet" href="css/style.css">

	<?php

	function createQuestionTable() {

		include "config.php";

		// Create connection
		$conn = new mysqli($servername, $user, $pass, $database);
		
		// Check connection
		if ($conn->connect_error) {
			trigger_error("Database connection failed: "  . $conn->connect_error, E_USER_ERROR);
		}

		// Perform an SQL query
		$sql = "SELECT *
		FROM preguntas";

		if (!$result = $conn->query($sql)) {
			echo "Sorry, the website is experiencing problems.";
		} else {

			if ($result->num_rows != 0) {

				echo "<table class=\"infoTable\" readonly>";
				echo "<thead>";
				echo "<tr>";
				echo "<th class=\"sortable\">Id</th>";
				echo "<th class=\"sortable\">Email</th>";
				echo "<th>Enunciado</th>";
				echo "<th>Respuesta Correcta</th>";
				echo "<th>Respuesta Incorrecta 1</th>";
				echo "<th>Respuesta Incorrecta 2</th>";
				echo "<th>Respuesta Incorrecta 3</th>";
				echo "<th class=\"sortable\">Complejidad</th>";
				echo "<th class=\"sortable\">Tema</th>";
				echo "<th>Imagen</th>";
				echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
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

					echo "<td><img class=\"modalImage\" src='$image' style=\"max-width: 50%; height: auto; object-fit: contain;\"></td>";
					echo "</tr>";
				}				
				echo "</tbody>";
				echo "</table>";
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
	<header>
		
		<?php
		if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])) {
			echo '<span><a href="creditos.php">Logout</a></span>';
		} else {
			echo '<span><a href="Registrar.php">Registrarse</a></span>';
			echo '&nbsp'; // Add non-breaking space
			echo '<span><a href="Login.php">Login</a></span>';
		}
		?>

		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<?php 
			if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])) {
				echo '<span><a href="layout.php">Inicio</a></span>';
				echo '<span><a href="quizes.php">Hacer pregunta</a></span>';
				echo '<span><a href="VerPreguntasConFoto.php">Ver preguntas</a></span>';
				echo '<span><a href="GestionPreguntas.php">Gestionar preguntas</a></span>';
				echo '<span><a href="creditos.php">Creditos</a></span>';
			} else {
				echo '<span><a href="layout.php">Inicio</a></span>';
				echo '<span><a href="creditos.php">Creditos</a></span>';
			}
			?>
		</nav>
		<article class="content">
			<div style="margin: 5px; padding: 15px 5px 15px; border-left: 6px solid grey; border-radius: 5px; background-color: lightgrey;">
				<a target="_blank" href="VerPreguntasXML.php">Ver las preguntas del fichero xml</a>
			</div>

			<div class="scrollContent">
				<?php createQuestionTable()?>
			</div>
			<!-- The Modal -->
			<div id="modalElement" class="modal">

				<!-- The Close Button -->
				<span class="close">&times;</span>

				<!-- Modal Content (The Image) -->
				<img class="modal-content" id="img01">

				<!-- Modal Caption (Image Text) -->
				<div id="caption"></div>
			</div>
			<div>
				<input type="button" id="volver" name="volver" value="Volver" onClick="javascript:history.go(-1)"/>
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
