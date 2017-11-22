<?php

require_once('nusoap-0.9.5/src/nusoap.php');

$ns = "http://localhost/ProyectoSW/ComprobarContrasena.php?wsdl";
$server = new soap_server;
$server->configureWSDL('checkPass', $ns);
$server->wsdl->schemaTargetNamespace = $ns;

$server->register('checkPass',
	array('password'=>'xsd:string'),
	array('isValid'=>'xsd:string'),
	$ns);

function checkPass($password){	
	return strpos(file_get_contents("files/toppasswords.txt"), $password) !== false ? 'INVALIDA' : 'VALIDA';
}

$server->service(file_get_contents('php://input'));

?>