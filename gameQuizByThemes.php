<?php
session_start();
$config = include("config.php");

function contesta() {

	global $config;

	// Create connection
	$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

	// Check connection
	if ($conn->connect_error) {
		trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
	}

	if(!empty($_POST['numeropregunta']) && $_SESSION['questions-answer'] < 3) {

		$idpreg = $_POST['numeropregunta'];

		if(isset($_POST['respuesta']) && !empty($_POST['respuesta'])) {
			$respuesta = $_POST['respuesta'];
		} else {
			$respuesta = "";
			echo '<strong><label style="color:#cd0000;">¡Has dejado la pregunta sin contestar!</label></strong>';
		}
	
		$result = $conn->query("SELECT * FROM preguntas WHERE id='$idpreg'")->fetch_assoc();
		array_push($_SESSION['questions-ids'], $idpreg);

		if($result['respuesta_correcta'] == $respuesta) {
			echo '<strong><label style="color:#008000;">¡Has acertado!</label></strong>';
			$player = $_SESSION['logged_user'];
			$conn->query("UPDATE jugadores SET preguntas_respondidas = preguntas_respondidas + 1, puntuacion = puntuacion + 1, preguntas_acertadas = preguntas_acertadas + 1 WHERE nick = '$player'");
		} else {
			echo '<strong><label style="color:#cd0000;">¡Has fallado!</label></strong>';
			$conn->query("UPDATE jugadores SET preguntas_respondidas = preguntas_respondidas + 1, preguntas_falladas = preguntas_falladas + 1 WHERE nick = '$player'");
		}

		$_SESSION['questions-answer'] += 1;
		array_push($_SESSION['num-complejidad'], $result['complejidad']);

		echo '<div style="text-align:center">';
		echo '<label>¿Te ha gustado la pregunta?</label>';
		echo '<label id="val">'.$result['valoracion'].'</label>';
		echo '<input type="button" id="like" value="Like" onclick="actualizarLike('.$idpreg.')">';
		echo '<input type="button" id="dislike" value="Dislike" onclick="actualizarDislike('.$idpreg.')">';
		echo '</div>';
	} else {
		$player = $_SESSION['logged_user'];
		$result = $conn->query("SELECT * FROM jugadores WHERE nick='$player'")->fetch_assoc();
		$pacer = $result['preguntas_acertadas'];
		$pfal = $result['preguntas_falladas'];

		$media = array_sum($_SESSION['num-complejidad'])/$_SESSION['questions-answer'];
		echo "<script language=\"javascript\">alert(\"Has respondido bien ".$pacer." preguntas. Has respondido mal ".$pfal." preguntas.
		 La complejidad media de las preguntas ha sido de ".$media.".\"); window.location.replace(\"logout.php\");</script>";
		$conn->close();
	}

}

function questionsThemes() {
	global $config;

	// Create connection
	$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

	// Check connection
	if ($conn->connect_error) {
		trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
	}

	$result = $conn->query("SELECT DISTINCT tema FROM preguntas");

	while($tema = $result->fetch_array(MYSQLI_ASSOC)) {
		echo "<div><a href=\"gameQuizByThemes.php?tema=".$tema['tema']."\">Preguntas acerca de ".$tema['tema']."</a></div>";
	}
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
			if (isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user']) && isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
   				if($_SESSION['user_type'] != 'player') {
        			echo "<script language=\"javascript\">window.location.replace(\"layout.php\");</script>";
   				}
			} else {
				//echo "<script language=\"javascript\">window.location.replace(\"layout.php\");</script>";
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
			<form id="random" enctype="multipart/form-data" method="post" <?php if(isset($_GET['tema']) && !empty($_GET['tema'])) {echo 'action="gameQuizByThemes.php?tema='.$_GET['tema'].'"';}?>>	
				<fieldset>
					<legend>Juego de las preguntas</legend>
					<div id="Quizer">
						<?php
							if(isset($_POST['contestar']) && !empty($_POST['contestar'])) {
								contesta();
							}

							if(isset($_GET['tema']) && !empty($_GET['tema'])) {
								echo '<input type="button" value="Solicitar pregunta" onclick="questionByTheme(\''.$_GET['tema'].'\')">';
							} else {
								questionsThemes();
							}
						?>
						
						
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
