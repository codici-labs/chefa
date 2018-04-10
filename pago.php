<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
require_once ('./lib/mercadopago.php');
include("./sqlserver.php");

if($_POST){


	////////////// inserto en tabla sales /////////////

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}
	$orderId = strval(time()) . strval(rand(0,9999));
	$sql = "INSERT INTO sales (email, amount, price, food_id, food, address, orderID) VALUES ('" . $_POST['compra-email'] . "', '" . $_POST['amount'] . "', '" . $_POST['price'] . "', " . $_POST['id'] . ", '" . $_POST['title'] . "', '" . $_POST['direccion'] . "', '" . $orderId . "')";

	if (mysqli_query($conn, $sql)) {
	    //echo "New record created successfully";
	    //$externalReference = mysqli_insert_id($conn);
	} else {
	    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);




	////////////// seguimos con ML /////////////

	$url = 'https://www.chefnity.com/';
	$mp = new MP ("xxxx", "xxxx");  //chefnity

	$access_token = $mp->get_access_token();

	$preference_data = array(
	    "items" => array(
			array(
			    "id" => $_POST['id'],
			    "title" => $_POST['title'],
			    "quantity" => intval($_POST['amount']),
			    "currency_id" => "ARS", //Available currencies at: https://api.mercadopago.com/currencies
			    "picture_url" => $url.$_POST['image'],
			    "unit_price" => intval($_POST['price'])
			)
	    ),
	    "payer" => array(
				"email" => $_POST['compra-email'], //mail que viene por post??
		),
	    "back_urls" => array(
			"success" => "https://www.chefnity.com/bien.php",
			"failure" => "https://www.chefnity.com/mal.php",
			"pending" => "https://www.chefnity.com/pendiente.php"
		),
		"notification_url" => "http://www.codicilabs.com/mercadopago-sdk-php/mepagaron.php",
		"auto_return" => "approved",
		"payment_methods" => array(
			"excluded_payment_types" => array(
				array(
					"id" => "ticket",
				),
				array(
					"id" => "atm",
				),
				array(
					"id" => "bank_transfer",
				)

			)
		),
		"external_reference" => $orderId
	);



	$preference = $mp->create_preference($preference_data);
	if($preference){
		$redirect = $preference['response']['init_point'];
		header('Location:'.$redirect);
		echo $redirect;
		exit;
	}else{
		die('Error');
	}
}else{
	die('Error');
}

?>

<!DOCTYPE html>
<html>
    <head>
	<title>Chefnity</title>
    </head>
    <body>

	Esta siendo redireccionado a mercado pago.
    </body>
</html>