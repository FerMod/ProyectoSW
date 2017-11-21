<?php
require_once('nusoap-0.9.5/src/nusoap.php');

$ns="http://localhost/nusoap-0.9.5/samples";
$server = new soap_server;
$server->configureWSDL('checkPass',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

$server->register('checkPass',
array('pass'=>'xsd:string'),
array('valida'=>'xsd:string'),
$ns);

function checkPass ($pass){
	$file = fopen("/files/toppasswords.txt", "r") or die("¡Incapaz de abrir el archivo!");
	
	while(!feof($file)) {
		$passcomp = fgets($file);
		if($passcomp == $pass) {
			fclose($file);
			return 'INVALIDA';
		}
	}
	
	fclose($file);
	return 'VALIDA';
}

if ( !isset( $HTTP_RAW_POST_DATA ) )
	$HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
$server->service($HTTP_RAW_POST_DATA);
?>