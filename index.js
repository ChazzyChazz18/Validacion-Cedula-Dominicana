function algoritmoBase10 () {

	var cedulaInputValue = document.getElementById("cedulaInput").value;
	var resultOutput = document.getElementById("resultado");
	var resultContainer = document.getElementById("resultContainer");

	const Url = "modulo_10.php?pid=" + cedulaInputValue;

	resultContainer.style.display = "block";

	if(cedulaInputValue.length > 9){

		$.ajax({
			url: Url,
			type: "GET",
			success: function (result) {

				var cedulaLegible = "";

				for(var i = 0; i < result[0].personalID.length; i++){

					if(i != result[0].personalID.length - 1)
						cedulaLegible += result[0].personalID[i];
					else
						cedulaLegible += changeTextColor(result[0].personalID[i], "red");

					if(i == 2 || i == 9) cedulaLegible += "-";

				}

				if(cedulaInputValue.length == 10){
					resultOutput.innerHTML = cedulaLegible + "<br>" + "El numero identificador es: " + 
					result[0].personalID[result[0].personalID.length - 1];
				}else{
					var isValid = result[0].valid;
					var validStr = (isValid) ? changeTextColor("es valida.", "green") : changeTextColor("no es valida.", "red");
					resultOutput.innerHTML = cedulaLegible + "<br>" + "Esta cedula " + validStr;
				}
			},
			error: function (error) {
				console.log(error);
				resultOutput.innerHTML = error.responseText;
			}
		});

	}else{
		resultOutput.innerHTML = changeTextColor("Necesita ingresar su cedula completa o solo los 10 primeros digitos.", "red");
	}

}

function changeTextColor (str, color) {
	return "<span style='color:"+color+"'>" + str + "</span>";
}