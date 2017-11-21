<?php
require_once('/nusoap-0.9.5/src/nusoap.php');
require_once('/nusoap-0.9.5/docs/nusoap/_class_wsdlcache_php.html');

$ns="http://localhost/nusoap-0.9.5/samples";
$server = new soap_server;
$server->configureWSDL('comprobarContraseña',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

$server->register('comprobarContraseña',
array('pass'=>'xsd:string'),
array('z'=>'xsd:string'),
$ns);

function sumar ($pass){
	return null;
}
//llamamos al método service de la clase nusoap
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>