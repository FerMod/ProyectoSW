<?php
include_once('login_session.php'); // Includes login script
include_once('session_timeout.php');

if(isValidSession()) {
	refreshSessionTimeout();
}

//$config = include("config.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<!-- Uncomment the following line to auto refresh the page -->
	<!-- <meta http-equiv="refresh" content="<?php echo $config["session"]["expiration_time"]; ?>"> -->

	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Preguntas - Layout</title>

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>	

	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">

	<link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">

</head>

<?php

	function createPlayersTable() {

		global $config;
		$i = 0;

		// Create connection
		$conn = new mysqli($config["db"]["servername"], $config["db"]["username"], $config["db"]["password"], $config["db"]["database"]);
		
		// Check connection
		if ($conn->connect_error) {
			trigger_error("Database connection failed: "  . $conn->connect_error, E_USER_ERROR);
		}

		// Perform an SQL query
		$sql = "SELECT * FROM jugadores ORDER BY puntuacion DESC limit 0, 10";

		if (!$result = $conn->query($sql)) {
			echo "Sorry, the website is experiencing problems.";
		} else {

			if ($result->num_rows != 0) {

				echo "<table class=\"infoTable\" readonly>";
				echo "<thead>";
				echo "<tr>";
				echo "<th>Puesto</th>";	
				echo "<th class=\"sortable\">Jugador</th>";			
				echo "<th>Puntuacion</th>";
				echo "<th>Preguntas respondidas</th>";
				echo "<th>Preguntas acertadas</th>";
				echo "<th>Preguntas falladas</th>";
				echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				while ($player = $result->fetch_assoc()) {
					echo "<tr>";
					echo "<td>".++$i."</td>";
					echo "<td>$player[nick]</td>";
					echo "<td>$player[puntuacion]</td>";
					echo "<td>$player[preguntas_respondidas]</td>";
					echo "<td>$player[preguntas_acertadas]</td>";
					echo "<td>$player[preguntas_falladas]</td>";
					echo "</tr>";
				}				
				echo "</tbody>";
				echo "</table>";
				$result->free();
			} else {
				echo "We could not find any values, sorry about that.";
			}

		}

		// Close connection
		$conn->close();

	}

?>

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
		<?php
		if((isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) && (isset($_SESSION['user_type']) && !empty($_SESSION['user_type']))) {		

			$userType = '';
			switch ($_SESSION['user_type']) {
				case 'teacher':
				$userType = 'profesor';
				break;

				case 'student':
				$userType = 'alumno';
				break;
			}

			echo '<span>¡Bienvenido ' . $userType . ' "' . $_SESSION['logged_user'] . '"! </span>';

		}
		?>
	</header>
	<div class="container">
		<nav class="navbar" role="navigation">
			<?php include('navbar_items.php'); ?>
		</nav>
		<article class="content">
			<div>
				<h2 style="text-align: center; padding-bottom: 5px;">Ranking quizers</h2>
			</div>
			<div class="scrollContent">
				<?php createPlayersTable()?>
			</div>
			<!-- The Modal -->
			<div id="modalElement" class="modal">

				<!-- The Close Button -->
				<span class="close">&times;</span>

				<!-- Modal Content (The Image) -->
				<img class="modal-content" id="img01">

				<!-- Modal Caption (Image Text) -->
				<div id="caption"></div>
			</div>
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
