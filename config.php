<?php

$local = true;

// Parse .ini file with sections
$iniConfig = parse_ini_file("config.ini", true);
// print_r($iniConfig);

if (!file_exists($iniConfig["folders"]["image_uploads"])) {
	mkdir($iniConfig["folders"]["image_uploads"], 0777, true);
}

if (!file_exists($iniConfig["folders"]["profile_images"])) {
	mkdir($iniConfig["folders"]["profile_images"], 0777, true);
}

if (!file_exists($iniConfig["folders"]["xml"])) {
	mkdir($iniConfig["folders"]["xml"], 0777, true);
}

return $iniConfig;

?>
