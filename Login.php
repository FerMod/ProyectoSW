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
			<span><a href='layout.php'>Inicio</a></span>
			<span><a href='creditos.php'>Creditos</a></span>
		</nav>
		<article class="content">
			<form id="login" enctype="multipart/form-data" method="post">	
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
						<input type="submit" value="Acceder" name="submit"/>
					</div>

				</fieldset>
			</form>
		</article>		
		<aside class="sidebar">
			Sidebar contents<br/>(sidebar)
		</aside>
	</div>
		<?php 
			function logIn() {
				include "config.php";

				// Create connection
				$conn = new mysqli($servername, $username, $password, $database);

				// Check connection
				if ($conn->connect_error) {
					trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
				}
			
				$email = $_POST['email'];
				$pass = $_POST['password'];
			
				if(!empty($email) && isset($email) && !empty($pass) && isset($pass)) {
					$result = $conn->query("SELECT * FROM `usuarios` WHERE `email`='$email'");
					$con = $result->fetch_assoc(); #Para comprobar que la contraseña que se escribe es correcta.
					$passhash = $con["password"];
					if(password_verify($pass,$passhash)) {
						session_start();
						$_SESSION["email"] = $email; //Iniciamos sesión.
						echo '<script>location.href="layout.php"</script>'; #Redirecciona a la página de Inicio.
					} else if(!$result){
						echo '<script language="javascript">alert("No existe email asignado a la contraseña.");</script>';
					} else {
						echo '<script language="javascript">alert("Contraseña incorrecta.");</script>';
					}
				} else {
					echo '<script language="javascript">alert("Faltan campos por escribir.");</script>';
				}
			}
		?>
	<footer>
		<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank">¿Qué es un Quiz?</a></p>
		<a href='https://github.com/FerMod/ProyectoSW'>Link GITHUB</a>
	</footer>
		<?php
			if(isset($_POST['submit'])) {
				logIn();
			}
		?>
</body>
</html>
