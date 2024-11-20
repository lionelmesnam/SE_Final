<?php
require_once "../core/Template.php";
require_once "../controller/ContactService.php";
require_once "../controller/CartService.php";

if (isset($_SESSION['__user'])) {
	$user = $_SESSION['__user'];
	$user_id = $user['id']; // Giả sử bạn lưu user dưới dạng mảng hoặc đối tượng chứa ID
} else {
	header('location: ../index.php');
	exit; // Thoát sau khi chuyển hướng
}

// Nếu đã submit form
if (isset($_POST['send'])) {
	// Lấy giá trị từ form lên
	$name = $_POST['name'];
	$email = $_POST['email'];
	$number = $_POST['number'];
	$msg = $_POST['msg'];

	// khai báo mảng data để lưu trị vào bảng contact
	$data = [
		'user_id' => $user_id,
		'name' => $name,
		'email' => $email,
		'number' => $number,
		'message' => $msg,
	];

	//lưu data vào
	if (ContactService::create($data)) {
		$message[] = 'Gửi lời nhắn thành công!';

		// header("Location: cart.php");
		go_back();
		// exit();
	} else {
		$message[] = 'Gửi lời nhắn không thành công!';
	}
}
?>

<?php Template::head("Liên hệ"); ?>

<!-- <div class="page-inner"> -->
<?php Template::header(); ?>

<div class="heading">
	<h3>CONTACT US</h3>
	<p><a href="<?= route("/index.php") ?>">TRANG CHỦ</a> <span> / LIÊN HỆ</span></p>
</div>

<!-- contact section starts  -->

<section class="contact">

	<div class="row">

		<div class="image">
			<img src="../public/assets/imgs/Contact2.webp" alt="">
		</div>

		<form action="" method="post">
			<h3>Bạn mong muốn gì nào?</h3>
			<input type="text" name="name" maxlength="50" class="box" placeholder="Nhập tên của bạn" required>
			<input type="number" name="number" min="0" max="9999999999" class="box" placeholder="Nhập số điện thoại" required maxlength="10">
			<input type="email" name="email" maxlength="50" class="box" placeholder="Nhập email của bạn" required>
			<textarea name="msg" class="box" required placeholder="Lời nhắn của bạn" maxlength="500" cols="30" rows="10"></textarea>
			<input type="submit" value="Gửi lời nhắn" name="send" class="btn">
		</form>

	</div>

</section>

<?php Template::footer(); ?>

<?php Template::foot(); ?>