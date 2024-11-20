<?php
// require_once "./db/database.php";

if (isset($message)) {
  foreach ($message as $message) {
    echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
  }
}

$user = Session::get_session('__user');
$user_id = isset($user['id']) ? $user['id'] : null; // Kiểm tra nếu user có tồn tại

if ($user_id !== null) {
  $total_products = CartService::count_products_by_user($user_id);
} else {
  $total_products = 0; // Nếu không có user, giỏ hàng là 0 sản phẩm
}

?>

<header class="header">

  <section class="flex">

    <a href="<?= route("/index.php") ?>" class="logo"> MOBILE SHOP</a>

    <nav class="navbar">
      <a href="<?= route("/index.php") ?>">Trang chủ</a>
      <a href="<?= route("/views/about.php") ?>">Giới thiệu</a>
      <a href="<?= route("/views/product.php") ?>">Sản phẩm</a>
      <a href="<?= route("/views/orders.php") ?>">Đặt hàng</a>
      <a href="<?= route("/views/contact.php") ?>">Liên hệ</a>
    </nav>

    <div class="icons">
      <a href="<?= route("/views/search.php") ?>"><i class="fas fa-search"></i></a>
      <a href="<?= route("/views/cart.php") ?>"><i class="fas fa-shopping-cart"></i><span>(<?= $total_products; ?>)</span></a>
      <div id="user-btn" class="fas fa-user"></div>
      <div id="menu-btn" class="fas fa-bars"></div>
    </div>

    <div class="profile">

      <?php
      if (Session::get_session('__user')) {
        $user = Session::get_session('__user');
      ?>
        <p class="name"><?= $user['name']; ?></p>
        <div class="flex">
          <a href="<?= route("/views/profile.php") ?>" class="btn">Thông tin</a>
          <a href="<?= route("/views/logout.php") ?>" onclick="return confirm('Bạn có chắc muốn đăng xuất?');" class="delete-btn">Đăng xuất</a>
        </div>
        <p class="account">
          <!-- <a href="<?= route("/views/login.php") ?>">Đăng nhập</a> or
          <a href="<?= route("/views/register.php") ?>">Đăng ký</a> -->
        </p>
      <?php
      } else {
      ?>
        <p class="name">Vui lòng đăng nhập!</p>
        <a href="<?= route("/views/login.php") ?>" class="btn">Đăng nhập</a>
      <?php
      }
      ?>
    </div>

  </section>

</header>