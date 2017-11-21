<?php
//incluimos la clase nusoap.php
require_once('nusoap-0.9.5/src/nusoap.php');
//creamos el objeto de tipo soap_server
$ns="http://localhost/ProyectoSW/demo.php?wsdl";
$server = new soap_server;
$server->configureWSDL('sumar',$ns);
$server->wsdl->schemaTargetNamespace=$ns;
//registramos la función que vamos a implementar
$server->register('sumar',
array('x'=>'xsd:int','y'=>'xsd:int'),
array('z'=>'xsd:int'),
$ns);
//implementamos la función
function sumar ($x, $y){
return $x + $y;
}
//llamamos al método service de la clase nusoap
if ( !isset( $HTTP_RAW_POST_DATA ) )
	$HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
$server->service($HTTP_RAW_POST_DATA);
?>
