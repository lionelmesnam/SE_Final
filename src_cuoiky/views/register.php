<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/CartService.php";

// Kiểm tra nếu người dùng đã đăng nhập
// if (isset($_SESSION['__user'])) {
// 	$user = $_SESSION['__user'];
// 	$user_id = $user['id'];
// } else {
// 	header('Location: ../index.php');
// 	exit;
// }

$message = [];

if (isset($_POST['submit'])) {

	$name = $_POST['name'];
	$name = filter_var($name, FILTER_SANITIZE_STRING);
	$email = $_POST['email'];
	$email = filter_var($email, FILTER_SANITIZE_STRING);
	$number = $_POST['number'];
	$number = filter_var($number, FILTER_SANITIZE_STRING);
	$pass = sha1($_POST['pass']);
	$pass = filter_var($pass, FILTER_SANITIZE_STRING);
	$cpass = sha1($_POST['cpass']);
	$cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

	$select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
	$select_user->execute([$email, $number]);
	$row = $select_user->fetch(PDO::FETCH_ASSOC);

	if ($select_user->rowCount() > 0) {
		$message[] = 'Email hoặc số điện thoại đã tồn tại!';
	} else {
		if ($pass != $cpass) {
			$message[] = 'Mật khẩu không khớp!';
		} else {
			$insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
			$insert_user->execute([$name, $email, $number, $pass]);
			$message[] = 'Đăng ký thành công!';

			// Đăng nhập người dùng sau khi đăng ký thành công
			$select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
			$select_user->execute([$email, $pass]);
			$row = $select_user->fetch(PDO::FETCH_ASSOC);
			if ($select_user->rowCount() > 0) {
				$_SESSION['user_id'] = $row['id'];
				header('location: ../index.php');
				exit;
			}
		}
	}
}

?>

<?php
// Phần PHP của bạn...
?>

<?php Template::head("Đăng ký"); ?>

<?php Template::header(); ?>

<section class="form-container">

	<form action="" method="post">
		<h3>Đăng ký</h3>

		<?php
		if (!empty($message)) {
			foreach ($message as $msg) {
				echo '<p class="message">' . $msg . '</p>';
			}
		}
		?>

		<input type="text" name="name" required placeholder="Nhập họ và tên của bạn" class="box" maxlength="50">
		<input type="email" name="email" required placeholder="Nhập email của bạn" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
		<input type="number" name="number" required placeholder="Nhập số điện thoại" class="box" min="0" max="9999999999" maxlength="10">
		<input type="password" name="pass" required placeholder="Nhập mật khẩu của bạn" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
		<input type="password" name="cpass" required placeholder="Xác nhận lại mật khẩu" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
		<input type="submit" value="Đăng ký" name="submit" class="btn">
		<p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
	</form>

</section>

<!-- JavaScript để tự động ẩn thông báo sau 5 giây -->
<script>
    setTimeout(function() {
        const messages = document.querySelectorAll('.message');
        messages.forEach(message => message.style.display = 'none');
    }, 5000); // 5000 ms = 5 giây
</script>

<?php Template::footer(); ?>
<?php Template::foot(); ?>

