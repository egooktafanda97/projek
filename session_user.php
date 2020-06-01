
<?php
	if (!isset($_SESSION['user']) OR !isset($_COOKIE['username'])) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Silahkan Masuk Terlebih Dahulu.');
		exit(header("Location: ".$config['web']['url']."auth/login"));
	}
