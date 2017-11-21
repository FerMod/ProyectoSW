<FORM NAME="datos" ID="datos" METHOD="POST">
<input type='text' name='sumando1'></input>
<input type='text' name='sumando2'></input>
<input type='submit' name='sumar' value='SUMAR'></input>
</form>
<?php
//incluimos la clase nusoap.php
require_once('nusoap-0.9.5/src/nusoap.php');
//creamos el objeto de tipo soapclient.
//http://www.mydomain.com/server.php se refiere a la url
//donde se encuentra el servicio SOAP que vamos a utilizar.
$soapclient = new nusoap_client( 'http://localhost/ProyectoSW/demo.php?wsdl',
true);
if (isset($_POST['sumando1'])){
echo '<h1>La suma es: ' . $soapclient->call('sumar',array( 'x'=>$_POST['sumando1'],
'y'=>$_POST['sumando2'])). '</h1>';
//echo '<h2>Request</h2><pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';
//echo '<h2>Response</h2><pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
//echo '<h2>Debug</h2>';
//echo '<pre>' . htmlspecialchars($soapclient->debug_str, ENT_QUOTES) . '</pre>';
}
?>