<?php

$config = include("config.php");

function nuevoJugador() {

	global $config;

	// Create connection
	$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

	// Check connection
	if ($conn->connect_error) {
		trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
	}

	$operationMessage = "";
	$dataCorrect = true;

	try {

		$dataCheckMessage = "";

		if(isset($_POST['nickjugador']) && !empty($_POST['nickjugador'])) { 
			$jugador = formatInput($_POST['nickjugador']);
		} else {
			$dataCorrect = false;
			$operationMessage .= "<div class=\"serverMessage\">El campo \"Jugador\" no puede ser vacío.</div>";
		}

	} catch (RuntimeException $e) {
		$dataCorrect = false;
		$operationMessage .= $e->getMessage();
	}

	if($dataCorrect) {
		$sql = "INSERT INTO jugadores (nick, puntuacion, preguntas_respondidas, preguntas_acertadas, preguntas_falladas) VALUES ('$jugador', 0, 0, 0, 0)";


		if(!$result = $conn->query($sql)) {
			$operationMessage .= "<script language=\"javascript\">alert(\"Ha ocurrido un error con la base de datos, por favor, inténtelo de nuevo.\");</script>"; 
		} else {
			session_start();
			$_SESSION['player'] = $jugador;
			$_SESSION['questions-ids'] = [];
			$operationMessage .= "<script language=\"javascript\">alert(\"¡Se ha registrado con éxito el jugador!\"); window.location.replace(\"gameQuiz.php\");</script>";
		}

		// Close connection
		$conn->close();

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

	?>

	<!DOCTYPE html>
	<html lang="es">
	<head>
		<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">

		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<title>Preguntas - Juego de las preguntas</title>

		<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
		<script src="js/script.js"></script>	

		<link rel="stylesheet" href="css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">

	</head>

	<body>
		<?php
			session_start();
			if(isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
				echo '<script language=\"javascript\">window.location.replace(\"layout.php\");</script>';
			}

			if(isset($_SESSION['player']) && !empty($_SESSION['player'])) {
				echo '<script language=\"javascript\">window.location.replace(\"gameQuiz.php\");</script>';
			}
		?>

		<header>
			<span><a href="Registrar.php">Registrarse</a></span>
			<span><a href="Login.php">Login</a></span>
		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<?php include('navbar_items.php'); ?>
		</nav>
		<article class="content">
			<form id="random" enctype="multipart/form-data" method="post">	
				<fieldset>
					<legend>Juego de las preguntas</legend>
					<div>
						<label>Escriba su nombre de jugador.</label>
						<input type="text" name="nickjugador">
						<input type="submit" name="registrojug" value="Registrar jugador">
					</div>
					<?php
					if(isset($_POST['registrojug'])) {
						nuevoJugador();
					}
					?>
				</fieldset>
			</form>

		</article>		
		<aside class="sidebar">
			<span>Sidebar contents<br/>(sidebar)</span>
		</aside>
	</div>

	<footer>
		<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank" rel="noopener">¿Qué es un Quiz?</a></p>
		<a href='https://github.com/FerMod/ProyectoSW'>Link GITHUB</a>
	</footer>

</body>
</html>
