<?php
$num_to_guess = 42;
$message = "";
if ( ! isset( $_POST["guess"] ) )
	$message = "Welcome to the guessing machine!";
else {
	$guess = (int) $_POST["guess"];
	if  ( $guess > $num_to_guess )
		$message = "$guess is too big! Try a smaller number";
	elseif  ( $guess < $num_to_guess )
		$message = "$guess is too small! Try a larger number";
	else // must be equivalent
		$message = "Well done!";
}
?>
<html>
<head>
<title>Listing 9.9 A PHP number guessing script</title>
</head>
<body>
<h1>
<?php print $message ?>
</h1>
<form method="POST">
Type your guess here: <input type="text" name="guess" value="<?php print $guess?>">
</form>
</body>
</html>
