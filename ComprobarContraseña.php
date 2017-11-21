<?php
require_once('nusoap-0.9.5/src/nusoap.php');

$ns="http://localhost/nusoap-0.9.5/samples";
$server = new soap_server;
$server->configureWSDL('comprobarContraseña',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

$server->register('comprobarContraseña',
array('pass'=>'xsd:string'),
array('valida'=>'xsd:string'),
$ns);

function comprobarContraseña ($pass){
	$file = fopen("/files/toppasswords.txt", "r") or die("¡Incapaz de abrir el archivo!");
	
	while(!feof($file)) {
		$passcomp = fgets($myfile);
		if($passcomp == $pass) {
			fclose($file);
			return 'INVALIDA';
		}
	}
	
	fclose($file);
	return 'VALIDA';
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>