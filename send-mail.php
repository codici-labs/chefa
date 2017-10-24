<?php

print_r($_POST);
die();

$type=$_POST['type'];
$name=$_POST['name'];
$email=$_POST['email'];
$message=$_POST['message'];
$phone=$_POST['phone'];
    
    $body .= "Chef/Comprador: " . $type . "\n"; 
    $body .= "Nombre: " . $name . "\n"; 
    $body .= "Email: " . $email . "\n"; 
	$body .= "Teléfono: " . $phone . "\n"; 
    $body .= "Mensaje: " . $message . "\n"; 

    //replace with your email-sotomayorsebastian4@gmail.com
    mail("gdzabaleta@gmail.com","Nuevo email","$body","From: $Email"); 

  
?>
