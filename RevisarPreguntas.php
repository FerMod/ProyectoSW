
<?php

include_once('login_session.php'); // Includes login script
include("session_timeout.php");

if(isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
	// What is doing here a logged user??
	checkSessionTimeOut();
	header("location: layout.php");
}

$config = include("config.php");

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Preguntas - Login</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">
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
			<?php 
			if(isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
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
			<label>Editar pregunta</label>
			<fieldset id="preguntas">
			<?php
				$preguntas = simplexml_load_file('xml/preguntas.xml');
				$i = 0;
				foreach ($preguntas->assessmentItem as $pregunta) {
					echo '<div class="preguntaed" id='.$i.'>';
						echo '<div><label id="'.$i.'comp">Complejidad: '.$pregunta['complexity'].' | Tema: '.$pregunta['subject'].' | Autor: '.$pregunta['author'].'</label></div>';
						echo '<div><label id="'.$i.'preg">Enunciado: '.$pregunta->itemBody->p.'</label></div>';
						echo '<div><label id="'.$i.'cor">+Respuesta correcta: '.$pregunta->correctResponse->value.'</label></div>';
						$j = 1;
						$incorrect = $pregunta->incorrectResponses;
						foreach($incorrect->value as $incor) {
							echo '<div><label id="'.$i.'incor'.$j.'">-Respuesta incorrecta '.$j.': '.$incor.'</label></div>';
							$j = $j + 1;
						}
					echo '</div>';
					echo '<hr>';
					$i = $i + 1;
				}
			?>
			<style type="text/css">
				.preguntaed {
						border-left: 6px solid green;
    					background-color: lightgrey;
				}	

				#preguntas {
					height:30%;
					overflow:hidden;
					overflow-y:scroll;
				}
			</style>
			</fieldset>
			<div>
			<fieldset>
				<legend>Datos de la pregunta</legend>
				<form>
					<div>
						<label for="email">Email*:</label>
						<input type="text" id="email" name="email" autofocus/>
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
						<label for="respuestaincorrecta1">Respuesta incorrecta 1*:</label>
						<input type="text" id="respuestaincorrecta1" name="respuestaincorrecta1" size="35" />
					</div>
					<div>
						<label for="respuestaincorrecta2">Respuesta incorrecta 2*:</label>
						<input type="text" id="respuestaincorrecta2" name="respuestaincorrecta2" size="35" />
					</div>
					<div>
						<label for="respuestaincorrecta3">Respuesta incorrecta 3*:</label>
						<input type="text" id="respuestaincorrecta3" name="respuestaincorrecta3" size="35" />
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
						<input type="submit" value="Confirmar edición" id="tema" name="tema"/>
					</div>
				</form>
			</fieldset>
		</div>
		</article>		
		<aside class="sidebar">
			<span>Sidebar contents<br/>(sidebar)</span>
		</aside>
	</div>

	<footer>
		<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank">¿Qué es un Quiz?</a></p>
		<a href='https://github.com/FerMod/ProyectoSW'>Link GITHUB</a>
	</footer>

</body>
</html>