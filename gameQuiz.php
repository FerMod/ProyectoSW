<?php

// include_once('login_session.php');
// include_once('session_timeout.php');

// if(isValidSession()) {
// 	refreshSessionTimeout();
// }

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
			if(isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
				echo '<script language=\"javascript\">window.location.replace(\"layout.php\");</script>';
			}

			if(!isset($_SESSION['player']) && empty($_SESSION['player'])) {
				echo '<script language=\"javascript\">window.location.replace(\"layout.php\");</script>';
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
					<div id="startQuiz">
						<label for="startQuizButton">Se elegiran de forma aleatoria preguntas. Pulse el boton de abajo para comenzar el quiz.</label>
						<input type="button" id="startQuizButton" value="Empezar Quiz!">
						<input type="button" value="Preguntas por temas" onclick="">
						<input type="button" value="Ranking de jugadores" onclick="">
					</div>
					<div id="question-container"></div>
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
