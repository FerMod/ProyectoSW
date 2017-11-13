<!DOCTYPE html>
<html>
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript" src="js/verDatos.js"></script>

	<link rel="stylesheet" href="css/style.css">

	<!--
		Code Mirror (http://codemirror.net/)
		A versatile text editor implemented in JavaScript for the browser.
		It is specialized for editing code, and comes with a number of language modes and addons that
		implement more advanced editing functionality.
	-->
	
	<script src="https://codemirror.net/lib/codemirror.js"></script>
	<link rel="stylesheet" href="https://codemirror.net/lib/codemirror.css">
	<script src="https://codemirror.net/mode/xml/xml.js"></script>

	<?php include "ajaxRequestManager.php";?>

</head>

<body>
	<header>
		<?php
		if(!isset($_GET['login']) || empty($_GET['login'])) {
			echo '<span><a href="Registrar.php">Registrarse</a></span>';
			echo '&nbsp'; // Add non-breaking space
			echo '<span><a href="Login.php">Login</a></span>';
		} else {
			echo '<span><a href="layout.php">Logout</a></span>';
		}
		?>
		<h2>Quiz: el juego de las preguntas</h2>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<?php 
			if(isset($_GET['login']) || !empty($_GET['login'])) {
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
		<article class="content">
			<fieldset>
				<legend>Mis preguntas</legend>
				<div id="divpreg">
				<?php
					$xml = simplexml_load_file("xml/preguntas.xml");
					$preguntastot = count($xml->xpath('/assessmentItems/assessmentItem'));
					$preguntasem = count($xml->xpath('/assessmentItems/assessmentItem[@author="jvadillo001@ikasle.ehu.es"]'));
					
					echo "<label id='numpregs';>".$preguntasem."/".$preguntastot."</label>";
				?>
				</div>
			</fieldset>
			<form id="formGestionPreguntas" name="formGestionPreguntas" method="post" action="" enctype="multipart/form-data">

				<fieldset>
					<legend>DATOS DE LA PREGUNTA</legend>
					<div>
						<label for="email">Email*:</label>
						<input type="text" id="email" name="email" autofocus/>
					</div>
					<div>
						<label for="enunciado">Enunciado de la pregunta*:</label>
						<input type="text" id="enunciado" name="enunciado" size="35" />
					</div>
					<div>
						<label for="respuestacorrecta">Respuesta correcta*:</label>
						<input type="text" id="respuestacorrecta" name="respuestacorrecta" size="35" />
					</div>
					<div>
						<label for="respuestaincorrecta1">Respuesta incorrecta 1*:</label>
						<input type="text" id="respuestaincorrecta1" name="respuestaincorrecta1" size="35" />
					</div>
					<div>
						<label for="respuestaincorrecta2">Respuesta incorrecta 2*:</label>
						<input type="text" id="respuestaincorrecta2" name="respuestaincorrecta2" size="35" />
					</div>
					<div>
						<label for="respuestaincorrecta3">Respuesta incorrecta 3*:</label>
						<input type="text" id="respuestaincorrecta3" name="respuestaincorrecta3" size="35" />
					</div>
					<div>
						<label for="complejidad">Complejidad (1..5)*:</label>
						<input type="text" id="complejidad" name="complejidad" size="10" />
					</div>
					<div>
						<label for="tema">Tema (subject)*:</label>
						<input type="text" id="tema" name="tema" size="10" />
					</div>
					<div>
						<label for="imagen">Subir imagen:</label>
						<input type="file" name="imagen" id="imagen"/>

						<img id="previewImage" class="modalImage" src="#" alt="Imagen de la pregunta"/>
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
					<div>
						<input type="submit" id="enviarPregunta" name="enviarPregunta" value="Enviar pregunta"/>
						<!-- Comment the hidden input field when using sessions -->
						<input type="hidden" id="login" name="login" value="<?php echo $_GET['login'];?>"/>
					</div>
				</fieldset>
			</form>
			<div id="operationResult"></div>
			<div id="visualizarDatos"></div>

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
