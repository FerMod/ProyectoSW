<!DOCTYPE html>
<html lang="es">
<head>
	<!-- <?php
		//if(!isset($_GET['email']) && empty($_GET['email']) && password_verify(hash("sha256", $_GET['id'])) {
			//echo '<script>alert("No está permitido cambiar de contraseña sin id."); location.href="layout.php"; </script>';
		//}
	?> -->
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Preguntas - Recuperar contraseña</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">

	<?php 

	$config = include("config.php");

	function resetPassword($email, $id) {

		global $config;

		/*
		if(!password_verify(hash("sha256", $id)) {
			echo '<script> alert("No está permitido cambiar de contraseña sin id."); location.href="layout.php"; </script>';
		}
		*/

		$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

		if ($conn->connect_error) {
			trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
		}

		$passOk = true;
		$dataCheckMessage = "";

		if(isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['passwordrep']) && !empty($_POST['passwordrep'])) {
			if($_POST['password'] == $_POST['passwordrep']) {
				$hashedPassword = password_hash(hash("sha256", $_POST['password']), PASSWORD_DEFAULT);
			} else {
				$passOk = false;
				$dataCheckMessage .= "<div class=\"serverErrorMessage\">Las contraseñas introducidas no coinciden.</div>";	
			}
		} else {
			$passOk = false;
			$dataCheckMessage .= "<div class=\"serverErrorMessage\">Los campos de contraseñas no pueden ser vacios.</div>";		
		}

		if($passOk) {
			$result = $conn->query("UPDATE usuarios SET password = '$hashedPassword' WHERE email='$email'");
			if($result) {
				$dataCheckMessage .= "<div class=\"serverInfoMessage\">Contraseña cambiada con éxito.</div>";	
			} else {
				$dataCheckMessage .= "<div class=\"serverErrorMessage\">Ha ocurrido un error interno al intentar cambiar la contraseña.</div>";	
			}
		}


		echo $dataCheckMessage;
	}

	?>

</head>

<body>
	<header>

		<span><a href="Registrar.php">Registrarse</a></span>
		<span><a href="Login.php">Login</a></span>

		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<span><a href="layout.php">Inicio</a></span>
			<span><a href="creditos.php">Creditos</a></span>
		</nav>
		<article class="content">
			<form method="post" action=<?php echo '"reset.php?email="'.$_GET['email'].'&id='.$_GET['id'].'"'; ?>>	
				<fieldset>
					<legend>Recuperar contraseña</legend>

					<div>
						<label for="pass">Escriba su nueva contraseña.</label>
						<input type="text" name="password" autofocus/>
						<label for="passrep">Repita su nueva contraseña.</label>
						<input type="text" name="passwordrep"/>
					</div>
					<div>
						<input type="submit" value="Cambiar contraseña" name="submit"/>
					</div>

				</fieldset>

				<?php
					if(isset($_POST['submit']) && !empty($_POST['submit'])) {
						resetPassword($_GET['email'], $_GET['id']);
					}
				?>

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