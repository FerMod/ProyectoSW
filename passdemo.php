<html>

<form name="form1" method="post"> <!-- Método de envío mediante POST -->
	<input type="text" name="pass">
	<input type="submit" name="enviar">
</form>

<?php

	if(checkPassword($_POST['pass']) == 'INVALIDA') {
		echo '<div> Youre a faggot. </div>';
	} else {
		echo '<div> *dabs* </div>';
	}
	
	function checkPassword($pass) {
		require_once('nusoap-0.9.5/src/nusoap.php');
		
		$soapclient = new nusoap_client('http://localhost/ProyectoSW/ComprobarContraseña.php?wsdl', true);
		
		
		
		return $client->call("checkPass", array('pass'=>$pass));
	}
?>
</html>