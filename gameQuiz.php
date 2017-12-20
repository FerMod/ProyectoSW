<?php

$config = include("config.php");

function contesta() {

	global $config;

	// Create connection
	$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

	// Check connection
	if ($conn->connect_error) {
		trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
	}

	$idpreg = $_POST['numeropregunta'];

	if(isset($_POST['respuesta']) && !empty($_POST['respuesta'])) {
		$respuesta = $_POST['respuesta'];
	} else {
		$respuesta = "";
		echo '<strong><label style="color:#cd0000;">¡Has dejado la pregunta sin contestar!</label></strong>';
	}
	
	$result = $conn->query("SELECT * FROM preguntas WHERE id='$idpreg'")->fetch_assoc();

	if($result['respuesta_correcta'] == $respuesta) {
		echo '<strong><label style="color:#008000;">¡Has acertado!</label></strong>';
	} else {
		echo '<strong><label style="color:#cd0000;">¡Has fallado!</label></strong>';
	}


	echo '<div style="text-align:center">';
	echo '<label>¿Te ha gustado la pregunta?</label>';
	echo '<label id="val">'.$result['valoracion'].'</label>';
	echo '<input type="button" id="like" value="Like" onclick="actualizarLike('.$idpreg.')">';
	echo '<input type="button" id="dislike" value="Dislike" onclick="actualizarDislike('.$idpreg.')">';
	echo '</div>';
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
	<header>

		<?php
		if(isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
			echo '<span><a href="logout.php">Logout</a></span>';
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
			<?php include('navbar_items.php'); ?>
		</nav>
		<article class="content">
			<form id="random" enctype="multipart/form-data" method="post">	
				<fieldset>
					<legend>Juego de las preguntas</legend>
					<div id="Quizer">
						<?php
							if(isset($_POST['contestar']) && !empty($_POST['contestar'])) {
								contesta();
							} else {
								echo '<label>Clique en pregunta aleatoria para empezar.</label>';
							}
						?>
						<input type="button" value="Pregunta aleatoria" onclick="createRandomQuestion()">
					</div>
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
