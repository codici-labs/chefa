<?php
require("./lib/class.phpmailer.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
// $mail->SMTPSecure = "ssl";
$mail->Host = "localhost"; //"smtp.zoho.com"; 
// $mail->Username = "test@codicilabs.com"; 
// $mail->Password = "c0dicilabs2017"; 
$mail->Port = 25; //465; 
$mail->IsHTML(true); 
$mail->CharSet = 'UTF-8';
$mail->Encoding = "base64";
$mail->FromName = "Chefnity";


// //replace with your email-sotomayorsebastian4@gmail.com

switch ($_POST['form']) {
	case 'registration':
		try {
		// Nuevo usuario registrado
		$mail->Body = build_mail(file_get_contents('./mailing/registro/chefnity.html'));
		$mail->From = "test@codicilabs.com"; //info@chefnity.com
		$mail->addAddress("test@codicilabs.com"); //info@chefnity.com 
		$mail->Subject = "Nuevo usuario registrado";
		$mail->send();

		$mail->clearAddresses();

		// Mail de registro al usuario
		$mail->Body = build_mail(file_get_contents('./mailing/registro/user.html'));
		$mail->From = "test@codicilabs.com"; //info@chefnity.com
		$mail->addAddress($_POST['email']); 
		$mail->Subject = "¡Bienvenido a Chefnity!";
		$mail->send();

		echo 'registration';
		} catch (Exception $e) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}

		break;

	case 'contact':
		try {
		// Mail de contacto
		$mail->Body = build_mail(file_get_contents('./mailing/contacto/chefnity.html'));
		$mail->From = "test@codicilabs.com"; //info@chefnity.com
		$mail->addAddress("test@codicilabs.com"); //info@chefnity.com 
		$mail->Subject = "Mail de contacto";
		$mail->send();

		$mail->clearAddresses();

		// Copia al usuario
		$mail->Body = build_mail(file_get_contents('./mailing/contacto/user.html'));
		$mail->From = "test@codicilabs.com"; //info@chefnity.com
		$mail->addAddress($_POST['email']); 
		$mail->Subject = "Copia de tu email enviado a Chefnity";
		$mail->send();

		echo 'contact';
		} catch (Exception $e) {
		    echo 'error';
		}

		break;
	
	default:
		die('error');
		break;
}



function build_mail($html){
	foreach ($_POST as $key => $value) {
		$html = str_replace("!*".strtoupper($key)."*!", $value, $html);
	}
	return $html;
}

?>
