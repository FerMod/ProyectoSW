<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<header>
		<?php
		session_start();

		if(!@$_SESSION["email"]) {
			echo "<span><a href=\"Registrar.php\">Registrarse</a></span> ";
			echo "<span><a href=\"Login.php\">Login</a></span>";
		} else {
			echo "<span><a href=\"logout.php\">Logout</a></span>";
		}
		?>
		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<span><a href='layout.php'>Inicio</a></span>
			<?php 
			if(@$_SESSION["email"]) {
				echo '<span><a href="quizes.php">Preguntas</a></span>';
			}
			?>
			<span><a href='creditos.php'>Creditos</a></span>
		</nav>
		<article class="content">
			<table id="tablaAutores">
				<tr>
					<td><img src="img/user.png" class="modalImage"></img></td>
					<td><img src="img/user.png" class="modalImage"></img></td>
				</tr>
				<tr>
					<td>Ferran Tudela</td>
					<td>Miguel Ángel Blanco</td>
				</tr>
				<tr>
					<td>Estudiante</td>
					<td>Estudiante</td>
				</tr>
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

</body>
</html>
