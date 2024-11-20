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
?>

<?php Template::headerAdmin("Dashboard"); ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Xin chào</h3>
         <p><?= $admin['name']; ?></p>
         <a href="update_profile.php" class="btn">Cập nhật thông tin</a>
      </div>

      <div class="box">
         <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_pendings->execute(['pending']);
         while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
            $total_pendings += $fetch_pendings['total_price'];
         }
         ?>
         <h3><?php echo currency_format($total_pendings) ?></h3>

         <p>Tổng tiền chờ xử lý</p>
         <a href="placed_orders.php" class="btn">Xem đơn hàng</a>
      </div>

      <div class="box">
         <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completes->execute(['completed']);
         while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
            $total_completes += $fetch_completes['total_price'];
         }
         ?>
         <h3><?php echo currency_format($total_completes) ?></h3>
         <p>Tổng đơn hoàn thành</p>
         <a href="placed_orders.php" class="btn">Xem đơn hàng</a>
      </div>

      <div class="box">
         <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $numbers_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $numbers_of_orders; ?></h3>
         <p>Tổng số đơn đặt hàng</p>
         <a href="placed_orders.php" class="btn">Xem đơn hàng</a>
      </div>

      <div class="box">
         <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $numbers_of_products = $select_products->rowCount();
         ?>
         <h3><?= $numbers_of_products; ?></h3>
         <p>Sản phẩm đã thêm</p>
         <a href="products.php" class="btn">Xem sản phẩm</a>
      </div>

      <div class="box">
         <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $numbers_of_users = $select_users->rowCount();
         ?>
         <h3><?= $numbers_of_users; ?></h3>
         <p>Tài khoản người dùng</p>
         <a href="users_accounts.php" class="btn">Xem người dùng</a>
      </div>

      <div class="box">
         <?php
         $select_admins = $conn->prepare("SELECT * FROM `admin`");
         $select_admins->execute();
         $numbers_of_admins = $select_admins->rowCount();
         ?>
         <h3><?= $numbers_of_admins; ?></h3>
         <p>Tài khoản</p>
         <a href="admin_accounts.php" class="btn">VIEW</a>
      </div>

      <div class="box">
         <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $numbers_of_messages = $select_messages->rowCount();
         ?>
         <h3><?= $numbers_of_messages; ?></h3>
         <p>Phản hồi mới</p>
         <a href="messages.php" class="btn">Xem phản hồi</a>
      </div>

   </div>

</section>

<!-- admin dashboard section ends -->

<?php Template::footerAdmin(); ?>