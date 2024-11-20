<?php
require_once "../core/Template.php";
require_once "../controller/ProductService.php";
require_once "../controller/CartService.php";

// lấy id từ trang product qua thông qua url
$id = $_GET['id'] ?? '';

if (empty($id)) {
    go_back();
}

//Lấy id của product để hiển thị dữ liệu chi tiết của product đó
$product = ProductService::find_by_id($id, true);
$error = '';

if (empty($product)) {
    go_back();
}

//khai báo đường dẫn ảnh trỏ đến đúng thư mục ảnh
$base_url = 'http://localhost/src_cuoiky/public/uploads/';
//để lấy ảnh từ thư mục upload trùng vs file ảnh trên db (chính là image) để hiển thị
$product_image = $base_url . $product['image'];

$user = Session::get_session('__user');

if (isset($_POST['add_to_cart'])) {

    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (!$user) {
        header('location:login.php');
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


<?php Template::head("Chi tiết sản phẩm"); ?>

<?php Template::header(); ?>

<section class="quick-view">

    <h1 class="title">Chi tiết sản phẩm</h1>

    <form action="" method="post" class="box">
        <input type="hidden" name="id" value="<?= $product['id']; ?>">
        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
        <input type="hidden" name="name" value="<?= $product['name']; ?>">
        <input type="hidden" name="price" value="<?= $product['price']; ?>">
        <input type="hidden" name="image" value="<?= $product['image']; ?>">
        <img src="../public/uploads/<?= $product['image']; ?>" alt="">
        <a href="category.php?category=<?= $product['category']; ?>" class="cat"><?= $product['category']; ?></a>
        <div class="name"><?= $product['name']; ?></div>
        <div class="flex">
            <div class="price"><?php echo currency_format($product['price']); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
        </div>
        <button type="submit" name="add_to_cart" class="cart-btn">Thêm vào giỏ hàng</button>
    </form>

</section>

<?php Template::footer(); ?>

<?php Template::foot(); ?>