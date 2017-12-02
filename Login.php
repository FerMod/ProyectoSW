
<?php

include_once('login_session.php'); // Includes login script
include('session_timeout.php');

if(isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
	// What is doing here a logged user??
	refreshSessionTimeout();
	header("location: layout.php");
}

$config = include("config.php");

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Preguntas - Login</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">

	<!-- In case to use sessions, coment the code below -->
	<?php 
	function logIn() {

		global $config;
		
		// Create connection
		$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

		// Check connection
		if ($conn->connect_error) {
			trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
		}

		try {

			if(!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['password']) && empty($_POST['password'])) { 
				throw new RuntimeException("<div class=\"serverInfoMessage\">Tanto el email como la contraseña deben ser introducidas para poder continuar.</div>");
			} else {
				$email = formatInput($_POST['email']) ?? '';
				$password = $_POST['password'] ?? '';
			}

			$result = $conn->query("SELECT * FROM usuarios WHERE email = \"$email\"");
			$passwordHash = $result->fetch_assoc(); // Para comprobar que la contraseña que se escribe es correcta.
			if(password_verify(hash("sha256", $password), $passwordHash["password"]) && existsEmail($email, $conn)) {
				echo '<script>location.href="layout.php"</script>'; // Redirecciona a la página de Inicio.
			} else {
				throw new RuntimeException("<div class=\"serverErrorMessage\">El email o la contraseña introducida es incorrecta.</div>");
			}

		} catch (RuntimeException $e) {
			$operationMessage = $e->getMessage();
		}

		return $operationMessage;

	}

	// Format the input for security reasons
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
			<form id="login" enctype="multipart/form-data" method="post">	
				<fieldset>
					<legend>LOGIN</legend>

					<div>
						<label>Email</label>
						<!-- <input type="text" name="email" autofocus/>
						<input type="text" name="email" autofocus value="admin"/> -->
						<input type="text" name="email" autofocus value="web000@ehu.es"/>
					</div>

					<div>
						<label>Contraseña</label>
						<!-- <input type="password" name="password"/>
						<input type="password" name="password" autofocus value="admin"/> -->
						<input type="password" name="password" autofocus value="web000"/>
					</div>

					<div>
						<input type="submit" value="Acceder" name="submit"/>
					</div>

				</fieldset>

				<?php
				echo $errorMessage;
				?>

			</form>
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
