<?php
session_start();
require '../config.php';
$tipe = "Masuk";

        if (isset($_SESSION['user'])) {
            header("Location: ".$config['web']['url']);
        } else {

            if (isset($_POST['masuk'])) {
                $username = $conn->real_escape_string(trim(filter($_POST['username'])));
                $password = $conn->real_escape_string(trim(filter($_POST['password'])));

                $cek_pengguna = $conn->query("SELECT * FROM users WHERE username = '$username'");
                $cek_pengguna_ulang = mysqli_num_rows($cek_pengguna);
                $data_pengguna = mysqli_fetch_assoc($cek_pengguna);

                $verif_password = password_verify($password, $data_pengguna['password']);

                $error = array();
                if (empty($username)) {
        		    $error ['username'] = '*Tidak Boleh Kosong';
                } else if ($cek_pengguna_ulang == 0) {
        		    $error ['username'] = '*Pengguna Tidak Terdaftar';
                }
                if (empty($password)) {
        		    $error ['password'] = '*Tidak Boleh Kosong';
                } else if ($verif_password <> $data_pengguna['password']) {
        		    $error ['password'] = '*Kata Sandi Anda Salah';
                } else {

                if ($data_pengguna['status'] == "Tidak Aktif") {
                    $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Akun Sudah Tidak Aktif.<script>swal("Gagal!", "Akun Sudah Tidak Aktif.", "error");</script>');

                } else if ($data_pengguna['status_akun'] == "Belum Verifikasi") {
                    $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Akun Kamu Belum Di Verifikasi.<script>swal("Gagal!", "Akun Kamu Belum Di Verifikasi.", "error");</script>');

                } else {

                        if ($cek_pengguna_ulang == 1) {
                            if ($verif_password == true) {
                                $conn->query("INSERT INTO aktifitas VALUES ('','$username', 'Masuk', '".get_client_ip()."','$date','$time')");
                                $_SESSION['user'] = $data_pengguna;
                                

                                $cookie_name = "username";
                                $cookie_value = $username;
                                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
                                


                                exit(header("Location: ".$config['web']['url']));
                            } else {
                                $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Gagal! Sistem Kami Sedang Mengalami Gangguan.<script>swal("Ups Gagal!", "Sistem Kami Sedang Mengalami Gangguan.", "error");</script>');
                            }
                        }
                    }
                }
            }
        }

        require '../lib/header_home.php';

?>

        <!-- Start Page Login -->
        <div class="login-2" style="background: rgb(0,232,255);background:linear-gradient(0deg,rgba(0,232,255,1)0%,rgba(33,137,217,1)100%);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-section">
                            <h3>Masuk</h3>
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
                                        <input type="text" name="username" class="active input-text" placeholder="Nama Pengguna" value="<?php echo $username; ?>">
                                        <i class="flaticon-user"></i>
                                        <small class="text-danger font-13 pull-right"><?php echo ($error['username']) ? $error['username'] : '';?></small>
                                    </div>
                                    <div class="form-group form-box">
                                        <input id="pswd" type="password" name="password" class="active input-text" placeholder="Kata Sandi">
                                        <i id="icon" class="flaticon-password"></i>
                                        <small class="text-danger font-13 pull-right"><?php echo ($error['password']) ? $error['password'] : '';?></small>
                                    </div>
                                    <div class="checkbox clearfix">
                                        <div class="form-check checkbox-theme">
                                            <input hidden class="form-check-input" type="checkbox" value="" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">
                                                Ingat Saya!
                                            </label>
                                        </div>
                                        <span class="pull-right"><a href="<?php echo $config['web']['url'] ?>auth/forgot-password">Lupa Kata Sandi?</a></span>
                                    </div>
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-block" name="masuk">Masuk</button>
                                    </div>
                                    <br />
                                    <p>Belum Punya Akun?<a href="<?php echo $config['web']['url'] ?>auth/register"> <b>Daftar</b></a></p>
                                      <br />
                                    <p>Belum Verifikasi Akun?<a href="<?php echo $config['web']['url'] ?>auth/verification-account"> <b>Verifikasi</b></a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Login -->
<script type="text/javascript">
var input = document.getElementById('pswd'),
    icon = document.getElementById('icon');

   icon.onclick = function () {

     if(input.className == 'active') {
        input.setAttribute('type', 'text');
        icon.className = 'flaticon-key';
       input.className = '';

     } else {
        input.setAttribute('type', 'password');
        icon.className = 'flaticon-password';
       input.className = 'active';
    }

   }
</script>        
        

<?php
require '../lib/footer_home.php';
?>
