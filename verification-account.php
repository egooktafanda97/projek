<?php
session_start();
require("../config.php");
require("../lib/class.phpmailer.php");
$tipe = "Verifikasi Akun";

	    if (isset($_POST['kirim_kode'])) {
	        $email = $conn->real_escape_string(filter(trim($_POST['email'])));

	        $cek_pengguna = $conn->query("SELECT * FROM users WHERE email = '$email'");
	        $cek_pengguna_ulang = mysqli_num_rows($cek_pengguna);
	        $data_pengguna = mysqli_fetch_assoc($cek_pengguna);

	        $error = array();
	        if (empty($email)) {
			    $error ['email'] = '*Tidak Boleh Kosong';
	        } else if ($cek_pengguna_ulang == 0 ) {
			    $error ['email'] = '*Email Tidak Ditemukan';
	        } else {

	        if ($data_email['status_akun'] == "Sudah Verifikasi") {
	            $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Akun Kamu Sudah Di Verifikasi.<script>swal("Gagal!", "Akun Kamu Sudah Di Verifikasi.", "error");</script>');

	        } else {

	        $kode_verifikasi = acak_nomor(3).acak_nomor(3);
	        $pengguna = $data_pengguna['username'];

	        $mail = new PHPMailer;
	        $mail->IsSMTP();
	        $mail->SMTPSecure = 'ssl'; 
            $mail->Host = "jetpedia.id"; //host masing2 provider email
            $mail->SMTPDebug = 2;
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->Username = "no-reply@jetpedia.id"; //user email
            $mail->Password = "Pekalongan123"; //password email 
            $mail->SetFrom("no-reply@jetpedia.id","jetpedia.id"); //set email pengirim
	        $mail->Subject = "Verifikasi Akun"; //subyek email
	        $mail->AddAddress("$email","");  //tujuan email
	        $mail->MsgHTML("Verifikasi Akun<br><br><b>Email : $email<br>Kode Verifikasi : $kode_verifikasi<b><br>Silahkan Verifikasi Akun Anda Dengan Menggunakan Kode Verifikasi, Terima Kasih!");
	        if ($mail->Send());
	            if ($conn->query("UPDATE users SET kode_verifikasi = '$kode_verifikasi' WHERE username = '".$data_pengguna['username']."'") == true) {
                    $_SESSION['hasil'] = array('alert' => 'success', 'pesan' => 'Sip, Kode Verifikasi Berhasil Dikirim Ke Email Kamu.<script>swal("Berhasil!", "Kode Verifikasi Berhasil Dikirim.", "success");</script>');
                } else {
                    $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Gagal! Sistem Kami Sedang Mengalami Gangguan.<script>swal("Ups Gagal!", "Sistem Kami Sedang Mengalami Gangguan.", "error");</script>');
                }
            }
        }

	    } else if (isset($_POST['verifikasi'])) {
	        $email = $conn->real_escape_string(filter(trim($_POST['email'])));
	        $kode = $conn->real_escape_string(filter(trim($_POST['kode'])));

            $cek_pengguna = $conn->query("SELECT * FROM users WHERE email = '$email'");
            $cek_pengguna_ulang = mysqli_num_rows($cek_pengguna);
            $data_pengguna = mysqli_fetch_assoc($cek_pengguna);

            $cek_kode = $conn->query("SELECT * FROM users WHERE kode_verifikasi = '$kode'");
            $cek_kode_ulang = mysqli_num_rows($cek_kode);
            $data_kode = mysqli_fetch_assoc($cek_kode);

            $cek_referral = $conn->query("SELECT * FROM setting_referral WHERE status = 'Aktif'");
            $cek_referral_ulang = mysqli_num_rows($cek_referral);
            $data_referral = mysqli_fetch_assoc($cek_referral);

            $error = array();
	        if (empty($email)) {
			    $error ['email'] = '*Tidak Boleh Kosong';
	        } else if ($cek_pengguna_ulang == 0) {
			    $error ['email'] = '*Email Tidak Ditemukan';
	        }
            if (empty($kode)) {
		        $error ['kode'] = '*Tidak Boleh Kosong';
            } else if ($data_pengguna['kode_verifikasi'] <> $kode) {
		        $error ['kode'] = '*Kode Verifikasi Tidak Sesuai';
            } else {

	        if ($data_pengguna['status_akun'] == "Sudah Verifikasi") {
	            $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Akun Kamu Sudah Di Verifikasi.<script>swal("Gagal!", "Akun Kamu Sudah Di Verifikasi.", "error");</script>');

	        } else {

                if ($conn->query("UPDATE users SET status_akun = 'Sudah Verifikasi' WHERE username = '".$data_kode['username']."'") == true) {
                    
                    // $conn->query("UPDATE users SET koin = koin+".$data_referral['jumlah']." WHERE username = '".$data_pengguna['uplink_referral']."'");




                    // $conn->query("INSERT INTO riwayat_saldo_koin VALUES ('', '".$data_pengguna['uplink_referral']."', 'Koin', 'Penambahan Koin', '".$data_referral['jumlah']."', 'Mendapatkan Koin Melalui Referral Akun Dengan Nama Pengguna :  ".$data_kode['username']."', '$date', '$time')");

                    
                    // $conn->query("INSERT INTO riwayat_referral VALUES ('', '".$data_kode['username']."', '".$data_pengguna['uplink_referral']."', '".$data_pengguna['kode_referral']."', '".$data_referral['jumlah']."', '$date', '$time')"); 

                    $_SESSION['hasil'] = array('alert' => 'success', 'pesan' => 'Sip, Verifikasi Akun Berhasil, Silahkan Masuk Untuk Melanjutkan Transaksi.<script>swal("Berhasil!", "Akun Kamu Berhasil Di Verifikasi.", "success");</script>');
                } else {
                    $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Gagal! Sistem Kami Sedang Mengalami Gangguan.<script>swal("Ups Gagal!", "Sistem Kami Sedang Mengalami Gangguan.", "error");</script>');
                }
            }
        }

	    }

        require '../lib/header_home.php';

