<?php
include_once 'includes/header.php';
include_once 'includes/footer.php';

$message = "";
$msg = preg_replace('#[^a-z 0-9.:_()]#i', '', $_GET['msg']);
if($msg == "activation_failure"){
	$message = '<h2>Activation Error</h2> Sorry there seems to have been an issue activating your account. You cannot acces our services for now';
} else if($msg == "activation_success"){
	$message = '<h2>Activation Success</h2> Your account is now activated. <a href="login.php">Click here to log in</a>';
} else {
	$message = $msg;
}
?>
<div><?php echo $message; ?></div>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
</html>
