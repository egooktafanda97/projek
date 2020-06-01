<?php 
if (isset($_SESSION['user']) OR isset($_COOKIE['username'])) {
	$sess_username = ((isset($_COOKIE['username']))?$_COOKIE['username']:$_SESSION['user']['username']);
    $check_user = $conn->query("SELECT * FROM users WHERE username = '".$sess_username."'");
    $data_user = $check_user->fetch_assoc();
    $check_username = $check_user->num_rows;
    if ($check_username == 0) {
        exit(header("Location: ".$config['web']['url']."logout.php"));
    } else if ($data_user['status'] == "Tidak Aktif") {
        exit(header("Location: ".$config['web']['url']."logout.php"));
    }
	$sess_username = $sess_username;
}
