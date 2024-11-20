<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/AdminService.php";

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location: admin_login.php');
};

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);

   if ($select_admin->rowCount() > 0) {
      $message[] = 'Tên người dùng đã tồn tại!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Mật khẩu xác nhận không khớp!';
      } else {
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'Đăng ký thành công!';
      }
   }
}

?>

<?php Template::headerAdmin("Đăng ký admin"); ?>

<!-- register admin section starts  -->

<section class="form-container">

   <form action="" method="POST">
      <h3>Đăng ký admin</h3>
      <input type="text" name="name" maxlength="20" required placeholder="Nhập tên tài khoản" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="Nhập mật khẩu" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" maxlength="20" required placeholder="Xác nhận lại mật khẩu" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Đăng ký" name="submit" class="btn">
   </form>

</section>

<!-- register admin section ends -->


<?php Template::footerAdmin(); ?>