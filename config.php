<?php

$local = true;

if($local) {
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "quiz";
} else {
	$servername = "localhost";
	$username = "";
	$password = "";
	$database = "";
}

$imageUploadFolder = './img/uploads/';
$profileImageFolder = './img/users/';

if (!file_exists($imageUploadFolder)) {
	mkdir($imageUploadFolder, 0777, true);
}

if (!file_exists($profileImageFolder)) {
	mkdir($profileImageFolder, 0777, true);
}

?>
