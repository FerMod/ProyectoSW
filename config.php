<?php

$local = false;

if($local) {
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "quiz";
} else {
	$servername = "localhost";
	$username = "id2921821_quizo";
	$password = "07151129";
	$database = "id2921821_quiz";
}

$imageUploadFolder = './img/uploads/';

if (!file_exists($imageUploadFolder)) {
	mkdir($imageUploadFolder, 0777, true);
}

?>
