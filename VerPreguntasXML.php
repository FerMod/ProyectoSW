<?php

$xslDoc = new DOMDocument();
$xslDoc->load($xmlFolder . "preguntas.xsl");

$xmlDoc = new DOMDocument();
$xmlDoc->load($xmlFolder . "preguntas.xml");

$proc = new XSLTProcessor();
$proc->importStylesheet($xslDoc);

echo $proc->transformToXML($xmlDoc);

?>
