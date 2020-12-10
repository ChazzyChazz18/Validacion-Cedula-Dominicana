<?php

class Module_10_API {

	function verifyPersonalID () {

		$person = array();
		$completePersonalID;
		$isValid = false;

		//Obtenemos el parametro pid desde el URL
		$personalID = $_GET['pid'];

		//Si el parametro pid solo tiene 10 digitos obtenemos el digito verificador y mostramos la cedula completa
		if (strlen($personalID) == 10){
			$completePersonalID = $personalID . "" . $this -> getLastDigitID($personalID);
		}
		//Si tiene 11 verificamos que el ultimo digito del parametro sea igual al digito verificador para saber si es valido
		else if (strlen($personalID) == 11){
			$completePersonalID = $personalID;
			$lastDigit = substr($personalID, -1);
			$first_10_personalID = substr($personalID, 0, -1); 
			$isValid = ($lastDigit == $this -> getLastDigitID($first_10_personalID)) ? true : false;
		}

		//Creamos un "objeto/arreglo" con sus respectivos atributos
		$person[0] = array(
			'id' => 0,
			'personalID' => $completePersonalID,
			'valid' => $isValid
		);

		//Retornamos el objeto persona parseado en formato JSON
		return json_encode($person);

	}

	function getLastDigitID ($id) {

		$cedulaArray = array();// A
		$evenOddArray = array();// B
		$cedula_x_evenOddArray = array();// T
		$finalArray = array();// H
		$sumatoriaDeH = 0;
		$digitoVerificador = 0;

		$idLength = strlen($id);
		for ($i = 0; $i < $idLength; $i++) {

		    //Obtenemos cada numero y lo ingresamos a un arreglo --- array(A)
			$cedulaArray[$i] = $id[$i];

			//Obtenemos el arreglo de pares e impares en base a la posicion --- array(B)
			if($i % 2 == 0) $evenOddArray[$i] = 1; else $evenOddArray[$i] = 2;

			//Obtenemos el arreglo resultado de la multiplicacion de los arreglos anteriores --- array (T)
			$cedula_x_evenOddArray[$i] = "" . $cedulaArray[$i] * $evenOddArray[$i];

			//Obtenemos el arreglo final que nos servira para encontrar el digito verificador --- array (H)
			if($cedula_x_evenOddArray[$i] < 10)
				$finalArray[$i] = $cedula_x_evenOddArray[$i];//si el digito es menor que 10 lo pasamos igual
			else
				$finalArray[$i] = $this -> justOneDigit($cedula_x_evenOddArray[$i]);//si es mayor que 10 sumamos sus digitos, ejemplo 12 = 1 + 2 = 3

			//Obtenemos la sumatoria de todos los numeros en el arreglo H
			$sumatoriaDeH += $finalArray[$i];

		}

		//Obtenemos el digito verificador realizando la siguiente operacion:
		// digitos ingresados - Modulo (sumatoria H, digitos ingresados) == 10 - MOD(H,10)
		$digitoVerificador = $idLength - ($sumatoriaDeH % $idLength);

		return $digitoVerificador;

	}

	//Esta funcion permite sumar los digitos de un numero Ejemplo: 115 -> 1 + 1 + 5 = 7
	//y (en caso de que el resultado sea mas de 1 digito) recursivamente se repite hasta tener 1 solo digito...
	function justOneDigit($digit) {
		$digit = str_split($digit);
		$sum = 0;

		for($i = 0; $i<count($digit); $i++) { $sum += $digit[$i]; }

		if(count(str_split($sum))<2) return  $sum; else justOneDigit($sum);
	}

}

//Inicializamos el objeto API
$API = new Module_10_API;

//Indicamos que el contenido de la pagina es tipo JSON
header('Content-Type: application/json');

//Llamamos el metodo verifyPersonalID desde el objeto $API
echo $API -> verifyPersonalID();

?>