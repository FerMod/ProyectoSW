
<?php

include_once('login_session.php');
include_once('session_timeout.php');
include('ajaxRequestManager.php');

if(!isValidSession()) {
	header("location: layout.php");
} else {
	refreshSessionTimeout();
}

header("Cache-Control: no-store, no-cache, must-revalidate");

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Preguntas - Gestion Preguntas</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/script.js"></script>

	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">


	<!--
		Code Mirror (http://codemirror.net/)
		A versatile text editor implemented in JavaScript for the browser.
		It is specialized for editing code, and comes with a number of language modes and addons that
		implement more advanced editing functionality.
	-->	
	<script src="https://codemirror.net/lib/codemirror.js"></script>
	<link rel="stylesheet" href="https://codemirror.net/lib/codemirror.css">
	<script src="https://codemirror.net/mode/xml/xml.js"></script>

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
		<?php
		if((isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) && (isset($_SESSION['user_type']) && !empty($_SESSION['user_type']))) {		

			$userType = '';
			switch ($_SESSION['user_type']) {
				case 'teacher':
				$userType = 'profesor';
				break;

				case 'student':
				$userType = 'alumno';
				break;
			}

			echo '<span>¡Bienvenido ' . $userType . ' "' . $_SESSION['logged_user'] . '"! </span>';

		}
		?>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<?php include('navbar_items.php'); ?>
		</nav>
		<article class="content">
			<div id="questionStats" style="margin: 5px; padding: 15px 5px 15px; border-left: 6px solid grey; border-radius: 5px; background-color: lightgrey;">				
				<label for="questionStatsLabel" style="display: block;"><strong>Preguntas</strong></label>
				Preguntas introducidas: <span id="preguntasUsuarios"></span>
				Preguntas totales: <span id="preguntasTotales"></span>				
			</div>

			<form id="formGestionPreguntas" name="formGestionPreguntas" method="post" action="" enctype="multipart/form-data">

				<fieldset>
					<legend>DATOS DE LA PREGUNTA</legend>
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
						<label for="imagen">Subir imagen:</label>
						<input type="file" name="imagen" id="imagen"/>

						<img id="previewImage" class="modalImage" src="#" alt="Imagen de la pregunta"/>
						<input type="button" id="quitarImagen" value="Quitar Imagen"/>

						<!-- The Modal -->
						<div id="modalElement" class="modal">

							<!-- The Close Button -->
							<span class="close">&times;</span>

							<!-- Modal Content (The Image) -->
							<img class="modal-content" id="img01">

							<!-- Modal Caption (Image Text) -->
							<div id="caption"></div>
						</div>
					</div>
					<div>
						<input type="submit" id="enviarPregunta" name="enviarPregunta" value="Enviar pregunta"/>
					</div>
				</fieldset>
			</form>
			<div id="operationResult"></div>
			<div id="visualizarDatos"></div>

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
