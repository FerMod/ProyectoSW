<?php

$config = include("config.php");

$xslDoc = new DOMDocument();
$xslDoc->load($config["folders"]["xml"] . "preguntas.xsl");

$xmlDoc = new DOMDocument();
$xmlDoc->load($config["folders"]["xml"] . "preguntas.xml");

$proc = new XSLTProcessor();
$proc->importStylesheet($xslDoc);

echo $proc->transformToXML($xmlDoc);

?>
