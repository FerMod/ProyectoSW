<?php

// define variables and set to empty values
$email = "";
$enunciado = "";
$respuestaCorrecta = "";
$respuestaIncorrecta = "";
$respuestaIncorrecta1 = "";
$respuestaIncorrecta2 = "";
$complejidad = "";
$tema = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = formatInput($_POST["email"]);
  $enunciado = formatInput($_POST["enunciado"]);
  $respuestaCorrecta = formatInput($_POST["respuestacorrecta"]);
  $respuestaIncorrecta = formatInput($_POST["respuestaincorrecta"]);
  $respuestaIncorrecta1 = formatInput($_POST["respuestaincorrecta1"]);
  $respuestaIncorrecta2 = formatInput($_POST["respuestaincorrecta2"]);
  $complejidad = formatInput($_POST["complejidad"]);
  $tema = formatInput($_POST["tema"]);
  
  printInput();
}

// Format the input for security reasons
function formatInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function printInput() {
  echo "<h2>Form data:</h2>";
  echo "email: $email";
  echo "<br>";
  echo "enunciado: $enunciado";
  echo "<br>";
  echo "respuestaCorrecta: $respuestaCorrecta";
  echo "<br>";
  echo "respuestaIncorrecta: $respuestaIncorrecta";
  echo "<br>";
  echo "respuestaIncorrecta1: $respuestaIncorrecta1";
  echo "<br>";
  echo "respuestaIncorrecta2: $respuestaIncorrecta2";
  echo "<br>";
  echo "complejidad: $complejidad";
  echo "<br>";
  echo "tema: $tema";
}

?>
