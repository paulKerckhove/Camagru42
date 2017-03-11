<?php
session_start();

// If user is logged in, header them away
if(isset($_SESSION["username"])){
	/*include_once("includes/header.php");
	include_once("includes/footer.php");*/
	header("location: message.php?msg=You are already logged in");
    exit();
}

?><?php
include_once 'db_conx.php';
// Ajax calls this code to execute
if(isset($_POST["usernamecheck"])){
	include_once("config/setup.php");
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
	/*echo $username;*/
	$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {
	    echo '<strong style="color:#F00;"> Username must contain 3 to 16 characters please</strong>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<strong style="color:#F00;">Username must begin with a letter</strong>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '<strong style="color:#3498db;">' . $username . ' is alvailable</strong>';
	    exit();
    } else {
	    echo '<strong style="color:#3498db;">' . $username . ' is not alvailable</strong>';
	    exit();
    }
}
?><?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){
	// CONNECT TO THE DATABASE
	include_once("config/setup.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = $_POST['p'];
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
	$u_check = mysqli_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($u == "" || $e == "" || $p == ""){
		echo "please fill out every field in the form.";
        exit();
	} else if ($u_check > 0){
        echo "The username you entered is not alvailable";
        exit();
	} else if ($e_check > 0){
        echo "This email is not alvailable";
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "Username must contain 3 to 16 characters";
        exit();
    } else if (is_numeric($u[0])) {
        echo 'Username must begin with a letter';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt, cryptpass will give any password a 34 bit (char) cryptic hash
		/*$cryptpass = crypt($p);*/
		$p_hash = hash('whirlpool', $p);
		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (username, email, password)
		        VALUES('$u','$e','$p_hash')";
		$query = mysqli_query($db_conx, $sql);
		$uid = mysqli_insert_id($db_conx);
		$sql = "INSERT INTO user_option (id, username, email) VALUES ('$uid','$u', '$e')";
		$query = mysqli_query($db_conx, $sql);
		// Establish their row in the useroptions table
		// Email the user their activation link
		$to = "$e";
		$from = "Camagru_pkerckho";
		$subject = 'Camagru Account Activation';
		$message = 'Hello '.$u.',<br /><br /> Welcome to Camagru, Click the link below to activate your account : <br /><br /><a href="http://localhost:8080/camagrelle/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Activate your account </a><br /><br />If you did not signup to Camagru recently, please disregard this email<br /></b></div></body></html>';
		$headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers);
		echo "signup_success";
		exit();
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
	_(x).innerHTML = "";
}
function checkusername(){
	var u = _("username").value;
	if(u != ""){
		_("unamestatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("unamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("usernamecheck="+u);
	}
}
function signup(){
	var u = _("username").value;
	var e = _("email").value;
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var status = _("status");
	if(u == "" || e == "" || p1 == "" || p2 == ""){
		status.innerHTML = "please fill out every field in the form";
	} else if(p1 != p2){
		status.innerHTML = "Passwords do not match";
	} else {
		_("signupbtn").style.display = "none";
		status.innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "signup.php");
		ajax.send("u="+u+"&e="+e+"&p="+p1);
    ajax.onreadystatechange = function() {
      if(ajaxReturn(ajax) == true) {
          if(ajax.responseText != "signup_success"){
			status.innerHTML = ajax.responseText;
			_("signupbtn").style.display = "block";
		} else {
			window.scrollTo(0,0);
			_("signupform").innerHTML = "Ok "+u+", please check your email inbox and junk mail box at <u>"+e+"</u> to complete the sign up process by activating your account.";
		}
      }
    }
	}
}
function openTerms(){
	_("terms").style.display = "block";
	emptyElement("status");
}
/* function addEvents(){
	_("elemID").addEventListener("click", func, false);
}
window.onload = addEvents; */
</script>
</head>
<body>
	<link rel="stylesheet" type="text/css" href="css/styles.css" >
<?php include_once 'includes/header.php'; ?>
<div id="pageMiddle">
  <h3>Sign up</h3>
  <form name="signupform" id="signupform" onsubmit="return false;">
    <div>Username: </div>
    <input id="username" type="text" class="login_signupform" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
    <span id="unamestatus"></span>
    <div>Email Address:</div>
    <input id="email" type="text" class="login_signupform" onkeyup="restrict('email')" maxlength="88">
    <div>Create Password:</div>
    <input id="pass1" type="password" class="login_signupform" maxlength="16">
    <div>Confirm Password:</div>
    <input id="pass2" type="password" class="login_signupform" maxlength="16">
    <br /><br />
    <button id="signupbtn" onclick="signup()">Create Account</button>
    <span id="status"></span>
  </form>
</div>
<?php include_once 'includes/footer.php'; ?>
</body>
</html>
