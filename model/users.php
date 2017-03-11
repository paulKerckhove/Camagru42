<?php

/*
** Functions resume :
**
** All function return TRUE if success, FALSE otherwise.
**
** create_user($login, $pass, $is_admin = false);
** update_user_pass($login, $pass, $newpass);
** update_user_pass_by_admin($user_login, $user_newpass, $admin_login, $admin_pass);
** delete_user($login, $pass);
** delete_user_by_admin($user_login, $admin_login, $admin_pass);
** connect_user($login, $pass);
**
** This function return an array with all users (empty if no one).
**
** get_all_users();
*/

function create_user($login, $pass, $is_admin = false) {
	// Check params
	$is_valid = (gettype($login) == "string" && strlen($login) < 255);
	$is_valid = ($is_valid && gettype($pass) == "string" && strlen($pass) < 255);
	$is_valid = ($is_valid && gettype($is_admin) == "boolean");
	if (!$is_valid)
		return (false);

	// Connect to bdd
	if (!($bdd = init_mysql_connection()))
		return (false);

	// Check if login already exist
	$req = mysqli_prepare($bdd, 'SELECT login FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $login);
	$result = mysqli_stmt_execute($req);
	mysqli_stmt_bind_result($req, $data['login']);
	mysqli_stmt_fetch($req);
	mysqli_stmt_close($req);
	if ($data['login'])
		return (false);

	// Hash password
	$pass = hash("whirlpool", $pass);

	// Process to insertion
	$req = mysqli_prepare($bdd, 'INSERT INTO users (login, pass, is_admin) VALUES (?, ?, ?)');
	$is_admin = $is_admin ? 1 : 0;
	mysqli_stmt_bind_param($req, "ssi", $login, $pass, $is_admin);
	mysqli_stmt_execute($req);
	mysqli_stmt_close($req);
	return (true);
}

function update_user_pass($login, $pass, $newpass) {
	// Check params
	$is_valid = (gettype($pass) == "string" && strlen($pass) < 255);
	$is_valid = ($is_valid && gettype($login) == "string" && strlen($login) < 255);
	$is_valid = ($is_valid && gettype($newpass) == "string" && strlen($newpass) < 255);
	if (!$is_valid)
		return (false);

	// Connect to bdd
	if (!($bdd = init_mysql_connection()))
		return (false);

	// Check if login and pass are correct
	$req = mysqli_prepare($bdd, 'SELECT pass FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $login);
	mysqli_stmt_execute($req);
	mysqli_stmt_bind_result($req, $user['pass']);
	mysqli_stmt_fetch($req);
	mysqli_stmt_close($req);
	if (!$user || hash("whirlpool", $pass) != $user['pass'])
		return (false);

	// Hash new password
	$newpass = hash("whirlpool", $newpass);

	// Process to update
	$req = mysqli_prepare($bdd, 'UPDATE users SET pass = ? WHERE login = ?');
	mysqli_stmt_bind_param($req, "ss", $newpass, $login);
	mysqli_stmt_execute($req);
	mysqli_stmt_close($req);
	return (true);
}

function update_user_pass_by_admin($user_login, $user_newpass, $admin_login, $admin_pass) {
	// Check params
	$is_valid = (gettype($user_login) == "string" && strlen($login) < 255);
	$is_valid = ($is_valid && gettype($user_newpass) == "string" && strlen($user_newpass) < 255);
	$is_valid = ($is_valid && gettype($admin_login) == "string" && strlen($admin_login) < 255);
	$is_valid = ($is_valid && gettype($admin_pass) == "string" && strlen($admin_pass) < 255);
	if (!$is_valid)
		return (false);

	// Connect to bdd
	if (!($bdd = init_mysql_connection()))
		return (false);

	// Check if admin is correct
	$req = mysqli_prepare($bdd, 'SELECT pass, is_admin FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $admin_login);
	mysqli_stmt_execute($req);
	mysqli_stmt_bind_result($req, $admin['pass'], $admin['is_admin']);
	mysqli_stmt_fetch($req);
	mysqli_stmt_close($req);
	if (!$admin || !$admin['is_admin'] || hash("whirlpool", $admin_pass) != $admin['pass'])
		return (false);

	// Check if user exist
	$req = mysqli_prepare($bdd, 'SELECT id FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $user_login);
	mysqli_stmt_execute($req);
	mysqli_stmt_bind_result($req, $user['id']);
	mysqli_stmt_fetch($req);
	mysqli_stmt_close($req);
	if (!$user['id'])
		return (false);

	// Hash new password
	$user_newpass = hash("whirlpool", $user_newpass);

	// Process to update
	$req = mysqli_prepare($bdd, 'UPDATE users SET pass = ? WHERE login = ?');
	mysqli_stmt_bind_param($req, "ss", $user_newpass, $user_login);
	mysqli_stmt_execute($req);
	mysqli_stmt_close($req);
	return (true);
}

function delete_user($login, $pass) {
	// Check params
	$is_valid = (gettype($pass) == "string" && strlen($pass) < 255);
	$is_valid = ($is_valid && gettype($login) == "string" && strlen($login) < 255);
	if (!$is_valid)
		return (false);

	// Connect to bdd
	if (!($bdd = init_mysql_connection()))
		return (false);

	// Check if login and pass are correct
	$req = mysqli_prepare($bdd, 'SELECT pass FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $login);
	mysqli_stmt_execute($req);
	mysqli_stmt_bind_result($req, $user['pass']);
	mysqli_stmt_fetch($req);
	mysqli_stmt_close($req);
	if (!$user || hash("whirlpool", $pass) != $user['pass'])
		return (false);

	// Process to deletion
	$req = mysqli_prepare($bdd, 'DELETE FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $login);
	mysqli_stmt_execute($req);
	mysqli_stmt_close($req);
	return (true);
}

function delete_user_by_admin($user_login, $admin_login, $admin_pass) {
	// Check params
	$is_valid = (gettype($user_login) == "string" && strlen($user_login) < 255);
	$is_valid = ($is_valid && gettype($admin_login) == "string" && strlen($admin_login) < 255);
	$is_valid = ($is_valid && gettype($admin_pass) == "string" && strlen($admin_pass) < 255);
	if (!$is_valid)
		return (false);

	// Connect to bdd
	if (!($bdd = init_mysql_connection()))
		return (false);

	// Check if admin is correct
	$req = mysqli_prepare($bdd, 'SELECT pass, is_admin FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $admin_login);
	mysqli_stmt_execute($req);
	mysqli_stmt_bind_result($req, $admin['pass'], $admin['is_admin']);
	mysqli_stmt_fetch($req);
	mysqli_stmt_close($req);
	if (!$admin || !$admin['is_admin'] || hash("whirlpool", $admin_pass) != $admin['pass'])
		return (false);

	// Process to deletion
	$req = mysqli_prepare($bdd, 'DELETE FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $user_login);
	mysqli_stmt_execute($req);
	mysqli_stmt_close($req);
	return (true);
}

function get_all_users() {
	// Connect to bdd
	if (!($bdd = init_mysql_connection()))
		return (false);

	// Get all existing users
	$result = mysqli_query($bdd, 'SELECT id, login, is_admin FROM users');
	$users = array();
	while ($user = mysqli_fetch_assoc($result))
		array_push($users, $user);
	mysqli_free_result($result);
	return ($users);
}

function connect_user($login, $pass) {
	// Check params
	$is_valid = (gettype($login) == "string" && strlen($login) < 255);
	$is_valid = ($is_valid && gettype($pass) == "string" && strlen($pass) < 255);
	if (!$is_valid)
		return (false);

	// Connect to bdd
	if (!($bdd = init_mysql_connection()))
		return (false);

	// Get user from login
	$req = mysqli_prepare($bdd, 'SELECT * FROM users WHERE login = ?');
	mysqli_stmt_bind_param($req, "s", $login);
	mysqli_stmt_execute($req);
	mysqli_stmt_bind_result($req, $user['id'], $user['login'], $user['pass'], $user['is_admin']);
	mysqli_stmt_fetch($req);
	mysqli_stmt_close($req);
	if (!$user)
		return (false);
	if (hash("whirlpool", $pass) != $user['pass'])
		return (false);
	// Remove the hash pass
	unset($user['pass']);
	return ($user);
}
