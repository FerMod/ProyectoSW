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
	
	if( strpos(file_get_contents("files/toppasswords.txt"), $x) !== false) {
        return 'INVALIDA';
    } else {
		return 'VALIDA';
	}
	
}

if ( !isset( $HTTP_RAW_POST_DATA ) )
    $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
$server->service($HTTP_RAW_POST_DATA);
?>