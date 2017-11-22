<?php

$local = true;

if($local) {
	$servername = "localhost";
	$user = "root";
	$pass = "";
	$database = "quiz";
} else {
	$servername = "localhost";
	$user = "";
	$pass = "";
	$database = "";
}

$imageUploadFolder = './img/uploads/';
$profileImageFolder = './img/users/';
$xmlFolder = './xml/';


if (!file_exists($imageUploadFolder)) {
	mkdir($imageUploadFolder, 0777, true);
}

if (!file_exists($profileImageFolder)) {
	mkdir($profileImageFolder, 0777, true);
}

if (!file_exists($xmlFolder)) {
	mkdir($xmlFolder, 0777, true);
}

?>
