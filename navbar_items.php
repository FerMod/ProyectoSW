<?php 

echo '<span><a href="layout.php">Inicio</a></span>';

if((isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) && (isset($_SESSION['user_type']) && !empty($_SESSION['user_type']))) {

	$userType = $_SESSION['user_type'] ?? '';				

	switch ($userType) {
		case 'teacher':
		echo '<span><a href="RevisarPreguntas.php">Revisar preguntas</a></span>';
		break;

		case 'student':
		echo '<span><a href="GestionPreguntas.php">Hacer pregunta</a></span>';
		break;
	}


	echo '<span><a href="VerPreguntasConFoto.php">Ver preguntas</a></span>';
	

}

echo '<span><a href="creditos.php">Creditos</a></span>';
echo '<span><a href="registroJugador.php">¿Cuánto sabes? pruébame</a></span>';

?>
