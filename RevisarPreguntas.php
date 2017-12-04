
<?php

include_once('login_session.php'); // Includes login script
include_once("session_timeout.php");

if(!isValidSession()) {
	header("location: layout.php");
} else {
	refreshSessionTimeout();
}

//$config = include("config.php");

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Preguntas - Revisar preguntas</title>

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
			<label>Editar pregunta</label>
			<div id="listaPreguntas" class="listaPreguntas" style="border-style: groove;">
			</div>
			<script type="text/javascript">
				getQuestions(createQuestionList);
			</script>
			<form id="formRevPreguntas" name="formRevPreguntas" method="post" action="" enctype="multipart/form-data">
				<fieldset>
					<legend>Datos de la pregunta</legend>
					<div>
						<label for="id-edit">Id pregunta:</label>
						<input type="text" id="id-edit" name="id-edit" readonly="readonly" style="background: #dddddd;" />
					</div>
					<div>
						<label for="email-edit">Email*:</label>
						<input type="text" id="email-edit" name="email-edit" readonly="readonly" style="background: #dddddd;"/>
					</div>
					<div>
						<label for="enunciado-edit">Enunciado de la pregunta*:</label>
						<input type="text" id="enunciado-edit" name="enunciado-edit" size="35" />
					</div>
					<div>
						<label for="respuestaCorrecta-edit">Respuesta correcta*:</label>
						<input type="text" id="respuestaCorrecta-edit" name="respuestaCorrecta-edit" size="35" />
					</div>
					<div>
						<label for="respuestaIncorrecta1-edit">Respuesta incorrecta 1*:</label>
						<input type="text" id="respuestaIncorrecta1-edit" name="respuestaIncorrecta1-edit" size="35" />
					</div>
					<div>
						<label for="respuestaIncorrecta2-edit">Respuesta incorrecta 2*:</label>
						<input type="text" id="respuestaIncorrecta2-edit" name="respuestaIncorrecta2-edit" size="35" />
					</div>
					<div>
						<label for="respuestaIncorrecta3-edit">Respuesta incorrecta 3*:</label>
						<input type="text" id="respuestaIncorrecta3-edit" name="respuestaIncorrecta3-edit" size="35" />
					</div>
					<div>
						<label for="complejidad-edit">Complejidad (1..5)*:</label>
						<input type="text" id="complejidad-edit" name="complejidad-edit" size="10" />
					</div>
					<div>
						<label for="tema-edit">Tema (subject)*:</label>
						<input type="text" id="tema-edit" name="tema-edit" size="10" />
					</div>
					<div>
						<input type="submit" value="Confirmar edición"/>
					</div>
				</fieldset>
			</form>
			<div id="respuesta">
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