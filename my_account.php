<?php
include_once("check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$profile_pic = "";
$profile_pic_btn = "";
$avatar_form = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_SESSION["username"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_SESSION['username']);
} /*else {
    header("location: http://localhost:8080/camagrelle/index.php");
    exit();
}*/
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND status='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);

// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
	echo "That user does not exist or is not yet activated, press back";
    exit();
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$profile_pic_btn = '<a href="#" onclick="return false;" onmousedown="toggleElement(\'avatar_form\')">Change my picture</a>';
	$avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="photo_system.php">';
	$avatar_form .=   '<h4>Change your picture</h4>';
	$avatar_form .=   '<input type="file" name="avatar" required>';
	$avatar_form .=   '<p><input type="submit" value="Upload"></p>';
	$avatar_form .= '</form>';
}

// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_BOTH)) {
	$avatar = $row["avatar"];
}
$profile_pic = '<img src="photos/'.$u.'/'.$avatar.'" alt="'.$u.'">';
if($avatar == NULL){
	$profile_pic = '<img src="img/picture.png" alt="'.$user1.'">';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $u; ?></title>
<link rel="stylesheet" href="css/styles.css" media="screen" title="no title" charset="utf-8">
<link rel="stylesheet" href="css/profilepic.css" media="screen" title="no title" charset="utf-8">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript">
</script>
</head>
<body>
<?php include_once("includes/header.php"); ?>
<div id="pageMiddle">
  <div id="profile_pic_box"><?php echo $profile_pic_btn; ?><?php echo $avatar_form; ?><?php echo $profile_pic; ?></div>
  <h2><?php echo $u; ?></h2>
  <p><?php echo $friendsHTML; ?></p>
</div>
<?php include_once("includes/footer.php"); ?>
</body>
</html>
