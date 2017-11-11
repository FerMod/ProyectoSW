document.addEventListener("DOMContentLoaded", function(event) {

	//(IE7+, Firefox, Chrome, Safari, and Opera)
	XMLHttpRequestObject = new XMLHttpRequest();
	XMLHttpRequestObject.onreadystatechange = function() {
		alert (XMLHttpRequestObject.readyState);
		if (XMLHttpRequestObject.readyState==4) {
			var obj = document.getElementById('visualizarDatos');
			obj.innerHTML = XMLHttpRequestObject.responseText;
		}
	}

	function mostrarDatos(filePath) {
		XMLHttpRequestObject.open("GET", filePath);
		XMLHttpRequestObject.send(null);
	}

});