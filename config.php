<?php

$local = true;

if($local) {
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "quiz";
} else {
	$servername = "";
	$username = "";
	$password = "";
	$database = "quiz";
}

<<<<<<< HEAD

$imageUploadFolder = './img/uploads/';

if (!file_exists($imageUploadFolder)) {
    mkdir($imageUploadFolder, 0777, true);
}

=======
$imageUploadFolder = './img/uploads/';

if (!file_exists($imageUploadFolder)) {
	mkdir($imageUploadFolder, 0777, true);
}
>>>>>>> origin/FerMod

?>
