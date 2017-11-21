<?php
require_once('nusoap-0.9.5/src/nusoap.php');

$ns="http://localhost/ProyectoSW/ComprobarContrasena.php?wsdl";
$server = new soap_server;
$server->configureWSDL('checkPass',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

$server->register('checkPass',
array('x'=>'xsd:string'),
array('z'=>'xsd:string'),
$ns);

function checkPass ($x){
	$file = fopen("/files/toppasswords.txt", "r") or die("¡Incapaz de abrir el archivo!");
	
	$passcomp = fgets($file);
	$z = 'VALIDA';
	while(!feof($file)) {
		if(strpos($passcomp, $x) == true) {
			fclose($file);
			$z = 'INVALIDA';
		}
		$passcomp = fgets($file);
	}
	
	fclose($file);
	return $z;
}

if ( !isset( $HTTP_RAW_POST_DATA ) )
    $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
$server->service($HTTP_RAW_POST_DATA);
?>