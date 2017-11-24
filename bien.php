<?php
require("./lib/class.phpmailer.php");
include("./sqlserver.php");


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM sales WHERE orderId = " . $_GET['external_reference'];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        //echo "id: " . $row["id"]. " - Email: " . $row["email"]. " - Amount: " . $row["amount"]. " - idFood: " . $row["idFood"]. "<br>";


		$mail = new PHPMailer();

		//Luego tenemos que iniciar la validación por SMTP:
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host = "smtp.zoho.com"; // SMTP a utilizar. Por ej. smtp.elserver.com
		$mail->Username = "test@codicilabs.com"; // Correo completo a utilizar
		$mail->Password = "clavemail"; // Contraseña
		$mail->Port = 465; // Puerto a utilizar

		//Con estas pocas líneas iniciamos una conexión con el SMTP. Lo que ahora deberíamos hacer, es configurar el mensaje a enviar, el //From, etc.
		$mail->From = "test@codicilabs.com"; // Desde donde enviamos (Para mostrar)
		$mail->FromName = "Chefnity Test Codicilabs";

		//Estas dos líneas, cumplirían la función de encabezado (En mail() usado de esta forma: “From: Nombre <correo@dominio.com>”) de //correo.
		$mail->AddAddress($row["email"]); // Esta es la dirección a donde enviamos
		$mail->IsHTML(true); // El correo se envía como HTML
		$mail->Subject = "Tu compra en Chefnity"; // Este es el titulo del email.
		$body = "Hola. Esto es un recibo de tu compra en chefnity.<br />";
		$body .= "Tu nro de orden es: " . $row["id"] . "<br />";
		$body .= "Compraste <strong>" . $row["amount"] . "</strong> del plato: <strong>" . $row["food"] . "</strong><br />";
		$body .= "Pagaste <strong>$" . $row["price"] . "</strong> por cada plato. Un total de <strong>$" . ($row["price"]*$row["amount"]) . "</strong><br />";
		$body .= "Tenes que ir a retirarlo a <strong>" . $row["address"] . "</strong>.";
		$mail->Body = $body; // Mensaje a enviar
		$exito = $mail->Send(); // Envía el correo.

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