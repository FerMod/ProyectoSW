<html>

<form name="form1" method="post"> <!-- Método de envío mediante POST -->
	<input type="text" name="pass">
	<input type="submit" name="enviar">
</form>

<?php

	if(checkPassword('000000003')) {
		echo '<div> Youre a faggot. </div>';
	} else {
		echo '<div> *dabs* </div>';
	}
	
	function checkPassword($pass) {
		require_once('nusoap-0.9.5/src/nusoap.php');
		
		$soapclient = new nusoap_client('http://localhost/ProyectoSW/ComprobarContraseña.php?wsdl', true);
		
		$result = $soapclient->call("checkPass", array('pass'=>$pass));
		
		if($result == "VALIDA") {
			return true;
		} else {
			return false;
		}
	}
?>
</html>