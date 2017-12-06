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
	"use strict";
	$("#registro").on("submit", function(event) {
		refreshSessionTimeout();

		if(!$("#email").get(0).checkValidity() || !$("#password").get(0).checkValidity()) {
			return false;
		}

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
		refreshSessionTimeout();

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
		refreshSessionTimeout();

		$("#imagen").val("");
		$("#imagen").trigger("change");

	});

	function previewImage(input, imgElement) {

		if (input.files && input.files[0]) {

			var reader = new FileReader();

			reader.onload = function(e) {
				imgElement.attr("src", e.target.result);
			};

			reader.readAsDataURL(input.files[0]);

		}

	}

	// When the user clicks on <span> (x), close the modal
	$(".close").on("click", function(event) { 
		event.stopPropagation();
		refreshSessionTimeout();
		$("#modalElement").css("display", "none");
	});

	// When the user clicks away, close the modal
	$("#modalElement").on("click", function(event) {
		$(this).css("display", "none");
		refreshSessionTimeout();
	});

	$(".modalImage").on("click", function() {
		refreshSessionTimeout();
		$("#modalElement").css("display", "block");
		$("#img01").attr("src", $(this).attr("src"));
		$("#caption").html($(".modalImage").attr("alt"));
	});

	$("#formGestionPreguntas").on("submit", function(event) {
		event.preventDefault();
		refreshSessionTimeout();

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
		refreshSessionTimeout();

		var formData = new FormData(this);
		formData.append("action", "editQuestion");

		$.ajax({
			url: "ajaxRequestManager.php",
			method: "post",
			data: formData,
			dataType: "json",
			contentType: false,
			cache: false,
			processData: false,
			success: function(result, status, xhr) {

				$("#respuesta").html(result.operationMessage);

				if(result.operationSuccess) {
					actualizarPregunta(result.question.id, result.question.email, result.question.enunciado, result.question.respuestaCorrecta, result.question.respuestaIncorrecta1, result.question.respuestaIncorrecta2, result.question.respuestaIncorrecta3, result.question.complejidad, result.question.tema);
				}
				
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
		var urlVariables = pageURL.split('&');

		for (var i = 0; i < urlVariables.length; i++) {
			var parameterName = urlVariables[i].split('=');

			if (parameterName[0] === param) {
				return parameterName[1] === undefined ? true : parameterName[1];
			}
		}
	}

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
		};

		// Bypass the cache.
		// Since the local cache is indexed by URL, this causes every request to be unique.
		filePath += (filePath.match(/\?/) === null ? "?" : "&") + new Date().getTime();
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
					$("#password").get(0).validity = result.isValid;
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
	
	// Is worse and more insecure to dont allow to paste password.
	// Allowing the copy and paste, allows to password managers to paste passwords.
	// // Dont allow any context menu and the cut, copy and paste actions in the password field
	// $("input[type=password]").on("contextmenu cut copy paste", function(event) {
	// 	event.preventDefault();
	// 	refreshSessionTimeout();
	// });

	var count = 1;
	$(".sidebar").find("span").on("click", function(event) {
		console.log(count);
		if(count++ == 3) {
			window.location.replace("https://goo.gl/NHLufA");
		}
	}).css("cursor", "pointer");


	$("[data-scroll-to]").click(function() {
		var $this = $(this),
		$toElement      = $this.attr('data-scroll-to'),
		$focusElement   = $this.attr('data-scroll-focus'),
		$offset         = $this.attr('data-scroll-offset') * 1 || 0,
		$speed          = $this.attr('data-scroll-speed') * 1 || 500;

		$('html, body').animate({
			scrollTop: $($toElement).offset().top + $offset
		}, $speed);

		if ($focusElement) $($focusElement).focus();
	});

});

function getQuestions(callbackFunciton) {
	"use strict";
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
	"use strict";
	if(result.operationSuccess) {
		$.each(result.query, function (key, value) {

			var $questionDivElement = $('<button id="' + key + '" onclick="editarPregunta(' +  key + ')"></button>').addClass("pregunta");
			$questionDivElement.append('<strong>Id pregunta: </strong><span id="id">' + key + '</span><br/>');
			$questionDivElement.append('<strong>Complejidad: </strong><span id="complejidad">' + value.complejidad + '</span><strong> | Tema: </strong><span id="tema">' + value.tema + '</span><strong> | Autor: </strong><span id="email">' + value.email + '</span></br>');
			$questionDivElement.append('<strong>Enunciado: </strong><span id="enunciado">' + value.enunciado + '</span><br/>');

			var $listElement = $('<ul></ul>').addClass("answerList");
			$listElement.append('<li id="respuestaCorrecta" class="tick">' + value.respuesta_correcta + '</li>');
			$listElement.append('<li id="respuestaIncorrecta1" class="cross">' + value.respuesta_incorrecta_1 + '</li>');
			$listElement.append('<li id="respuestaIncorrecta2" class="cross">' + value.respuesta_incorrecta_2 + '</li>');
			$listElement.append('<li id="respuestaIncorrecta3" class="cross">' + value.respuesta_incorrecta_3 + '</li>');

			$questionDivElement.append($listElement);
			$("#listaPreguntas").append($questionDivElement);

		});

		$(".loading").fadeOut("slow").hide("slow", function() {
			$("#listaPreguntas").fadeIn("slow").show("slow");
		});
	} else if(result.operationMessage) {
		$("#listaPreguntas").html(result.operationMessage);
	}

}

function actualizarPregunta(id, email, enunciado, respuestaCorrecta, respuestaIncorrecta1, respuestaIncorrecta2, respuestaIncorrecta3, complejidad, tema) {
	"use strict";
	var $idElement = $("#" + id).find("#id");
	var $emailElement = $("#" + id).find("#email");
	var $enunciadoElement = $("#" + id).find("#enunciado");
	var $respuestaCorrectaElement = $("#" + id).find("#respuestaCorrecta");
	var $respuestaIncorrecta1Element = $("#" + id).find("#respuestaIncorrecta1");
	var $respuestaIncorrecta2Element = $("#" + id).find("#respuestaIncorrecta2");
	var $respuestaIncorrecta3Element = $("#" + id).find("#respuestaIncorrecta3");
	var $complejidadElement =$("#" + id).find("#complejidad");
	var $temaElement = $("#" + id).find("#tema");

	if($idElement.text() != id) {
		$idElement.text(id);
		highlight($idElement);
	}

	if($emailElement.text() != email) {
		$emailElement.text(email);
		highlight($emailElement);
	}

	if($enunciadoElement.text() != enunciado) {
		$enunciadoElement.text(enunciado);
		highlight($enunciadoElement);
	}

	if($respuestaCorrectaElement.text() != respuestaCorrecta) {
		$respuestaCorrectaElement.text(respuestaCorrecta);
		highlight($respuestaCorrectaElement);
	}

	if($respuestaIncorrecta1Element.text() != respuestaIncorrecta1) {
		$respuestaIncorrecta1Element.text(respuestaIncorrecta1);
		highlight($respuestaIncorrecta1Element);
	}

	if($respuestaIncorrecta2Element.text() != respuestaIncorrecta2) {
		$respuestaIncorrecta2Element.text(respuestaIncorrecta2);
		highlight($respuestaIncorrecta2Element);
	}

	if($respuestaIncorrecta3Element.text() != respuestaIncorrecta3) {
		$respuestaIncorrecta3Element.text(respuestaIncorrecta3);
		highlight($respuestaIncorrecta3Element);
	}

	if($complejidadElement.text() != complejidad) {
		$complejidadElement.text(complejidad);
		highlight($complejidadElement);
	}

	if($temaElement.text() != tema) {
		$temaElement.text(tema);
		highlight($temaElement);
	}

	scrollTo($("#listaPreguntas"), $("#" + id));

}

function editarPregunta(id) {
	"use strict";
	var email = $("#" + id).find("#email").text();
	var enunciado = $("#" + id).find("#enunciado").text();
	var respuestaCorrecta = $("#" + id).find("#respuestaCorrecta").text();
	var respuestaIncorrecta1 = $("#" + id).find("#respuestaIncorrecta1").text();
	var respuestaIncorrecta2 = $("#" + id).find("#respuestaIncorrecta2").text();
	var respuestaIncorrecta3 = $("#" + id).find("#respuestaIncorrecta3").text();
	var complejidad = $("#" + id).find("#complejidad").text();
	var tema = $("#" + id).find("#tema").text();

	$("#id-edit").val(id);
	$("#email-edit").val(email);
	$("#enunciado-edit").val(enunciado);
	$("#respuestaCorrecta-edit").val(respuestaCorrecta);
	$("#respuestaIncorrecta1-edit").val(respuestaIncorrecta1);
	$("#respuestaIncorrecta2-edit").val(respuestaIncorrecta2);
	$("#respuestaIncorrecta3-edit").val(respuestaIncorrecta3);
	$("#complejidad-edit").val(complejidad);
	$("#tema-edit").val(tema);
}

function refreshSessionTimeout() {
	"use strict";
	$.ajax({
		url: "ajaxRequestManager.php",
		method: "post"
	});
}

$(document).ajaxSuccess(function(event, request, settings, data) {
	"use strict";
	if($.trim(data.sessionTimeout)) {
		if(data.sessionTimeout) {
			redirecTo("layout.php");
		}
	}
});

function redirecTo(url) {
	"use strict";
	if(url.length) {
		window.location.replace(url);
	}
}

function highlight($element) {
	"use strict";
	$element.addClass("highlight");
	$element.delay(2200).queue(function() { // Wait the defined seconds
		$(this).removeClass("highlight").dequeue();
	});	
}

function scrollTo($container, $element) {
	"use strict";
	$container.animate({
		scrollTop: $element.offset().top - $container.offset().top + $container.scrollTop()
	});
}
