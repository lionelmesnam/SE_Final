<?php
require_once "../core/Template.php";
require_once "../controller/UserService.php";

if (isset($_POST['submit'])) {
	$email = $_POST['email'];
	$pass = sha1($_POST['pass']);

	// Lấy thông tin người dùng từ UserService bằng email
	$user = UserService::getUserByEmail($email);

	if ($user && $user['password'] == $pass) {
		// 3. Lưu toàn bộ thông tin người dùng vào session
		Session::set_session('__user', $user);

		redirect("/"); // Chuyển hướng về trang chủ
	} else {
		echo "Sai thông tin đăng nhập!";
	}
}

?>

<?php Template::head("Đăng nhập"); ?>

<?php Template::header(); ?>

<section class="form-container">

	<form action="" method="post">
		<h3>Đăng nhập</h3>
		<input type="email" name="email" required placeholder="Nhập email của bạn" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
		<input type="password" name="pass" required placeholder="Nhập mật khẩu của bạn" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
		<input type="submit" value="Đăng nhập" name="submit" class="btn">
		<p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
	</form>

</section>


<?php Template::footer(); ?>

<?php Template::foot(); ?>