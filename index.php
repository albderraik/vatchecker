<?php
DEFINE ('VIES_URL', 'http://ec.europa.eu/taxation_customs/vies/services/checkVatService');
DEFINE ('DEBUG', false);

// Initialize variables
$message = '';
$country = '';
$number = '';
$vat = isset($_GET['vat']) ? $_GET['vat'] : '';

/**
 * VIES VAT number validation
 *
 * @param string $countryCode           
 * @param string $vatNumber         
 * @param int $timeout          
 */
function viesCheckVAT($countryCode, $vatNumber, $timeout = 30) {
	$response = array ();
	$pattern = '/<(%s).*?>([\s\S]*)<\/\1/';
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

	print_array($result);

	// Process return
	$check = preg_match(sprintf($pattern, 'checkVatResponse'), $result, $matches);
	if (DEBUG) print('<br>Check 1: '.$check);
	if ($check == 1) {
		print_array($matches);
		foreach($keys as $key)
			preg_match(sprintf($pattern, $key), $matches[2], $value) && $response[$key] = $value[2];
	} else {
		$check = preg_match(sprintf($pattern, 'soap:Fault'), $result, $matches);
		if (DEBUG) print('<br>Check 2: '.$check);
		if ($check == 1) {
			print_array($matches);
			foreach($keys as $key)
				preg_match(sprintf($pattern, $key), $matches[2], $value) && $response[$key] = $value[2];
		}
	}
	return $response;
}

function print_array($aArray) {
	if (!DEBUG) return;
    // Print a nicely formatted array representation:
	echo '<pre>';
	print_r($aArray);
	echo '</pre>';
}

// Check parameter
if ($vat == '') {
	$message = 'Error: Invalid VAT code.';
} else {
	// Get values
	$country = substr($vat, 0, 2);
	$number = substr($vat, 2);
	if ($country == '') {
		$message = 'Error: Need two letters country code. [' . $country . ']';
	} else if ($number == '') {
		$message = 'Error: Invalid number code. [' . $number . ']';
	} else {
		// Do the job
		if (DEBUG) print('Country: [' . $country . ']<br>');
		if (DEBUG) print('Number: [' . $number . ']<br>');
		$result = viesCheckVAT(strtoupper($country), $number);
		// Check results
		if (isset($result['valid'])) {
			$message = ($result['valid'] == 'true' ? "Valid" : "Invalid");
		} else {
			$message = isset($result['faultstring']) ? $result['faultstring'] : '';
			if ($message == '') {
				$message = "Valid";
			}
		}
	}
}
?>
<?php print $message ?>
