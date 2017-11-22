
<?php
include_once('login_session.php'); // Includes login script
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.min.js"></script>
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
			<table id="tablaAutores">
				<thead>
					<tr>
						<!-- <td><img src="img/loading.gif" class="modalImage lazyload" data-original="https://github.com/FerMod.png" width="640" heigh="480"></td> -->
						<td><img src="img/loading.gif" data-src="https://github.com/FerMod.png" class="modalImage lazy"></td>
						<td><img src="img/loading.gif" data-src="https://github.com/FosterGun.png" class="modalImage lazy"></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Ferran Tudela</td>
						<td>Miguel Ángel Blanco</td>
					</tr>
					<tr>
						<td>Estudiante</td>
						<td>Estudiante</td>
					</tr>
				</tbody>
			</table>
			<!-- The Modal -->
			<div id="modalElement" class="modal">

				<!-- The Close Button -->
				<span class="close">&times;</span>

				<!-- Modal Content (The Image) -->
				<img class="modal-content" id="img01">

				<!-- Modal Caption (Image Text) -->
				<div id="caption"></div>
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

	<script>
		$(function() {
			$('.lazy').lazy();
		});
	</script>

</body>
</html>
