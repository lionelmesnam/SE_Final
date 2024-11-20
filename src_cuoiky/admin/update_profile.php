<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/AdminService.php";

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location: admin_login.php');
   exit; // Thoát sau khi chuyển hướng
}

$admin = AdminService::find_by_id($admin_id);

if (isset($_POST['submit'])) {
   // Cập nhật tên
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   if (!empty($name) && $name != $admin['name']) {
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_name->execute([$name]);
      if ($select_name->rowCount() > 0) {
         $message[] = 'Tên người dùng đã được sử dụng!';
      } else {
         $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
         $message[] = 'Cập nhật tên thành công!';
      }
   }

   // Cập nhật mật khẩu
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709'; // SHA1 của chuỗi rỗng
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];

   $old_pass = sha1($_POST['old_pass']);
   $new_pass = sha1($_POST['new_pass']);
   $confirm_pass = sha1($_POST['confirm_pass']);

   if (!empty($_POST['old_pass']) || !empty($_POST['new_pass']) || !empty($_POST['confirm_pass'])) {
      if ($old_pass != $prev_pass) {
         $message[] = 'Mật khẩu cũ không đúng!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'Mật khẩu xác nhận không khớp!';
      } elseif ($new_pass == $empty_pass) {
         $message[] = 'Vui lòng nhập mật khẩu mới!';
      } else {
         $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
         $update_pass->execute([$new_pass, $admin_id]);
         $message[] = 'Cập nhật mật khẩu thành công!';
      }
   }

   // Thông báo kết quả
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '<p class="message">' . $msg . '</p>';
      }
   }
}
?>

<?php Template::headerAdmin("Cập nhật thông tin"); ?>

<!-- admin profile update section starts  -->

<section class="form-container">

   <form action="" method="POST">
      <h3>Cập nhật thông tin</h3>
      <input type="text" name="name" maxlength="20" class="box" placeholder="<?= htmlspecialchars($admin['name']); ?>">
      <input type="password" name="old_pass" maxlength="20" placeholder="Nhập mật khẩu cũ của bạn (nếu đổi)" class="box">
      <input type="password" name="new_pass" maxlength="20" placeholder="Nhập mật khẩu mới của bạn (nếu đổi)" class="box">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="Xác nhận lại mật khẩu (nếu đổi)" class="box">
      <input type="submit" value="Cập nhật thông tin" name="submit" class="btn">
   </form>

</section>

<!-- admin profile update section ends -->

<?php Template::footerAdmin(); ?>