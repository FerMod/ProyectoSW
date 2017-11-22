
<?php

include_once('login_session.php'); // Includes login script

if(isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
	// Another account? how many do you need??
	header("location: layout.php");
}

$config = include("config.php");

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
	<link rel="icon" href="favicon.png" type="image/x-icon">
	<title>Preguntas - Registrar</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">

	<?php

	function createUser() {		

		// Create connection
		$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);

		// Check connection
		if ($conn->connect_error) {
			trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
		}

		$operationMessage = "";
		$dataCorrect = true;

		try {

			$dataCheckMessage = "";

			if(isset($_POST['email']) && !empty($_POST['email'])) { 
				$email = formatInput($_POST['email']) ?? '';
				if(!isValidEmail($email)) {
					$dataCorrect = false;
					$dataCheckMessage .= "<div class=\"serverMessage\">El formato del email no es correcto.<br>Debe cumplir el formato de la UPV/EHU.</div>";
				}
				if (existsEmail($email, $conn)) {
					$dataCorrect = false;
					$dataCheckMessage .= "<div class=\"serverErrorMessage\">Ya existe una cuenta con el email introducido.</div>";
				}
			} else {
				$dataCorrect = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo \"Email\" no puede ser vacío.</div>";
			}

			if(isset($_POST['nombre']) && !empty($_POST['nombre'])) { 
				$nombre = formatInput($_POST['nombre']) ?? '';
			} else {
				$dataCorrect = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo \"Nombre y Apellidos\" no puede ser vacío.</div>";
			}

			if(isset($_POST['username']) && !empty($_POST['username'])) { 
				$username = formatInput($_POST['username']) ?? '';
			} else {
				$dataCorrect = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo \"Username\" no puede ser vacío.</div>";
			}

			if(isset($_POST['password']) && !empty($_POST['password'])) { 
				$password = $_POST['password'] ?? '';
			} else {
				$dataCorrect = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo \"Contraseña\" no puede ser vacío.</div>";
			}

			if(isset($_POST['passwordRep']) && !empty($_POST['passwordRep'])) { 
				$passwordRep = $_POST['passwordRep'] ?? '';
			} else {
				$dataCorrect = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo \"Repetir contraseña\" no puede ser vacío.</div>";
			}

			// Check if everything is ok
			if (!$dataCorrect) {
				throw new RuntimeException($dataCheckMessage);
			}

			if($password == $passwordRep) { // Comprobamos que la contraseña escrita coincide con su repetición.
				$hashedPassword = password_hash(hash("sha256", $password), PASSWORD_DEFAULT); // Guardamos de forma segura la contraseña.
			} else {
				throw new RuntimeException("<div class=\"serverErrorMessage\">Las contraseñas no coinciden entre sí, vuelva a escribirla.</div>");
			}
			
			$imagen = null;
			
			// Undefined | Multiple Files | $_FILES Corruption Attack
			// If this request falls under any of them, treat it invalid.
			if (!isset($_FILES['imagen']['error']) || is_array($_FILES['imagen']['error'])) {
				throw new RuntimeException("<div class=\"serverErrorMessage\">Parametros inválidos.</div>");
			}
			

			$containsImage = false;

			// Check $_FILES['imagen']['error'] value.
			switch ($_FILES['imagen']['error']) {
				case UPLOAD_ERR_OK:
				$containsImage = true;
				case UPLOAD_ERR_NO_FILE:
				//Nothing to do here, the file upload is optional
				break;
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException("<div class=\"serverErrorMessage\">Tamaño de archivo excedido.</div>");
				default:
				throw new RuntimeException("<div class=\"serverErrorMessage\">Error desconocido.</div>");
			}

			if($containsImage) {

				// You should also check filesize here. 
				if ($_FILES['imagen']['size'] > 1000000) {
					throw new RuntimeException("<div class=\"serverErrorMessage\">Tamaño de archivo excedido.");
				}

				// DO NOT TRUST $_FILES['imagen']['mime'] VALUE !!
				// Check MIME Type by yourself.
				$finfo = new finfo(FILEINFO_MIME_TYPE);
				if (false === $ext = array_search(
					$finfo->file($_FILES['imagen']['tmp_name']),
					array(
						'jpg' => 'image/jpeg',
						'png' => 'image/png',
						'gif' => 'image/gif',
					),
					true
				)) {
					throw new RuntimeException("<div class=\"serverErrorMessage\">Formato de archivo inválido.</div>");
				}

				// You should name it uniquely.
				// DO NOT USE $_FILES['imagen']['name'] WITHOUT ANY VALIDATION !!
				// On this example, obtain safe unique name from its binary data.
				$sha1Name = sha1_file($_FILES['imagen']['tmp_name']);
				if (!move_uploaded_file(
					$_FILES['imagen']['tmp_name'],
					sprintf('%s%s.%s',
						$profileImageFolder,
						$sha1Name,
						$ext
					)
				)) {
					throw new RuntimeException("<div class=\"serverErrorMessage\">Fallo al mover el archivo.</div>");
				}

				$imagen = sprintf('%s%s.%s', $profileImageFolder, $sha1Name, $ext);

			}

		} catch (RuntimeException $e) {
			$dataCorrect = false;
			$operationMessage .= $e->getMessage();
		}

		if($dataCorrect) {
			$sql = "INSERT INTO usuarios (email, password, nombre, username, imagen) VALUES ('$email', '$hashedPassword', '$nombre', '$username', '$imagen')";

			if(!$result = $conn->query($sql)) {
				$operationMessage .= "<script language=\"javascript\">alert(\"Ha ocurrido un error con la base de datos, por favor, inténtelo de nuevo.\");</script>"; 
			} else {
				$operationMessage .= "<script language=\"javascript\">alert(\"¡Se ha registrado con éxito!\"); window.location.replace(\"layout.php\");</script>";
			}

			// Close connection
			$conn->close();

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

	function isValidEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[a-zA-Z]+\\d{3}@ikasle\.ehu\.(eus|es)$/', $email);
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
			<form id="registro" enctype="multipart/form-data" method="post">	
				<fieldset>
					<legend>REGISTRO</legend>

					<div>
						<label>Escriba su email<strong><font size="3" color="red">*</font></strong></label>
						<input id="email" type="email" name="email" autofocus required/>
					</div>

					<div>
						<label>Nombre y apellidos<strong><font size="3" color="red">*</font></strong></label>
						<input type="text" name="nombre" required/>
					</div>

					<div>
						<label>Username<strong><font size="3" color="red">*</font></strong></label>
						<input type="text" name="username" required/>
					</div>

					<div>
						<label>Contraseña<strong><font size="3" color="red">*</font></strong></label>
						<input id="password" type="password" name="password" required/>
						<!-- <div class="tooltip-bg">
							<div class="tip"></div> 
							<label type="text" placeholder="type here" id="tooltipContent">
							</div> -->
						</div>

						<div>
							<label>Repetir contraseña<strong><font size="3" color="red">*</font></strong></label>
							<input type="password" name="passwordRep" required/>
						</div>

						<div>
							<label for="imagen">Elegir avatar</label>
							<input type="file" name="imagen" id="imagen"/>

							<img id="previewImage" class="modalImage" src="#" alt="Imagen del perfil"/>
							<input type="button" id="quitarImagen" value="Quitar Imagen"/>

							<!-- The Modal -->
							<div id="modalElement" class="modal">

								<!-- The Close Button -->
								<span class="close">&times;</span>

								<!-- Modal Content (The Image) -->
								<img class="modal-content" id="img01">

								<!-- Modal Caption (Image Text) -->
								<div id="caption"></div>
							</div>

						</div>

					</br>
					
					<!-- <div style="display:table-cell; vertical-align:middle; text-align:center">
						<label>Escriba el captcha que hay a continuación</label></br>
						<img src="captcha.php"></br>
						<input type="text" name="captchatext"/>
					</div> -->

					<div>
						<input type="submit" value="Registrarse" name="submit"/>
						<input type="reset" value="Restaurar campos"/>	
					</div>

				</fieldset>

				<?php 
				if(isset($_POST['submit'])){
					echo createUser();
				} 
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
