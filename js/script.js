$(document).ready(function() {

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

	function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}

<<<<<<< HEAD
	$("#imagen").on("change", function(){
=======
	$("#imagen").on("change", function() {
>>>>>>> origin/FerMod

		if($("#imagen").val()) {

			// Create element if does not exist
			if (!$("#quitarImagen").length) {
				
				$buttonElement = "<input type='button' id='quitarImagen' value='Quitar Imagen' style='width: auto; display: block; margin-left: 5%; margin-right: auto;'/>";
				
				$(this).after($buttonElement);

				$("#quitarImagen").on("click", function() {

					$("#imagen").val('');

					$("#previewImage").remove();

					$("#quitarImagen").remove();

				});

			}

			if (!$("#previewImage").length) { //If does not exist
				
				//Create the image preview
				$imageElement =  "<img id='previewImage' src='#' style='width: 20%; height: auto; object-fit: contain; display: block; margin-left: 5%; margin-right: auto;'/>";
				
				$(this).after($imageElement);

			}

			previewImage(this, $("#previewImage"));

		} else {

			if ($("#quitarImagen").length) {
				$("#quitarImagen").remove();
			}

			if ($("#previewImage").length) {
				$("#previewImage").remove();
			}

		}

	});

	function previewImage(input, imgElement) {

		if (input.files && input.files[0]) {

			var reader = new FileReader();

			reader.onload = function(e) {
				imgElement.attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);

		} else if (imgElement.length) {
			imgElement.remove();
		}

<<<<<<< HEAD
	}

	
=======
	}	
>>>>>>> origin/FerMod
	
});
