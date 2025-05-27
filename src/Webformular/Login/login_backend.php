<?php
@session_start();

require_once dirname(__FILE__, 3) . "/vendor/autoload.php";

use Classes\Datenbank;

$errors = array();
$db = new Datenbank();		# DBMS connection (default connection)

// user login
if (isset($_POST['login'])) {

	$username = mysqli_real_escape_string($db->get_conn(), $_POST['httpd_username']);
	$password = mysqli_real_escape_string($db->get_conn(), $_POST['httpd_password']);

	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	//checking for the errors
	if (count($errors) == 0) {

		# password matching
		$password = md5($password);
		$result = $db->execute_query("SELECT * FROM mitarbeiter WHERE account=? AND passwort=? ", $username, $password);
		if ($result->numRows() == 1) {
			$_SESSION['username'] = $username;		# Kennung in Session speichern
			$_SESSION['loggedin'] = true;  			# login flag

			header('location: ../index.php');
		} else {
			array_push($errors, "ID or password incorrect");
		}
	}
}
