<?php
DEFINE ('VIES_URL', 'http://ec.europa.eu/taxation_customs/vies/services/checkVatService');

// Check parameters
$message = "";
$country = isset( $_POST['country'] ) ? $_POST['country'] : '';
$number = isset( $_POST['number'] ) ? $_POST['number'] : '';
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
	$pattern_error = "/<soap:(%s).*?>([\s\S]*)<\/\1/";
	$keys = array (
		'countryCode',
		'vatNumber',
		'requestDate',
		'valid',
		'name',
		'address',
		'faultstring',
		'faultcode'
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
			"header" => "Content-Type: text/xml\r\n charset=utf-8\r\n",
			"content" => sprintf($content, $countryCode, $vatNumber),
			"timeout" => $timeout 
		) 
	);

	// Do the job
	$ctx = stream_context_create($opts);
	$result = file_get_contents(VIES_URL, false, $ctx);

//	print_array($result);

	// Process return
	if (preg_match(sprintf($pattern, 'checkVatResponse'), $result, $matches)) {
		print_array('OK');
		foreach($keys as $key)
			preg_match(sprintf($pattern, $key), $matches[2], $value) && $response[$key] = $value[2];
	} else if (preg_match(sprintf($pattern_error, 'soap:Fault'), $result, $matches)) {
		print_array('ERROR');
		foreach($keys as $key)
			preg_match(sprintf($pattern, $key), $matches[2], $value) && $response[$key] = $value[2];
	}
	return $response;
}

function print_array($aArray) {
    // Print a nicely formatted array representation:
	echo '<pre>';
	print_r($aArray);
	echo '</pre>';
}

// Do the job
if (checkParameters()) {
	$result = viesCheckVAT(strtoupper($country), $number);
	$message = isset( $result['faultstring'] ) ? $result['faultstring'] : '';
	if ($message == '') {
		$message = "Valid VAT code.";
	}
}
?>

<html>
<head>
	<title>VAT Checker</title>
</head>
<body>
	<h3> <?php print $message ?> </h3>
	<h3> <?php if (isset($country) && $country != '') print 'Country: ' . $country ?> </h3>
	<h3> <?php if (isset($number) && $number != '') print 'Number: ' . $number ?> </h3>
	<form method="POST">
	Type VAT to check:<br />
	<label>Country:</label><input type="text" name="country" value="<?php echo $country ?>"><br />
	<label>Number:</label><input type="text" name="number" value="<?php echo $number ?>"><br />
	<input type="submit" value="submit">
	</form>
</body>
</html>

