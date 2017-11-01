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
		<span><a href="Login.php">Login</a></span>
		<span style="display:none;"><a href="/logout">Logout</a></span>
		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<span><a href='layout.html'>Inicio</a></span>
			<span><a href='quizes.php'>Preguntas</a></span>
			<span><a href='creditos.html'>Creditos</a></span>
		</nav>
		<article class="content">
			<form id="login" enctype="multipart/form-data">	
				<fieldset>
					<legend>LOGIN</legend>

					<div>
						<label>Email</label>
						<input type="text" name="email"/>
					</div>

					<div>
						<label>Contraseña</label>
						<input type="password" name="password"/>
					</div>

					<div>
						<input type="submit" value="Acceder"/>
					</div>

				</fieldset>
			</form>
		</article>		
		<aside class="sidebar">
			Sidebar contents<br/>(sidebar)
		</aside>
	</div>
		<?php 
			include "config.php";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $database);

			// Check connection
			if ($conn->connect_error) {
				trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
			}
			
			$email = $_POST['email'];
			$pass = $_POST['password'];
			
			$sql = "SELECT * FROM `usuarios` WHERE `username`='$email' AND `password`='$password'";
	
			if($conn->query($sql)) {
				
			} else {
				
			}
		?>
	<footer>
		<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank">¿Qué es un Quiz?</a></p>
		<a href='https://github.com/FerMod/ProyectoSW'>Link GITHUB</a>
	</footer>

</body>
</html>
