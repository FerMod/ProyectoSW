
<?php
include_once('login_session.php'); // Includes login script
if(!isset($_SESSION['login_user']) || empty($_SESSION['login_user'])) {
	header("location: layout.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">	

	<?php

	function uploadQuestion() {

		include "config.php";

		// Create connection
		$conn = new mysqli($servername, $user, $pass, $database);

		// Check connection
		if ($conn->connect_error) {
			trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
		}

		$operationMessage = "";
		$uploadOk = true;
		$imagenPregunta = null;

		try {

			$dataCheckMessage = "";

			//Insert data of quizes.php
			if(isset($_POST['email']) && !empty($_POST['email'])) { 
				$email = formatInput($_POST['email']) ?? '';
				if(!isValidEmail($email)) {
					$uploadOk = false;
					$dataCheckMessage .= "<div class=\"serverMessage\">El formato del email no es correcto.<br>Debe cumplir el formato de la UPV/EHU.</div><br>";
				}
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Email\" no puede ser vacío.</div>";
			}

			if(isset($_POST['enunciado']) && !empty($_POST['enunciado'])) { 
				$enunciado = formatInput($_POST['enunciado']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Enunciado\" no puede ser vacío.</div>";
			}

			if(isset($_POST['respuestacorrecta']) && !empty($_POST['respuestacorrecta'])) { 
				$respuestaCorrecta = formatInput($_POST['respuestacorrecta']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta correcta\" no puede ser vacio.</div>";
			}

			if(isset($_POST['respuestaincorrecta1']) && !empty($_POST['respuestaincorrecta1'])) { 
				$respuestaIncorrecta1 = formatInput($_POST['respuestaincorrecta1']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta incorrecta 1\" no puede ser vacio.</div>";
			}

			if(isset($_POST['respuestaincorrecta2']) && !empty($_POST['respuestaincorrecta2'])) { 
				$respuestaIncorrecta2 = formatInput($_POST['respuestaincorrecta2']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta incorrecta 2\" no puede ser vacio.</div>";
			}

			if(isset($_POST['respuestaincorrecta3']) && !empty($_POST['respuestaincorrecta3'])) { 
				$respuestaIncorrecta3 = formatInput($_POST['respuestaincorrecta3']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Respuesta incorrecta 3\" no puede ser vacio.</div>";
			}

			/*
			 * In the next variable, will be checked differently because of the following reasons:
			 *
			 * (http://php.net/empty)
			 * The following things are considered to be empty:
			 *
			 * "" (an empty string)
			 * 0 (0 as an integer)
			 * 0.0 (0 as a float)
			 * "0" (0 as a string)
			 * NULL
			 * FALSE
			 * array() (an empty array)
			 * $var; (a variable declared, but without a value)
			 *
			 */
			if(isset($_POST['complejidad']) && !empty($_POST['complejidad']) || $_POST['complejidad'] != 0) { 
				$complejidad = formatInput($_POST['complejidad']) ?? '';
				if(!is_numeric($complejidad)) {
					$uploadOk = false;
					$dataCheckMessage .= "<div class=\"serverMessage\">El valor del campo \"Complejidad\" debe ser un número.</div>";
				} else if($complejidad < 1 || $complejidad > 5){
					$uploadOk = false;
					$dataCheckMessage .= "<div class=\"serverMessage\">El valor del campo \"Complejidad\" debe estar entre el 1 y el 5, ambos inclusive.</div>";
				}
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Complejidad\" no puede ser vacio.</div>";
			}

			if(isset($_POST['tema']) && !empty($_POST['tema'])) { 
				$tema = formatInput($_POST['tema']) ?? '';
			} else {
				$uploadOk = false;
				$dataCheckMessage .= "<div class=\"serverMessage\">El campo de \"Tema\" no puede ser vacio.</div>";
			}

			// Check if everything is ok
			if (!$uploadOk) {
				throw new RuntimeException($dataCheckMessage);
			}

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
						$imageUploadFolder,
						$sha1Name,
						$ext
					)
				)) {
					throw new RuntimeException("<div class=\"serverErrorMessage\">Fallo al mover el archivo.</div>");
				}

				$imagenPregunta = sprintf("%s%s.%s", $imageUploadFolder, $sha1Name, $ext);

			}

		} catch (RuntimeException $e) {

			$uploadOk = false;
			$operationMessage .= $e->getMessage();

		}

		if($uploadOk) {
			$sql = "INSERT INTO preguntas(email, enunciado, respuesta_correcta, respuesta_incorrecta_1, respuesta_incorrecta_2, respuesta_incorrecta_3, complejidad, tema, imagen)
			VALUES('$email', '$enunciado', '$respuestaCorrecta', '$respuestaIncorrecta1', '$respuestaIncorrecta2', '$respuestaIncorrecta3', $complejidad, '$tema',  '$imagenPregunta')";

			if (!$result = $conn->query($sql)) {
				// Oh no! The query failed. 
				$operationMessage .= "<div class=\"serverErrorMessage\">La pregunta no se ha insertado correctamente debido a un error con la base de datos.</div>Presione el botón de volver e inténtelo de nuevo.";
			} else {
				$filePath = sprintf("%s%s", $xmlFolder, "preguntas.xml");
				insertElement($filePath, $email, $enunciado, $respuestaCorrecta, $respuestaIncorrecta1, $respuestaIncorrecta2, $respuestaIncorrecta3, $complejidad, $tema, $imagenPregunta);
				//$last_id = $conn->insert_id;
				$operationMessage .= "<div class=\"serverInfoMessage\">La pregunta se ha insertado correctamente. 
				<br>Para verla haga click <a href='VerPreguntasConFoto.php?login=".$_GET['login']."' target='_self'>aquí</a>. 
				<br><br>O si prefiere ver el archivo '.xml' generado haga click <a href='$filePath' target='_blank'>aquí</a>.</div>";
			}

			// Close connection
			$conn->close();

		} else {
			$operationMessage .= "<br>Presione el botón de volver e inténtelo de nuevo.";
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

	function insertElement($filePath, $author, $question, $correctResponse, $incorrectResponse1, $incorrectResponse2, $incorrectResponse3, $complexity, $subject, $image) {

		$xml = new SimpleXMLElement($filePath, 0, true);

		$assessmentItemElement = $xml->addChild("assessmentItem");
		$assessmentItemElement->addAttribute("complexity", $complexity);
		$assessmentItemElement->addAttribute("subject", $subject);
		$assessmentItemElement->addAttribute("author", $author);

		$itemBodyElement = $assessmentItemElement->addChild("itemBody");
		$itemBodyElement->addChild("p", $question);

		$correctResponseElement = $assessmentItemElement->addChild("correctResponse");
		$correctResponseElement->addChild("value", "$correctResponse");
		
		$incorrectResponsesElement = $assessmentItemElement->addChild("incorrectResponses");
		$incorrectResponsesElement->addChild("value", $incorrectResponse1);
		$incorrectResponsesElement->addChild("value", $incorrectResponse2);
		$incorrectResponsesElement->addChild("value", $incorrectResponse3);

		$assessmentItemElement->addChild("image", $image);
		
		$xml->asXML($filePath);

		formatFileStyle($filePath);
	}

	function formatFileStyle($filePath) {
		$dom = new DOMDocument("1.0", "UTF-8");
		$dom->preserveWhiteSpace = false;		
		$dom->formatOutput = true;
		$dom->load($filePath);
		$dom->save($filePath);
	}

	?>
</head>

<body>
	<header>

		<?php
		if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])) {
			echo '<span><a href="creditos.php">Logout</a></span>';
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
			if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])) {
				echo '<span><a href="layout.php">Inicio</a></span>';
				echo '<span><a href="quizes.php">Hacer pregunta</a></span>';
				echo '<span><a href="VerPreguntasConFoto.php">Ver preguntas</a></span>';
				echo '<span><a href="GestionPreguntas.php>Gestionar preguntas</a></span>';
				echo '<span><a href="creditos.php">Creditos</a></span>';
			} else {
				echo '<span><a href="layout.php">Inicio</a></span>';
				echo '<span><a href="creditos.php">Creditos</a></span>';
			}
			?>
		</nav>
		<article class="content">
			<label>
				<?php
				echo uploadQuestion();
				?>
			</label>
			<div>
				<input type="button" value="Volver" style="height: 20px; width: 41px;" onClick="javascript:history.go(-1)"/>
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
