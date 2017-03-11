<?php
include_once("check_login_status.php");

// If user is already logged in, header that weenis away
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if(isset($_POST["l"])){
	// CONNECT TO THE DATABASE
	include_once("db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
	$l = mysqli_real_escape_string($db_conx, $_POST['l']);
	$p = hash('whirlpool', $_POST['p']);
	// FORM DATA ERROR HANDLING
	if($l == "" || $p == ""){
		echo "login_failed";
        exit();
	} else {
	// END FORM DATA ERROR HANDLING
		$sql = "SELECT id, username, password FROM users WHERE username='$l' AND status='1' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
		$db_id = $row[0];
		$db_username = $row[1];
    $db_pass_str = $row[2];
		if($p != $db_pass_str){
			echo "login_failed";
            exit();
		} else {
			// CREATE THEIR SESSIONS AND COOKIES
			$_SESSION['userid'] = $db_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['password'] = $db_pass_str;
			setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
    		setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE);
			// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
			/*$sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
			echo $db_username;*/
		    exit();
		}
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Log In</title>
<link rel="stylesheet" type="text/css" href="css/styles.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function emptyElement(x){
	_(x).innerHTML = "";
}
function login(){
	var l = _("username").value;
	var p = _("password").value;
	if(l == "" || p == ""){
		_("status").innerHTML = "Fill out all of the form data";
	} else {
		_("loginbtn").style.display = "none";
		/*_("status").innerHTML = 'please wait ...';*/
		var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
						var test = ajax.responseText;
						console.log(test);
	            if(ajax.responseText == "login_failed"){
								_("status").innerHTML = "Login unsuccessful, please try again.";
								_("loginbtn").style.display = "block";
							} else {
					window.location = "user.php?u="+ajax.responseText;
				}
	        }
        }
        ajax.send("l="+l+"&p="+p);
	}
}
</script>
</head>
<body>
	<?php include_once 'includes/header.php'; ?>
<div id="pageMiddle">
  <h3>Log In Here</h3>
  <form id="loginform" onsubmit="return false;">
    <div>Username:</div>
    <input type="text" class="login_signupform" id="username" maxlength="88">
    <div>Password:</div>
    <input type="password" id="password" class="login_signupform" maxlength="100">
    <br /><br />
    <button id="loginbtn" onclick="login()">Log In</button>
    <p id="status">
			<a href="forgot_your_pass.php">Forgot Your Password?</a>
		</p>
  </form>
</div>
<?php include_once 'includes/footer.php'; ?>
</body>
</html>
