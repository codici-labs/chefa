<?php
require_once ('./lib/mercadopago.php');

if($_POST){
	$url = 'http://codicilabs.com/trabajos/chefnity/'
	$mp = new MP ("2265501374633304", "0dFWJNE1CqTT91acmo8YUfGXasg3wUNy");

	$access_token = $mp->get_access_token();

	#print_r ($access_token);
	$preference_data = array(
	    "items" => array(
		array(
		    "id" => $_POST['id'],
		    "title" => $_POST['title'],
		    "quantity" => $_POST['quantity'],
		    "currency_id" => "ARS", //Available currencies at: https://api.mercadopago.com/currencies
		    "picture_url" => $url.$_POST['image'],
		    "unit_price" => $_POST['price']
		)
	    )
	);

	$preference = $mp->create_preference($preference_data);
	if($preference){
		header($preference['response']['sandbox_init_point']);
	}else{
		die('Error');
	}
}else{
	die('Error');
}

/*$preference_data = array(
    "items" => array(
	array(
	    "id" => "item-ID-1234",
	    "title" => "Robopill, el robot mala onda",
	    "quantity" => 1,
	    "currency_id" => "ARS", //Available currencies at: https://api.mercadopago.com/currencies
	    "picture_url" => "http://www.codicilabs.com/mercadopago-sdk-php/robopill.jpg",
	    "unit_price" => 10.00
	)
    )
);*/


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