<?php
include_once("check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: index.php");
    exit();
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND status='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
/*if($numrows < 1){
	echo "That user does not exist or is not yet activated, press back";
    exit();
}*/
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$profile_id = $row["id"];
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $u; ?></title>
<link rel="stylesheet" type="text/css" href="css/styles.css">
<link rel="stylesheet" href="style/style.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
</head>
<body>
<?php include_once 'includes/header.php'; ?>
<div id="pageMiddle">
  <h3><?php echo $u; ?></h3>
  <p>You have been verified, welcome to Camagru, have fun ! <b> </b></p>
</div>
<?php include_once 'includes/footer.php'; ?>
</body>
</html>