?>

        <!-- Start Page Verification Account -->
        <div class="login-2" style="background:rgb(0,232,255);background:linear-gradient(0deg,rgba(0,232,255,1)0%,rgba(33,137,217,1)100%);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-section">
                            <h3>Verifikasi Akun</h3>
                            <?php
                            if (isset($_SESSION['hasil'])) {
                            ?>
                            <div class="alert alert-<?php echo $_SESSION['hasil']['alert'] ?> alert-dismissible" role="alert">
                                <?php echo $_SESSION['hasil']['pesan'] ?>
                            </div>
                            <?php
                            unset($_SESSION['hasil']);
                            }
                            ?>
                            <div class="login-inner-form">
                                <form class="form-horizontal" role="form" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                    <div class="form-group form-box">
                                        <input type="email" name="email" class="input-text" placeholder="Masukkan Email Kamu" value="<?php echo $email; ?>">
                                        <i class="flaticon-email"></i>
                                        <small class="text-danger font-13 pull-right"><?php echo ($error['email']) ? $error['email'] : '';?></small>
                                    </div>
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-block" name="kirim_kode">Kirim Kode</button>
                                    </div>
                                    <br />
                                    <div class="form-group form-box">
                                        <input type="number" name="kode" class="input-text" placeholder="Masukkan Kode Verifikasi">
                                        <i class="flaticon-password"></i>
                                        <small class="text-danger font-13 pull-right"><?php echo ($error['kode']) ? $error['kode'] : '';?></small>
                                    </div>
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-block" name="verifikasi">Verifikasi</button>
                                    </div>
                                    <br />
                                    <p>Sudah Verifikasi Akun ?<a href="<?php echo $config['web']['url'] ?>auth/login"> Masuk</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Verification Account -->

<?php
require '../lib/footer_home.php';
?>
