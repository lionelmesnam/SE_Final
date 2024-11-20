<?php
require_once "../core/Template.php";
require_once "../controller/ProductService.php";
require_once "../db/config.php";
require_once "../controller/CartService.php";

$products = ProductService::find_all();

$user = Session::get_session('__user');

if (isset($_POST['add_to_cart'])) {

    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (!$user) {
        header('location: login.php');
        exit();
    } else {
        // Lọc và lấy dữ liệu từ form
        $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_STRING);
        $pid = filter_var($_POST['id'], FILTER_SANITIZE_STRING); // Giả sử 'pid' là ID sản phẩm
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
        $image = filter_var($_POST['image'], FILTER_SANITIZE_STRING);
        $qty = filter_var($_POST['qty'], FILTER_SANITIZE_NUMBER_INT);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $check_cart = CartService::getCheckCart($pid, $user_id); // Giả sử `getCheckCart` kiểm tra cả theo `pid` và `user_id`

        if ($check_cart) {
            // Nếu sản phẩm đã có trong giỏ hàng, cộng thêm số lượng
            $new_qty = $check_cart['quantity'] + $qty;
            $update_cart = CartService::updateCartQuantity($pid, $user_id, $new_qty);

            if ($update_cart) {
                $message[] = 'Đã cập nhật số lượng sản phẩm trong giỏ hàng!';
            } else {
                $message[] = 'Có lỗi xảy ra khi cập nhật giỏ hàng!';
            }
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            $data = [
                'user_id' => $user_id,
                'pid' => $pid, // ID sản phẩm
                'name' => $name,
                'price' => $price,
                'quantity' => $qty,
                'image' => $image
            ];

            // Sử dụng hàm db_insert để chèn dữ liệu
            $insert_id = CartService::create($data);

            if ($insert_id) {
                $message[] = 'Đã thêm vào giỏ hàng!';
            } else {
                $message[] = 'Có lỗi xảy ra khi thêm vào giỏ hàng!';
            }
        }
    }
}

?>

<?php Template::head("Tìm kiếm sản phẩm"); ?>

<?php Template::header(); ?>

<!-- search form section starts  -->

<section class="search-form">
    <form method="post" action="">
        <input type="text" name="search_box" placeholder="Tên sản phẩm..." class="box">
        <button type="submit" name="search_btn" class="fas fa-search"></button>
    </form>
</section>

<section class="products" style="min-height: 100vh; padding-top:0;">

    <div class="box-container">

        <?php
        if (isset($_POST['search_box']) || isset($_POST['search_btn'])) {
            $search_box = '%' . filter_var($_POST['search_box'], FILTER_SANITIZE_STRING) . '%'; // Sử dụng LIKE với wildcard %

            // Sử dụng prepared statement để tránh SQL Injection
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ?");
            $select_products->execute([$search_box]);

            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
                    <form action="" method="post" class="box">
                        <input type="hidden" name="id" value="<?= $fetch_products['id']; ?>">
                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                        <a href="quick_view.php?id=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
                        <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
                        <img src="../public/uploads/<?= $fetch_products['image']; ?>" alt="">
                        <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
                        <div class="name"><?= $fetch_products['name']; ?></div>
                        <div class="flex">
                            <div class="price"><span>$</span><?= $fetch_products['price']; ?></div>
                            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                        </div>
                    </form>
        <?php
                }
            } else {
                // Thông báo khi không có sản phẩm nào được tìm thấy
                echo '<p class="empty">Không tìm thấy sản phẩm nào khớp với tìm kiếm của bạn!</p>';
            }
        }
        ?>

    </div>

</section>


<?php Template::footer(); ?>

<?php Template::foot(); ?>