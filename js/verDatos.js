
//(IE7+, Firefox, Chrome, Safari, and Opera)
XMLHttpRequestObject = new XMLHttpRequest();
XMLHttpRequestObject.onreadystatechange = function() {
	if (XMLHttpRequestObject.readyState==4) {
		document.getElementById('visualizarDatos').innerHTML = '';
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
}
