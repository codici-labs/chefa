<?php
require("./lib/class.phpmailer.php");
include("./sqlserver.php");

$_GET['external_reference'] = 15135378961828;

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

		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host = "smtp.zoho.com";
		$mail->Username = "test@codicilabs.com";
		$mail->Password = "c0dicilabs2017";
		$mail->Port = 465;
		$mail->IsHTML(true);
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = "base64";
		$mail->From = "test@codicilabs.com"; //info@chefnity.com
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
		$mail->AddAddress($dish_info->mail);
		$body = file_get_contents('./mailing/confirmacion/chef.html');
		$body = str_replace("!*CANTIDAD*!", $row["amount"], $body);
		$body = str_replace("!*PRODUCTO*!", $row["food"], $body);
		$body = str_replace("!*DIRECCION*!", $row["address"], $body);
		$body = str_replace("!*EMAIL*!", $row["EMAIL"], $body);
		$body = str_replace("!*HORARIO*!", $dish_info->horario, $body);
		$exito = $mail->Send();

		
		//También podríamos agregar simples verificaciones para saber si se envió:
		if($exito){
			echo "El correo fue enviado correctamente.";
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			} 

			$sql = "UPDATE sales SET payed = 1 WHERE orderId = " . $_GET['external_reference'];
			$result = $conn->query($sql);



			header("Location: http://www.codicilabs.com/trabajos/chefnity/#success", true, 301);
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