<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/AdminService.php";

if (isset($_POST['submit'])) {
   // Lấy và xử lý dữ liệu từ form
   $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
   $pass = filter_var(sha1(trim($_POST['pass'])), FILTER_SANITIZE_STRING);

   // Gọi phương thức login từ AdminService
   $admin = AdminService::login($name, $pass);

   if ($admin) {
      // Nếu thông tin hợp lệ, lưu session và chuyển đến trang dashboard
      $_SESSION['admin_id'] = $admin['id'];
      header('location:dashboard.php');
      exit;
   } else {
      $message[] = 'Tên đăng nhập hoặc mật khẩu không chính xác!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập</title>
   <link rel="shortcut icon" href="../public/assets/imgs/icon.png" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../public/assets/css/admin_style.css">
</head>

<body>

   <?php
   // Hiển thị thông báo lỗi nếu có
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '
      <div class="message">
         <span>' . $msg . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
   }
   ?>

   <!-- admin login form section starts  -->

   <section class="form-container">
      <form action="" method="POST">
         <h3>Đăng nhập</h3>
         <input type="text" name="name" maxlength="20" required placeholder="Nhập tên người dùng của bạn" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" maxlength="20" required placeholder="Nhập mật khẩu của bạn" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Đăng nhập" name="submit" class="btn">
      </form>
   </section>

   <!-- admin login form section ends -->

</body>

</html>