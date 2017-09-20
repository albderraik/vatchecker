<?php
DEFINE ('VIES_URL', 'http://ec.europa.eu/taxation_customs/vies/services/checkVatService');

// Check parameters
$message = "";
function checkParameters() {
	if (! isset($_POST["country"])) {
		$message = 'Error: Need two letters country code.';
		return false;
	}
	elseif (! isset($_POST["number"])) {
		$message = 'Error: Need VAT number.';
		return false;
	}

	// Get values
	$country = $_POST["country"];
	$number = $_POST["number"];
	if (strlen($country) != 2) {
		$message = 'Error: invalid coutry code. Need to be two letters country code.';
		return false;
	}
	return true;
}

/**
 * VIES VAT number validation
 *
 * @author Eugen Mihailescu
 *        
 * @param string $countryCode           
 * @param string $vatNumber         
 * @param int $timeout          
 */
function viesCheckVAT($countryCode, $vatNumber, $timeout = 30) {
	$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
	var_dump($client->checkVat(array(
		'countryCode' => $countryCode,
		'vatNumber' => $vatNumber
	)));
	return $response;
}

// Do the job
if (checkParameters())
	print_r(viesCheckVAT(strtoupper($country), $number));
?>

<html>
<head>
	<title>VAT Checker</title>
</head>
<body>
	<h3> <?php print $message ?> </h3>
	<h3> <?php print 'Country: ' . $country ?> </h3>
	<h3> <?php print 'Number: ' . $number ?> </h3>
	<form method="POST">
	Type VAT to check:<br />
	<label>Country:</label><input type="text" name="country"><br />
	<label>Number:</label><input type="text" name="number"><br />
	<input type="submit" value="submit">
	</form>
</body>
</html>

