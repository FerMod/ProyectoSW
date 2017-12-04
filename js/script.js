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

	$("#registro").on("submit", function(event) {

		if(!$("#email").get(0).checkValidity() || !$("#password").get(0).checkValidity()) {
			return false;
		}

		refreshSessionTimeout();
		return true;

	});

	function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}

	$("form").on("reset", function(event) {
		var $inputElement =  $("input:not([type=button], [type=reset], [type=submit])");
		$inputElement.css("-webkit-box-shadow", "initial");
		$inputElement.css("-moz-box-shadow", "initial");
		$inputElement.css("box-shadow", "initial");
		$inputElement.css("border-color", "initial");
		$(".operationResult").remove();
	});

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

		refreshSessionTimeout();

	});

	$("#quitarImagen").on("click", function() {

		$("#imagen").val("");
		$("#imagen").trigger("change");

		refreshSessionTimeout();

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
		refreshSessionTimeout();
	});

	// When the user clicks away, close the modal
	$("#modalElement").on("click", function(event) {
		$(this).css("display", "none");
		refreshSessionTimeout();
	});

	$(".modalImage").on("click", function() {
		$("#modalElement").css("display", "block");
		$("#img01").attr("src", $(this).attr("src"));
		$("#caption").html($(".modalImage").attr("alt"));
		refreshSessionTimeout();
	});

	$("#formGestionPreguntas").on("submit", function(event) {
		event.preventDefault();

		var formData = new FormData(this);
		formData.append("action", "uploadQuestion");
		$.ajax({
			url: "ajaxRequestManager.php",
			method: "post",								// Type of request to be send, called as method
			data: formData,								// Data sent to server, a set of key/value pairs (i.e. form fields and values)
			dataType: "json",							// The type of data that you're expecting back from the server.
			contentType: false,							// The content type used when sending data to the server.
			cache: false,								// To unable request pages to be cached
			processData: false,							// To send DOMDocument or non processed data file it is set to false
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
	
	$("#formRevPreguntas").on("submit", function(event) {
		event.preventDefault();

		var formDataR = new FormData(this);
		formDataR.append("action", "editQuestion");

		$.ajax({
			url: "ajaxRequestManager.php",
			method: "post",								// Type of request to be send, called as method
			data: formDataR,								// Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,							// The content type used when sending data to the server.
			cache: false,								// To unable request pages to be cached
			dataType: "json",
			processData: false,							// To send DOMDocument or non processed data file it is set to false
			success: function(result, status, xhr) {	// A function to be called if request succeeds
				console.log(result);
				$("#respuesta").html(result.operationMessage);
			},
			error: function (xhr, status, error) {
				console.log(xhr.statusText);
				console.log(error);
			}
		});
	});

	if($("#preguntasUsuarios").length && $("#preguntasTotales").length) {
		refreshStats(); // Execute the function
		var timer = setInterval(refreshStats, 20000);
	}

	function refreshStats() {

		$.ajax({
			url: "ajaxRequestManager.php",
			data: {action: "getQuestionsStats"},
			method: "post",								// Type of request to be send, called as method
			dataType: "json",							// The type of data that you're expecting back from the server.
			success: function(result, status, xhr) {	
				refreshElementValue($("#preguntasUsuarios"), result.quizesUser);
				refreshElementValue($("#preguntasTotales"), result.quizesTotal);
			},
			error: function (xhr, status, error) {				
				clearInterval(timer);
				console.log(xhr.responseText);
			}
		});

	}

	function refreshElementValue(element, value) {
		if(element.is(':empty')) {
			element.text(value);
		} else if(parseInt(element.text(), 10) !== value) {
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
	
	$("#email").on("keyup", function(event) {
		
		if (!$("#email").val()) {
			$("#email").get(0).validity = false;
			$("#email").removeClass("validData").removeClass("invalidData");
		} else {
			$.ajax({
				url: "ajaxRequestManager.php",
				data: {"email": $("#email").val().trim(), action: "isVIPUser"},
				method: "post",
				dataType: "json",
				success: function(result, status, xhr) {
					$("#email").get(0).validity = result.isVip;
					if(result.isVip) {
						$("#email").removeClass("invalidData").addClass("validData");
					} else {
						$("#email").removeClass("validData").addClass("invalidData");
					}
				},
				error: function (xhr, status, error) {
					console.log(xhr.responseText);
				}
			});
		}

	});
	
	$("#password").on("keyup", function(event) {

		// Prevent multiple event trigger, use the first that triggers
		event.preventDefault();

		$.ajax({
			url: "ajaxRequestManager.php",
			data: {"password": $("#password").val(), action: "checkPassword"},
			method: "post",
			dataType: "json",
			success: function(result, status, xhr) {
				
				var strength = -1;

				if ($("#password").val()) {				
					$("#password").get(0).validity =true// result.isValid;
					if(result.isValid) {
						$("#password").addClass("validData");
						strength = getPasswordStrength($("#password").val());
					} else {
						strength = 0;
						$("#password").removeClass();
						$("#password").get(0).setCustomValidity("The password is very weak");
					}
				} else {					
					$("#password").get(0).validity = false;
					$("#password").get(0).setCustomValidity("The password field cannot be empty");
				}

				switch (strength) {

					case 0: // veryWeak
					$("#password").addClass("veryWeak");
					break; 

					case 1: // weak
					$("#password").addClass("weak");
					break;

					case 2: // medium
					$("#password").addClass("medium");
					break;

					case 3: // strong
					$("#password").addClass("strong");
					break;

					default: // password undefined (no password entered)
					$("#password").removeClass();
					break;

				}
				console.log("Validity: " + $("#password").get(0).validity);
				console.log("Password strength: " + strength);
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText);
			}
		});

	});

	function getPasswordStrength(password) {

		var strength = 0;

		if (password.length < 6) {
			return strength;
		}

		if(password.length >= 7) {
			strength++;
		}

		// If password contains both lower and uppercase characters, increase strength value.
		if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
			strength++;
		}

		// If it has numbers and characters, increase strength value.
		if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
			strength++;
		}

		// If it has one special character, increase strength value.
		if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
			strength++;
		}

		// If it has two special characters, increase strength value.
		if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) {
			strength++;
		}

		return strength;

	}
	
	// Dont allow any context menu and the cut, copy and paste actions in the password field
	$("input[type=password]").on("contextmenu cut copy paste", function(event) {
		event.preventDefault();
	});

	var count = 1;
	$(".sidebar").find("span").on("click", function(event) {
		console.log(count);
		if(count++ == 3) {
			window.location.replace("https://goo.gl/NHLufA");
		}
	}).css("cursor", "pointer");

	function editarPregunta(id) {
		var datos = $("#"+id+"comp").text().split(" | ");

		var id = $("#"+id).text().split("Id pregunta: ")[1];

		var comp = datos[0].split("Complejidad: ")[1];
		var tema = datos[1].split("Tema: ")[1];
		var email = datos[2].split("Autor: ")[1];
		var enun = $("#"+id+"preg").text().split("Enunciado: ")[1];
		var rcor = $("#"+id+"cor").text().split("+Respuesta correcta: ")[1];
		var rincor1 = $("#"+id+"incor1").text().split("-Respuesta incorrecta 1: ")[1];
		var rincor2 = $("#"+id+"incor2").text().split("-Respuesta incorrecta 2: ")[1];
		var rincor3 = $("#"+id+"incor3").text().split("-Respuesta incorrecta 3: ")[1];

		$("#ided").val(id);
		$("#emailed").val(email);
		$("#enunciadoed").val(enun);
		$("#respuestacorrectaed").val(rcor);
		$("#respuestaincorrecta1ed").val(rincor1);
		$("#respuestaincorrecta2ed").val(rincor2);
		$("#respuestaincorrecta3ed").val(rincor3);
		$("#complejidaded").val(comp);
		$("#temaed").val(tema);
	}

});

