<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/AdminService.php";
require_once "../controller/BookingService.php";

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Xử lý cập nhật trạng thái thanh toán
if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $message[] = BookingService::update_payment_status($order_id, $payment_status);
}

// Xử lý xóa đơn hàng
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_success = BookingService::delete_order($delete_id);
   if ($delete_success) {
      $message[] = "Đơn hàng đã được xóa thành công!";
   } else {
      $message[] = "Lỗi khi xóa đơn hàng!";
   }
   header('location:placed_orders.php');
}

?>

<?php Template::headerAdmin("Đơn hàng"); ?>

<!-- placed orders section starts  -->

<section class="placed-orders">

   <h1 class="heading">Các đơn đặt hàng</h1>

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

   <div class="box-container">

      <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if ($select_orders->rowCount() > 0) {
         while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p>
               <p> Đặt lúc : <span><?= $fetch_orders['placed_on']; ?></span> </p>
               <p> Họ và tên : <span><?= $fetch_orders['name']; ?></span> </p>
               <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
               <p> Số điện thoại : <span><?= $fetch_orders['number']; ?></span> </p>
               <p> Địa chỉ : <span><?= $fetch_orders['address']; ?></span> </p>
               <p> Tổng sản phẩm : <span><?= $fetch_orders['total_products']; ?></span> </p>
               <p> Tổng giá : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
               <p> Hình thức thanh toán : <span><?= $fetch_orders['method']; ?></span> </p>
               <form action="" method="POST">
                  <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                  <select name="payment_status" class="drop-down">
                     <option value="pending" <?= $fetch_orders['payment_status'] == 'pending' ? 'selected' : ''; ?>>Chưa giải quyết</option>
                     <option value="completed" <?= $fetch_orders['payment_status'] == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                  </select>
                  <div class="flex-btn">
                     <input type="submit" value="Cập nhật" class="btn" name="update_payment">
                     <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Xóa đơn hàng này?');">Xóa</a>
                  </div>
               </form>
            </div>
      <?php
         }
      } else {
         echo '<p class="empty">Chưa có đơn đặt hàng!</p>';
      }
      ?>

   </div>

</section>

<!-- placed orders section ends -->

<?php Template::footerAdmin(); ?>