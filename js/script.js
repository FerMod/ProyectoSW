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
				imgElement.attr("alt", "Imagen de la pregunta");
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
	$("#modalElement").on("click",function(event) {
		$(this).css("display", "none");
	});

	$("#previewImage").on("click", function() {
		$("#modalElement").css("display", "block");
		$("#img01").attr("src", $(this).attr("src"));
		$("#caption").html($(this).attr("alt"));
	});

});
