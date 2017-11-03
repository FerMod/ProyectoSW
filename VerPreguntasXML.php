<?php
	$xslDoc = new DOMDocument();
	$xslDoc->load("preguntas.xsl");
	$xmlDoc = new DOMDocument();
	$xmlDoc->load("preguntas.xml");
	$proc = new XSLTProcessor();
	$proc->importStylesheet($xslDoc);
	echo $proc->transformToXML($xmlDoc);
?>