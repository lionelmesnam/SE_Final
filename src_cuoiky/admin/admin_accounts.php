<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/AdminService.php";

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location: admin_accounts.php');
}

?>

<?php Template::headerAdmin("Đội ngũ quản lý"); ?>

<!-- add products section starts  -->

<?php
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

<!-- admins accounts section starts  -->

<section class="accounts">

   <h1 class="heading">Đội ngũ quản lý</h1>

   <div class="box-container">

      <div class="box">
         <p>ACCOUNT</p>
         <a href="register_admin.php" class="option-btn">Đăng ký</a>
      </div>

      <?php
      $select_account = $conn->prepare("SELECT * FROM `admin`");
      $select_account->execute();
      if ($select_account->rowCount() > 0) {
         while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <p> admin id : <span><?= $fetch_accounts['id']; ?></span> </p>
               <p> username : <span><?= $fetch_accounts['name']; ?></span> </p>
               <div class="flex-btn">
                  <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                  <?php
                  if ($fetch_accounts['id'] == $admin_id) {
                     echo '<a href="update_profile.php" class="option-btn">Sửa</a>';
                  }
                  ?>
               </div>
            </div>
      <?php
         }
      } else {
         echo '<p class="empty">Không có tài khoản!</p>';
      }
      ?>

   </div>

</section>

<?php Template::footerAdmin(); ?>