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
		<span><a href="login">Login</a></span>
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
			<form id="registro">	
				<fieldset>
					<legend>REGISTRO</legend>
					<div>
						<label>Escriba su email <strong><font size="3" color="red">*</font></strong></label>
					</div>
						<input type="text" name="email">

					<div>
						<label>Nombre y apellidos <strong><font size="3" color="red">*</font></strong></label>
					</div>
						<input type="text" name="nomap">
					
					<div>
						<label>Nickname <strong><font size="3" color="red">*</font></strong></label>
					</div>
						<input type="text" name="nick">
						
					<div>
						<label>Contraseña <strong><font size="3" color="red">*</font></strong></label>
					</div>
						<input type="password" name="con">
						
					<div>
						<label>Repetir contraseña <strong><font size="3" color="red">*</font></strong></label>
					</div>
						<input type="password" name="conrep">
		
						<br>
						
						<br>
						
						<br>
						
					<div>
						<label>Elegir avatar</label><br>
						<input type="button" name="foto" value="Seleccionar archivo">	
					</div>
					
						<br>
						
						<br>
						
					<input type="submit" value="Registrarse">
					<input type="reset" value="Restaurar campos">	
					
						<!--
							Email* (campo clave),
							Nombre y Apellidos* (al menos dos palabras),
							Nick* (una palabra),
							Password* (al menos de longitud seis),
							Repetir Password*,
							Foto (opcional)
						-->
					
				</fieldset>
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
