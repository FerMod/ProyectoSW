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
			
			if(!@$_SESSION["email"]) {
				echo '<span><a href="Login.php">Login</a></span>';
			} else {
				echo '<span><a href="logout.php">Logout</a></span>';
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
			<form id="registro" enctype="multipart/form-data" method="post">	
				<fieldset>
					<legend>REGISTRO</legend>

					<div>
						<label>Escriba su email<strong><font size="3" color="red">*</font></strong></label>
						<input type="text" name="email"/>
					</div>

					<div>
						<label>Nombre y apellidos<strong><font size="3" color="red">*</font></strong></label>
						<input type="text" name="nombre"/>
					</div>
					
					
					<div>
						<label>Username<strong><font size="3" color="red">*</font></strong></label>
						<input type="text" name="username"/>
					</div>

					<div>
						<label>Contraseña<strong><font size="3" color="red">*</font></strong></label>
						<input type="password" name="password"/>
					</div>

					<div>
						<label>Repetir contraseña<strong><font size="3" color="red">*</font></strong></label>
						<input type="password" name="passwordRep"/>
					</div>

					<div>
						<label>Elegir avatar</label>
						<input type="file" name="imagen" id="imagen"/>
					</div>

					<div>
						<input type="submit" value="Registrarse" name="submit"/>
						<input type="reset" value="Restaurar campos"/>	
					</div>

					<!--
					Email* (campo clave),
					Nombre y Apellidos* (al menos dos palabras),
					Nick* (una palabra),
					Password* (al menos de longitud seis),
					Repetir Password*,
					Foto (opcional)

					En la bbdd:
					email
					nombre
					username
					password (hash)
					imagen -->

				</fieldset>
			</form>
		</article>		
		<aside class="sidebar">
			Sidebar contents<br/>(sidebar)
		</aside>
		
		<?php
			
			function createUser() {
					include "config.php";

					// Create connection
					$conn = new mysqli($servername, $username, $password, $database);

					// Check connection
					if ($conn->connect_error) {
						trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
					}
			
					$email = $_POST['email'];
					$nombre = $_POST['nombre'];
					$username = $_POST['username'];
					$pass = $_POST['password'];
					$passrep = $_POST['passwordRep'];
					
					$imagen = null;
					
					if(!empty($email) && isset($email) && !empty($nombre) && isset($nombre) && !empty($username) && isset($username) && !empty($pass) && isset($pass) && !empty($passrep) && isset($passrep) && isValidEmail($email)) {
						if($pass == $passrep) { #Comprobamos que la contraseña escrita coincide con su repetición.
							$pass = password_hash($_POST['password'], PASSWORD_DEFAULT); #Guardamos de forma segura la contraseña.
							
							// Undefined | Multiple Files | $_FILES Corruption Attack
							// If this request falls under any of them, treat it invalid.
							if (!isset($_FILES['imagen']['error']) || is_array($_FILES['imagen']['error'])) {
								throw new RuntimeException("<div class=\"serverMessage\" id=\"serverErrorMessage\">Parametros inválidos.</div>");
							}
							
						try {	
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
								throw new RuntimeException("<div class=\"serverMessage\" id=\"serverErrorMessage\">Tamaño de archivo excedido.</div>");
								default:
								throw new RuntimeException("<div class=\"serverMessage\" id=\"serverErrorMessage\">Error desconocido.</div>");
							}

							if($containsImage) {

								// You should also check filesize here. 
								if ($_FILES['imagen']['size'] > 1000000) {
									throw new RuntimeException("<div class=\"serverMessage\" id=\"serverErrorMessage\">Tamaño de archivo excedido.");
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
									throw new RuntimeException("<div class=\"serverMessage\" id=\"serverErrorMessage\">Formato de archivo inválido.</div>");
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
									throw new RuntimeException("<div class=\"serverMessage\" id=\"serverErrorMessage\">Fallo al mover el archivo.</div>");
								}
								
								$imagen = sprintf('%s%s.%s', $profileImageFolder, $sha1Name, $ext);
							}
						} catch (RuntimeException $e) {
							$operationMessage .= $e->getMessage();
						}
							
							
							$sql = "INSERT INTO `usuarios` (`email`, `password`, `nombre`, `username`, `imagen`) VALUES ('$email', '$pass', '$nombre', '$username', '$imagen')";
							
							if($conn->query($sql)) {
								echo '<script language="javascript">alert("¡Se ha registrado el usuario con éxito!");</script>'; 
							} else {
								echo '<script language="javascript">alert("Ha ocurrido un error con la base de datos, por favor, inténtelo de nuevo.");</script>'; 
							}
						} else {
							echo '<script language="javascript">alert("La contraseña no coincide con su repetición, vuelva a intentarlo.");</script>'; 
						}
					} else if(!isValidEmail($email)) {
						echo '<script language="javascript">alert("Debe escribir el email con el formato correcto. Ejemplo: correo123@ikasle.ehu.eus/correo123@ikasle.ehu.es");</script>';
					} else {
						echo '<script language="javascript">alert("No se puede dejar ningún campo clave vacío.");</script>'; 
					}
			}
			
			function isValidEmail($email) {
				return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[a-zA-Z]+\\d{3}@ikasle\.ehu\.(eus|es)$/', $email);
			}
		?>
	</div>
		<?php 
			if(isset($_POST['submit'])){
				createUser();
			} 
		?>
	<footer>
		<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank">¿Qué es un Quiz?</a></p>
		<a href='https://github.com/FerMod/ProyectoSW'>Link GITHUB</a>
	</footer>
</body>
</html>