function getQuestions(callbackFunciton) {

	$.ajax({
		url: "ajaxRequestManager.php",
		data: {action: "getQuestions"},
		method: "post",
		dataType: "json",
		success: callbackFunciton,
		error: function (xhr, status, error) {
		}
	});

}

function createQuestionList(result, status, xhr) {
	//console.table(result.query);
	$.each(result.query, function (key, value) {

		var $questionDivElement = $('<div id="' + key + '" onclick="editarPregunta(' +  key + ')"></div>').addClass("pregunta");
		$questionDivElement.append('<div id="id">Id pregunta: ' + key + '</div>');
		$questionDivElement.append('<div id="complejidad">Complejidad: ' + value['complejidad'] + ' | Tema: ' + value['tema'] + ' | Autor: ' + value['email'] + '</div>');
		$questionDivElement.append('<div id="enunciado">Enunciado: ' + value['enunciado'] + '</div>');
		
		var $listElement = $('<ul></ul>').addClass("answerList");
		$listElement.append('<li id="respuestaCorrecta" class="tick">' + value['respuesta_correcta'] + '</li>');
		$listElement.append('<li id="respuestaIncorrecta1" class="cross">' + value['respuesta_incorrecta_1'] + '</li>');
		$listElement.append('<li id="respuestaIncorrecta2" class="cross">' + value['respuesta_incorrecta_2'] + '</li>');
		$listElement.append('<li id="respuestaIncorrecta3" class="cross">' + value['respuesta_incorrecta_3'] + '</li>');

		$questionDivElement.append($listElement);
		$("#preguntas").append($questionDivElement);

	});
}

function refreshSessionTimeout() {
	$.ajax({
		url: "ajaxRequestManager.php",
		method: "post"
	});
}

$(document).ajaxSuccess(function(event, request, settings, data) {
	if($.trim(data.sessionTimeout)) {
		if(data.sessionTimeout) {
			redirecTo("layout.php");
		}
	}
});

function redirecTo(url) {
	if(url.length) {
		window.location.replace(url);
	}
}

