	$(document).ready(
		$("#enviar").on("click", function() {

		var emailcomp = new RegExp("/^[a-z]+[0-9]{3}@ikasle.ehu.(eus | es)$/");

		var email = $("#email").val();
		var enunciado = $("#enunciado").val();
		var respuestacor = $("#respuestacorrecta").val();
		var respuestaincor = $("#respuestaincorrecta").val();
		var respuestaincor1 = $("#respuestaincorrecta1").val();
		var respuestaincor2 = $("#respuestaincorrecta2").val();
		var com = $("#complejidad").val();
		var tema = $("#tema").val();
		
	 	if(email != "" && enunciado != "" && respuestacor != "" && respuestaincor != ""
	 		 && respuestaincor1 != "" && respuestaincor2 != "" && com != "" && tema != "") {
			if(emailcomp.test(email)) {
				if(parseInt(com) >= 1 && parseInt(com) <= 5) {
					//Envía datos
				} else {
					alert("El número de complejidad debe situarse entre 1 y 5.");
				}
			} else {
				alert("La dirección de email introducida no es válida.");
			}
	 	} else {
			alert("No debe dejar ningún campo vacío.");
	 	}

			}));
		);