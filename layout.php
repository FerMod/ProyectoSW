
<?php
include_once('login_session.php'); // Includes login script
include('session_timeout.php');
refreshSessionTimeout();

$config = include("config.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<!-- Uncomment the following line to auto refresh the page -->
	<!-- <meta http-equiv="refresh" content="<?php echo $config["session"]["timeout"]; ?>"> -->

	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Preguntas - Layout</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<header>

		<?php
			if((isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) || (isset($_SESSION['logged_teacher']) && !empty($_SESSION['logged_teacher']))) {
				echo '<span><a href="logout.php">Logout</a></span>';
			} else {
				echo '<span><a href="Registrar.php">Registrarse</a></span>';
				echo '&nbsp'; // Add non-breaking space
				echo '<span><a href="Login.php">Login</a></span>';
			}
		?>

		<h2>Quiz: el juego de las preguntas</h2>
		<?php
			if(isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
				echo '<label>¡Bienvenido alumno '.$_SESSION['logged_user'].'! </label>';
			} else if(isset($_SESSION['logged_teacher']) && !empty($_SESSION['logged_teacher'])) {
				echo '<label>¡Bienvenido profesor '.$_SESSION['logged_teacher'].'! </label>';
			}
		?>
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
				} else if(isset($_SESSION['logged_teacher']) && !empty($_SESSION['logged_teacher'])) {
					echo '<span><a href="layout.php">Inicio</a></span>';
					echo '<span><a href="quizes.php">Hacer pregunta</a></span>';
					echo '<span><a href="RevisarPreguntas.php">Revisar preguntas</a></span>';
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
			Article contents<br/>(content)<br/>
			Aqui se visualizan las preguntas y los creditos ...
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
