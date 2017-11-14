$(document).ready(function() {

	/* --- COMENTED TO TEST THE SERVER VALIDATION ---
	$("#fpreguntas").on("submit", function() {
		
		var emailExp = new RegExp("^[a-zA-Z]+\\d{3}@ikasle\.ehu\.(eus|es)$");
		// Test: Correo123@ikasle.ehu.eus

		var email = $("#email").val();
		var enunciado = $("#enunciado").val();
		var respuestacor = $("#respuestacorrecta").val();
		var respuestaincor = $("#respuestaincorrecta").val();
		var respuestaincor1 = $("#respuestaincorrecta1").val();
		var respuestaincor2 = $("#respuestaincorrecta2").val();
		var com = $("#complejidad").val();
		var tema = $("#tema").val();

		if(email != "" && enunciado != "" && respuestacor != "" && respuestaincor != "" && respuestaincor1 != "" && respuestaincor2 != "" && com != "" && tema != "") {
			if(emailExp.test(email)) {
				if(isNumber(com)) {
					if(parseInt(com) >= 1 && parseInt(com) <= 5) {
						//Envía datos
						return true;
					} else {
						alert("El número de complejidad debe estar entre 1 y 5, ambos inclusive.");
					}
				} else {
					alert("El campo de complejidad debe tener un número.");
				}
			} else {
				alert("La dirección de email introducida no es válida.");
			}
		} else {
			alert("No debe dejar ningún campo vacío.");
		}
		
		return false;

	});
	*/

	function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}

	$("#imagen").on("change", function() {

		if($("#imagen").val()) {

			if ($("#quitarImagen").css("display") == "none") { //If does not show				
				$("#quitarImagen").css("display", "block");
			}

			if ($("#previewImage").css("display") == "none") { //If does not show			
				$("#previewImage").css("display", "block");
			}

			previewImage(this, $("#previewImage"));

		} else {

			$("#quitarImagen").css("display", "none");
			$("#previewImage").css("display", "none");

		}

	});

	$("#quitarImagen").on("click", function() {

		$("#imagen").val("");
		$("#imagen").trigger("change");

	});

	function previewImage(input, imgElement) {

		if (input.files && input.files[0]) {

			var reader = new FileReader();

			reader.onload = function(e) {
				imgElement.attr("src", e.target.result);
			}

			reader.readAsDataURL(input.files[0]);

		}

	}

	// When the user clicks on <span> (x), close the modal
	$(".close").on("click", function(event) { 
		event.stopPropagation();
		$("#modalElement").css("display", "none");
	});

	// When the user clicks away, close the modal
	$("#modalElement").on("click", function(event) {
		$(this).css("display", "none");
	});

	$(".modalImage").on("click", function() {
		$("#modalElement").css("display", "block");
		$("#img01").attr("src", $(this).attr("src"));
		$("#caption").html($(".modalImage").attr("alt"));
	});

	$("#formGestionPreguntas").on("submit", function(event) {
		event.preventDefault();

		var formData = new FormData(this)
		formData.append("action", "uploadQuestion");
		$.ajax({
			url: "ajaxRequestManager.php",
			method: "post",								// Type of request to be send, called as method
			data: formData,								// Data sent to server, a set of key/value pairs (i.e. form fields and values)
			dataType: "json",							// The type of data that you're expecting back from the server.
			contentType: false,							// The content type used when sending data to the server.
			cache: false,								// To unable request pages to be cached
			processData:false,							// To send DOMDocument or non processed data file it is set to false
			success: function(result, status, xhr) {	// A function to be called if request succeeds

				$("#operationResult").html(result.operationMessage);

				if(result.operationSuccess) {
					$("#formGestionPreguntas").remove();
					mostrarDatos('xml/preguntas.xml');
				}
			},
			error: function (xhr, status, error) {
				console.log(xhr.statusText);
				console.log(error);
			}
		});

	});

	refreshStats(20000);
	var timer;

	function refreshStats(refreshRate) {

		$.ajax({
			url: "ajaxRequestManager.php",
			data: {login: getUrlParameter("login"), action: "getQuestionsStats"},
			method: "post",								// Type of request to be send, called as method
			dataType: "json",							// The type of data that you're expecting back from the server.
			success: function(result, status, xhr) {	
				if($('#preguntasUsuarios').text() !== result.quizesUser) {
					refreshElementValue($("#preguntasUsuarios"), result.quizesUser);
				}
				if($('#preguntasTotales').text() !== result.quizesTotal) {
					refreshElementValue($("#preguntasTotales"), result.quizesTotal);
				}
			},
			error: function (xhr, status, error) {
				$("header").append(xhr.responseText);
				clearTimeout(timer);
			}
		});

		timer = setTimeout(function() {
			refreshStats(refreshRate);
		}, refreshRate);

	}

	function refreshElementValue(element, value) {
		if(element.is(':empty')) {
			element.text(value);
		} else {
			element.fadeOut(2000, function(){
				element.text(value);
				$(this).fadeIn(2000);
			});
		}
	}


	function getUrlParameter(param) {

		var pageURL = decodeURIComponent(window.location.search.substring(1));
		var	urlVariables = pageURL.split('&');

		for (var i = 0; i < urlVariables.length; i++) {
			var parameterName = urlVariables[i].split('=');

			if (parameterName[0] === param) {
				return parameterName[1] === undefined ? true : parameterName[1];
			}
		}
	};

	function mostrarDatos(filePath) {

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

		// Bypass the cache.
		// Since the local cache is indexed by URL, this causes every request to be unique.
		filePath += (filePath.match(/\?/) == null ? "?" : "&") + new Date().getTime();
		XMLHttpRequestObject.open("GET", filePath);
		XMLHttpRequestObject.send(null);
	}


});
