<html>

<form name="form1" method="post"> <!-- Método de envío mediante POST -->
	<input type="text" name="pass">
	<input type="submit" name="enviar">
</form>

<?php
	checkPassword('00000000');
	//checkPassword('00000000');
	//checkPassword('000000003');
	
	/*if(checkPassword('000000003')) {
		echo '<script> alert("Youre a faggot."); </script>';
	} else {
		echo '<script> alert("Dab."); </script>';
	}*/
	
	function checkPassword($pass) {
		require_once('nusoap-0.9.5/src/nusoap.php');
		
		$soapclient = new nusoap_client('http://localhost/ProyectoSW/ComprobarContrasena.php?wsdl', true);
		
		$result = $soapclient->call("checkPass", array('x'=>$pass));
		
		echo '<script> alert('.$result.'); </script>';
		
		if($result == 'INVALIDA') {
			echo 'Ya tira.';
			return true;
		} else {
			echo 'No tira.';
			return false;
		}
	}
?>
</html>