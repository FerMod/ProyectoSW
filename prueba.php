<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = formatInput($_POST['email']) ?? '';
  $enunciado = formatInput($_POST['enunciado']) ?? '';
  $respuestaCorrecta = formatInput($_POST['respuestacorrecta']) ?? '';
  $respuestaIncorrecta = formatInput($_POST['respuestaincorrecta']) ?? '';
  $respuestaIncorrecta1 = formatInput($_POST['respuestaincorrecta1']) ?? '';
  $respuestaIncorrecta2 = formatInput($_POST['respuestaincorrecta2']) ?? '';
  $complejidad = formatInput($_POST['complejidad']) ?? '';
  $tema = formatInput($_POST['tema']) ?? '';
  
  
  echo "<h2>Form data:</h2>";
  echo "<strong>email:</strong> " . $email;
  echo "<br><strong>enunciado:</strong> " . $enunciado;
  echo "<br><strong>respuestaCorrecta:</strong> " . $respuestaCorrecta;
  echo "<br><strong>respuestaIncorrecta:</strong> " . $respuestaIncorrecta;
  echo "<br><strong>respuestaIncorrecta1:</strong> " . $respuestaIncorrecta1;
  echo "<br><strong>respuestaIncorrecta2:</strong> " . $respuestaIncorrecta2;
  echo "<br><strong>complejidad:</strong> " . $complejidad;
  echo "<br><strong>tema:</strong> " . $tema;
  
  echo "<hr>";

  echo phpinfo();
  
}

// Format the input for security reasons
function formatInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
