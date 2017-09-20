<?php
DEFINE ('VIES_URL', 'http://ec.europa.eu/taxation_customs/vies/services/checkVatService');

// Check parameters
$message = "";
$country = "";
$number = "";
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
	$response = array ();
	$pattern = "/<(%s).*?>([\s\S]*)<\/\1/";
	$keys = array (
		'countryCode',
		'vatNumber',
		'requestDate',
		'valid',
		'name',
		'address' 
	);

	// Mount XML
	$content =
"<s11:Envelope xmlns:s11='http://schemas.xmlsoap.org/soap/envelope/'>
	<s11:Body>
		<tns1:checkVat xmlns:tns1='urn:ec.europa.eu:taxud:vies:services:checkVat:types'>
			<tns1:countryCode>%s</tns1:countryCode>
			<tns1:vatNumber>%s</tns1:vatNumber>
		</tns1:checkVat>
	</s11:Body>
</s11:Envelope>";

	// Mount HTML request
    $opts = array (
		"http" => array (
			"method" => "POST",
			"header" => "Content-Type: text/xml; charset=utf-8; SOAPAction: checkVatService",
			"content" => sprintf($content, $countryCode, $vatNumber),
			"timeout" => $timeout 
		) 
	);

	print('<pre>');
		print_r($opts);
	print('</pre>');

	// Do the job
	$ctx = stream_context_create($opts);
	$result = file_get_contents(VIES_URL, false, $ctx);

	// Process return
	if (preg_match(sprintf($pattern, 'checkVatResponse'), $result, $matches)) {
		foreach($keys as $key)
			preg_match(sprintf($pattern, $key), $matches [2], $value) && $response [$key] = $value [2];
	}
	return $response;
}

// Do the job
if (checkParameters()) {
	print('<pre>');
		print_r(viesCheckVAT(strtoupper($country), $number));
	print('</pre>');
}
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

