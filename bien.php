<?php
require("./lib/class.phpmailer.php");
include("./sqlserver.php");

//COMPLETAR CON EL MAIL DE CHEQUEO DE VENTAS:
$check = true;
$checkMail = "oscar@codicilabs.com";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM sales WHERE orderId = " . $_GET['external_reference'];
$result = $conn->query($sql);

$file = 'platosdeldia.json';
$data = json_decode(file_get_contents($file));

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $dish_info = $data->platosdeldia[ $row["food_id"] ];
    	
    	$mail = new PHPMailer();

    	
		$mail->SMTPDebug = 4; //Alternative to above constant
		//$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "tls";
		$mail->Host = "HOST MAIL SERVER";
		$mail->Username = "MAIL QUE ENVIA";
		$mail->Password = "CLAVE";
		$mail->Port = 587;
		$mail->IsHTML(true);
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = "base64";
		$mail->From = "MAIL QUE ENVIA"; //info@chefnity.com
		$mail->FromName = "Chefnity";


		// Mail comprador
		$mail->Subject = "Tu compra en Chefnity";
		$mail->AddAddress($row["email"]);
		$body = file_get_contents('./mailing/confirmacion/comprador.html');
		$body = str_replace("!*CANTIDAD*!", $row["amount"], $body);
		$body = str_replace("!*PRODUCTO*!", $row["food"], $body);
		$body = str_replace("!*DIRECCION*!", $row["address"], $body);
		$body = str_replace("!*CHEF*!", $dish_info->cocinero, $body);
		$body = str_replace("!*EMAIL*!", $dish_info->mail, $body);
		$body = str_replace("!*HORARIO*!", $dish_info->horario, $body);
		$mail->Body = $body;
		$exito = $mail->Send();

		$mail->clearAddresses();

		// Mail chef
		$mail->Subject = "Tu venta en Chefnity";
		$mail->AddAddress($dish_info->mail);  //email de vendedor
		$body = file_get_contents('./mailing/confirmacion/chef.html');
		$body = str_replace("!*CANTIDAD*!", $row["amount"], $body);
		$body = str_replace("!*PRODUCTO*!", $row["food"], $body);
		$body = str_replace("!*DIRECCION*!", $row["address"], $body);
		$body = str_replace("!*EMAIL*!", $row["email"], $body);
		$body = str_replace("!*HORARIO*!", $dish_info->horario, $body);
		$mail->Body = $body;
		$exitoo = $mail->Send();

		// Mail check
		if ($check) {
			$mail->clearAddresses();
			$mail->Subject = "Venta en Chefnity";
			$mail->AddAddress($checkMail);  //email de checks
			$body = file_get_contents('./mailing/confirmacion/check.html');
			$body = str_replace("!*CANTIDAD*!", $row["amount"], $body);
			$body = str_replace("!*PRODUCTO*!", $row["food"], $body);
			$body = str_replace("!*DIRECCION*!", $row["address"], $body);
			$body = str_replace("!*EMAILCLI*!", $row["email"], $body);
			$body = str_replace("!*EMAILCHEF*!", $dish_info->mail, $body);
			$body = str_replace("!*HORARIO*!", $dish_info->horario, $body);
			$body = str_replace("!*CHEF*!", $dish_info->cocinero, $body);
			$body = str_replace("!*IDVENTA*!", $row["orderId"], $body);
			$mail->Body = $body;
			$mail->Send();
		}
		
		//También podríamos agregar simples verificaciones para saber si se envió:
		if($exito && $exitoo){
			//echo "El correo fue enviado correctamente.";
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			} 

			$sql = "UPDATE sales SET payed = 1 WHERE orderId = " . $_GET['external_reference'];
			$result = $conn->query($sql);



			header("Location: https://www.chefnity.com/#success", true, 301);
			exit();
		}else{
			echo "Hubo un inconveniente con el envío de mail. Contacta a un administrador.";
		}



    }
} else {
    echo "Hubo un inconveniente con el nro de referencia externo. Contacta a un administrador.";
}
$conn->close();







?>