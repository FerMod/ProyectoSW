<!DOCTYPE html>
<html lang="es">
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Preguntas - Recuperar contraseña</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">


	<!-- In case to use sessions, coment the code below -->
	<?php 

	$config = include("config.php");

	function checkEmail() {

		global $config;

		$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

		if ($conn->connect_error) {
			trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
		}

		$emailOk = true;
		$dataCheckMessage = "";

		if(isset($_POST['email']) && !empty($_POST['email'])) {
			$email = formatInput($_POST['email']);
			if(existsEmail($_POST['email'], $conn)) {
				$email = formatInput($_POST['email']);
			} else {
				$emailOk = false;
				$dataCheckMessage .= "<div class=\"serverErrorMessage\">El \"Email\" introducido no existe en la base de datos.</div>";			
			}
		} else {
			$emailOk = false;
			$dataCheckMessage .= "<div class=\"serverErrorMessage\">El campo de \"Email\" no puede ser vacío.</div>";
		}

		if($emailOk) {
			$to      = $email;
			$title    = 'Restablecer su contraseña del juego de las preguntas.';
			$message   = 'Dirijase al siguiente enlace para cambiar su contraseña:

			http://mb11c.000webhostapp.com/ProyectoSW/reset.php?email='.$email.'&id='.password_hash(hash("sha256", $email), PASSWORD_DEFAULT);
			$headers = 'From: mblanco040@ikasle.ehu.eus';

			if(mail($to, $title, $message, $headers)) {
				$dataCheckMessage .= "<div class=\"serverInfoMessage\">Se le ha enviado un correo para que pueda reestablecer su contraseña.</div>";
			} else {
				$dataCheckMessage .= "<div class=\"serverErrorMessage\">El email no se ha enviado correctamente por un error interno.</div>";
			}
		}

		echo $dataCheckMessage;
	}

	function formatInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function existsEmail($email, $conn) {
		$query = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = \"$email\"");

		if (!$query) {
			echo "Error: " . mysqli_error($conn);
		}

		return mysqli_num_rows($query) > 0;
	}
	?>

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
			<form method="post">	
				<fieldset>
					<legend>Recuperar contraseña</legend>

					<div>
						<label for="email">Escriba su email para restablecer su contraseña.</label>
						<input type="text" name="email" autofocus/>
					</div>
					<div>
						<input type="submit" value="Enviar correo" name="submit"/>
					</div>

				</fieldset>

				<?php
					if(isset($_POST['submit']) && !empty($_POST['submit'])) {
						checkEmail();
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