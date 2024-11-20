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
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<?php Template::headerAdmin("Phản hồi người dùng"); ?>

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

<!-- messages section starts  -->

<section class="messages">

   <h1 class="heading">Phản hồi của người dùng</h1>

   <div class="box-container">

      <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      if ($select_messages->rowCount() > 0) {
         while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <p> Khách hàng : <span><?= $fetch_messages['name']; ?></span> </p>
               <p> Số điện thoại : <span><?= $fetch_messages['number']; ?></span> </p>
               <p> Email : <span><?= $fetch_messages['email']; ?></span> </p>
               <p> Lời nhắn : <span><?= $fetch_messages['message']; ?></span> </p>
               <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
            </div>
      <?php
         }
      } else {
         echo '<p class="empty">Chưa có lời phản hồi!</p>';
      }
      ?>

   </div>

</section>

<!-- messages section ends -->
<?php Template::footerAdmin(); ?>