<?php
const MAX_FILE_SIZE = 2097152; //2MB  (1024x1024x2) en bytes

foreach ($_POST as $clave => $valor) {

	echo "<strong>$clave</strong>:";

	if (!is_array($valor)) {

		echo " $valor";
	} else {

		echo var_dump($valor);
	}

	echo "<br/>";
}

/*
  Si se incluyen <input type="file"> hay que añadir el atributo    enctype="multipart/form-data" a form.
  El fichero/los ficheros se recibirán en el lado servidor en la variable global $_FILES
 */

 //Tamén podería amosarse o contido de $_FILES con calquera destas dúas opcións:
var_dump($_FILES);
echo "<pre>";
print_r($_FILES);
echo "</pre>";

foreach ($_FILES as $input => $infoArr) { //$input será el valor de name en el marcado HTML (sin corchetes)
	if (is_array($infoArr["name"])) { //Si se envía un array de ficheros con el valor de name  en <input type="file"> terminado en []

		foreach ($infoArr["size"] as $i => $value) {

			if ($value > MAX_FILE_SIZE) {
				echo "<p> O tamaño do arquivo supera o valor máximo admitido: " . MAX_FILE_SIZE . "(bytes)</p>";
			} else {
				echo "<strong>File zize " . ++$i . " </strong>:";

				echo $value . "<br>";
			}
		}


		//Se recibe un array asociativo con claves "name", "type", "tmp_name", "error" y "size" y por cada clave, un array de índices numéricos con los valores de cada fichero por cada clave
		foreach ($infoArr["name"] as $i => $value) {

			echo "<strong>File name " . ++$i . " </strong>:";

			echo $value . "<br>";
		}

		foreach ($infoArr["type"] as $i => $value) {

			echo "<strong>File type " . ++$i . " </strong>:";

			echo $value . "<br>";
		}

		foreach ($infoArr["tmp_name"] as $i => $value) {

			echo "<strong>File tmp_name " . ++$i . " </strong>:";

			echo $value . "<br>";
		}
		foreach ($infoArr["error"] as $i => $value) {

			if (($value == UPLOAD_ERR_INI_SIZE) || ($value == UPLOAD_ERR_FORM_SIZE)) {
				echo "<p> O tamaño do arquivo supera o valor máximo admitido: " . MAX_FILE_SIZE . "(bytes)</p>";
			} else {

				echo "<strong>File error " . ++$i . " </strong>:";

				echo $value . "<br>";
			}
			//Se todo vai
			if($value == UPLOAD_ERR_OK){
				$exito = move_uploaded_file($infoArr["tmp_name"][$i-1], 
				"..".DIRECTORY_SEPARATOR."..". DIRECTORY_SEPARATOR."destino".DIRECTORY_SEPARATOR.$infoArr["name"][$i-1]);
				if($exito){
					echo "<p> O arquivo moveuse con éxito</p>";
				}
				else{
					echo "<p> O arquivo non se moveu con éxito</p>";
				}
			}
		}


		foreach ($infoArr["full_path"] as $i => $value) {

			echo "<strong>File fullpath " . ++$i . " </strong>:";

			echo $value . "<br>";
		}
	} else { //Si se envía un único fichero (El valor del atributo name en <input type="file"> no termina con [])
		echo "<strong>File name</strong>: ";

		echo $infoArr["name"] . "<br>";
	}
}


/*
Estructura del array recibido en el servidor, en caso de múltiples ficheros:

Array(["ficheros"] =>   Array (
							["name"] => Array(
											[0] => nombre_fichero_0.ext
											[1] => nombre_fichero_1.ext	
											...
											),
							["type"] => Array(
											[0] => tipo_fichero_0
											[1] => tipo_fichero_1
											....
											),
							["tmp_name"] => Array(
											[0] => C:\xampp\tmp\algo_0.tmp
											[1] => C:\xampp\tmp\algo_1.tmp
											....
											), 
							["error"] => Array(
											[0] =>Código de error fichero_0 https://www.php.net/manual/en/features.file-upload.errors.php
											[1] =>Código de error fichero_1
											....
											), 
							["size"] => Array(
											[0] => tamaño en bytes
											[1] => tamaño en bytes
											....
                                            ),
                                            // a partir de PHP 8.1.0
                            ["full_path"] => Array(
											[0] => ruta enviada por el navegador del fichero 0
											[1] => ruta enviada por el navegador del fichero 1
											....
											)                

							)
	)

*/