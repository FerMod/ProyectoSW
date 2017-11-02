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
		<span ><a href="Registrar.php">Registrarse</a></span>
		<?php
			session_start();
			
			if(@$_SESSION["email"] != "ent") {
				echo '<span><a href="Login.php">Login</a></span>';
			} else {
				echo '<span><a href="logout.php">Log Out</a></span>';
			}
		?>
		<span style="display:none;"><a href="/logout">Logout</a></span>
		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<span><a href='layout.php'>Inicio</a></span>
			<?php 
				if(@!$_SESSION["email"]) {
					echo '<span><a href="quizes.php">Preguntas</a></span>';
				}
			?>
			<span><a href='creditos.php'>Creditos</a></span>
		</nav>
		<article class="content">
			Article contents<br/>(content)<br/>
			Aqui se visualizan las preguntas y los creditos ...
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
