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
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_order->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<?php Template::headerAdmin("Tài khoản người dùng"); ?>

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

<!-- user accounts section starts  -->

<section class="accounts">

   <h1 class="heading">Tài khoản người dùng</h1>

   <div class="box-container">

      <?php
      $select_account = $conn->prepare("SELECT * FROM `users`");
      $select_account->execute();
      if ($select_account->rowCount() > 0) {
         while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <p> user id : <span><?= $fetch_accounts['id']; ?></span> </p>
               <p> username : <span><?= $fetch_accounts['name']; ?></span> </p>
               <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Xóa tài khoản này?');">Xóa</a>
            </div>
      <?php
         }
      } else {
         echo '<p class="empty">Không có tài khoản người dùng!</p>';
      }
      ?>

   </div>

</section>

<!-- user accounts section ends -->

<?php Template::footerAdmin(); ?>