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
	/* SOLUCIÓN 1
	$file = fopen("/files/toppasswords.txt", "r") or die("¡Incapaz de abrir el archivo!");
	
	while($passcomp = fgets($file) !== false) {
		if(strpos($passcomp, $pass) == true) {
			fclose($file);
			return 'INVALIDA';
		}
	}
	
	fclose($file);
	return 'VALIDA';
	*/
	
	//SOLUCIÓN 2
	$lines = file("files/toppasswords.txt");
	$i = 0;
	while($i < sizeof($lines)) {
		if(strpos($lines[$i], $pass) == true) {
			return 'INVALIDA';
		}
		$i = $i + 1;
	}
	return 'VALIDA';
}

if ( !isset( $HTTP_RAW_POST_DATA ) )
	$HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
$server->service($HTTP_RAW_POST_DATA);
?>