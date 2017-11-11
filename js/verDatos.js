
//(IE7+, Firefox, Chrome, Safari, and Opera)
XMLHttpRequestObject = new XMLHttpRequest();
XMLHttpRequestObject.onreadystatechange = function() {
	if (XMLHttpRequestObject.readyState==4) {
		var myCodeMirror = CodeMirror(document.getElementById('visualizarDatos'), {
			value: XMLHttpRequestObject.responseText,
			mode:  "xml",
			lineNumbers: "true",
			readOnly: "true"
		});
	}
}

function mostrarDatos(filePath) {
	XMLHttpRequestObject.open("GET", filePath);
	XMLHttpRequestObject.send(null);
	
	$("#email").serialize(); //Serializing the objects (not photo included)
	$("#enunciado").serialize();
	$("#respuestacorrecta").serialize();
	$("#respuestaincorrecta1").serialize();
	$("#respuestaincorrecta2").serialize();
	$("#respuestaincorrecta3").serialize();
	$("#complejidad").serialize();
	$("#tema").serialize();
	
	XMLHttpRequestObject.open("POST", "ajaxdatos.php", true);
	XMLHttpRequestObject.send();
}
