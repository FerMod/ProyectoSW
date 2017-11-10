<?php
// include('login_session.php'); // Includes login script

// if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])) {
// 	// What is doing here a logged user??
// 	header("location: layout.php");
// }

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">

	<!-- In case to use sessions, coment the code below -->
	<?php 
	function logIn() {
		include "config.php";

		// Create connection
		$conn = new mysqli($servername, $user, $pass, $database);

		// Check connection
		if ($conn->connect_error) {
			trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
		}

		try {

			if(!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['password']) && empty($_POST['password'])) { 
				throw new RuntimeException("<div class=\"serverMessage\" id=\"serverInfoMessage\">Tanto el email como la contraseña deben ser introducidas para poder continuar.</div>");
			} else {
				$email = formatInput($_POST['email']) ?? '';
				$password = formatInput($_POST['password']) ?? '';
			}

			$result = $conn->query("SELECT * FROM usuarios WHERE email = \"$email\"");
			$passwordHash = $result->fetch_assoc(); // Para comprobar que la contraseña que se escribe es correcta.
			if(password_verify($password, $passwordHash["password"]) && existsEmail($email, $conn)) {
				echo '<script>location.href="layout.php?login=' . $email . '"</script>'; // Redirecciona a la página de Inicio.
			} else {
				throw new RuntimeException("<div class=\"serverMessage\" id=\"serverErrorMessage\">El email o la contraseña introducida es incorrecta.</div>");
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
		if(isset($_GET['login']) && !empty($_GET['login'])) {
			echo '<span><a href="logout.php">Logout</a></span>';
		} else {
			echo '<span><a href="Registrar.php">Registrarse</a></span>';
			echo '&nbsp'; // Add non-breaking space
			echo '<span><a href="Login.php">Login</a></span>';
		}
		?>

		<!-- FOR FUTURE USE
		<?php
		if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])) {
			echo '<span><a href="creditos.php">Logout</a></span>';
		} else {
			echo '<span><a href="Registrar.php">Registrarse</a></span>';
			echo '&nbsp'; // Add non-breaking space
			echo '<span><a href="Login.php">Login</a></span>';
		}
		?> -->

		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<?php 
			if(isset($_GET['login']) && !empty($_GET['login'])) {
				echo '<span><a href="layout.php?login='.$_GET['login'].'">Inicio</a></span>';
				echo '<span><a href="quizes.php?login='.$_GET['login'].'">Hacer pregunta</a></span>';
				echo '<span><a href="VerPreguntasConFoto.php?login='.$_GET['login'].'">Ver preguntas</a></span>';
				echo '<span><a href="GestionPreguntas.php?login='.$_GET['login'].'">Gestionar preguntas</a></span>';
				echo '<span><a href="creditos.php?login='.$_GET['login'].'">Creditos</a></span>';
			} else {
				echo '<span><a href="layout.php">Inicio</a></span>';
				echo '<span><a href="creditos.php">Creditos</a></span>';
			}
			?>
		</nav>

		<!-- FOR FUTURE USE
		<nav class="navbar" role="navigation">
			<?php 
			if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])) {
				echo '<span><a href="layout.php">Inicio</a></span>';
				echo '<span><a href="quizes.php">Hacer pregunta</a></span>';
				echo '<span><a href="VerPreguntasConFoto.php">Ver preguntas</a></span>';
				echo '<span><a href="creditos.php">Creditos</a></span>';
			} else {
				echo '<span><a href="layout.php">Inicio</a></span>';
				echo '<span><a href="creditos.php">Creditos</a></span>';
			}
			?>
		</nav> -->
		<article class="content">
			<form id="login" enctype="multipart/form-data" method="post">	
				<fieldset>
					<legend>LOGIN</legend>

					<div>
						<label>Email</label>
						<input type="text" name="email" autofocus/>
					</div>

					<div>
						<label>Contraseña</label>
						<input type="password" name="password"/>
					</div>

					<div>
						<input type="submit" value="Acceder" name="submit"/>
					</div>

				</fieldset>

				<?php
				// Comment the 'if' and uncoment the 'echo' when using sessions
				if(isset($_POST['submit'])) {
					echo logIn();
				}
				//echo $errorMessage;
				?>

			</form>
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
