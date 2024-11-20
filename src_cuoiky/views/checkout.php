<?php
session_start();
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/CartService.php";

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['__user'])) {
    $user = $_SESSION['__user'];
    $user_id = $user['id'];

    // Lấy thông tin người dùng mới nhất từ cơ sở dữ liệu
    $user_query = $conn->prepare("SELECT name, number, email, address FROM `users` WHERE id = ?");
    $user_query->execute([$user_id]);
    $user_data = $user_query->fetch(PDO::FETCH_ASSOC);

    // Cập nhật lại thông tin trong session và biến hiển thị
    if ($user_data) {
        $_SESSION['__user'] = array_merge($user, $user_data); // Cập nhật session với dữ liệu mới
        $name = $user_data['name'];
        $number = $user_data['number'];
        $email = $user_data['email'];
        $address = $user_data['address'];
    }
} else {
    header('Location: ../index.php');
    exit;
}

$message = []; // Khởi tạo biến message để chứa thông báo

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $number = filter_var(trim($_POST['number']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $method = filter_var(trim($_POST['method']), FILTER_SANITIZE_STRING);
    $address = filter_var(trim($_POST['address']), FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    // Kiểm tra xem địa chỉ có được nhập hay không
    if (empty($address)) {
        $message[] = 'Vui lòng thêm địa chỉ của bạn!';
    } else {
        // Kiểm tra giỏ hàng
        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $check_cart->execute([$user_id]);

        if ($check_cart->rowCount() > 0) {
            // Nếu có mặt hàng trong giỏ hàng, thực hiện chèn đơn hàng
            $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

            // Thông báo đặt hàng thành công
            $message[] = 'Đặt hàng thành công!';

            // Xóa giỏ hàng sau khi đặt hàng
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);
        } else {
            // Nếu giỏ hàng rỗng, không thực hiện đặt hàng
            $message[] = 'Giỏ hàng của bạn không có gì!';
        }
    }
}

?>

<?php Template::head("Thanh toán"); ?>
<?php Template::header(); ?>

<div class="heading">
    <h3>Thủ tục thanh toán</h3>
    <p><a href="<?= route("/index.php") ?>">Trang chủ</a> <span> / Thanh toán</span></p>
</div>

<section class="checkout">
    <h1 class="title">Tổng đơn hàng</h1>

    <form action="" method="post">
        <div class="cart-items">
            <h3>Các mặt hàng trong giỏ hàng</h3>
            <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);

            if ($select_cart->rowCount() > 0) {
                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $cart_items[] = $fetch_cart['name'] . ' (' . currency_format($fetch_cart['price']) . ' x ' . $fetch_cart['quantity'] . ')';
                    $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
                    <p><span class="name"><?= htmlspecialchars($fetch_cart['name']); ?></span><span class="price"><?= currency_format($fetch_cart['price']); ?> x <?= $fetch_cart['quantity']; ?></span></p>
            <?php
                }
            } else {
                echo '<p class="empty">Giỏ hàng của bạn không có gì!</p>';
            }
            ?>
            <p class="grand-total"><span class="name">Tổng cộng :</span><span class="price"><?= currency_format($grand_total); ?></span></p>
            <a href="cart.php" class="btn">Xem giỏ hàng</a>
        </div>

        <input type="hidden" name="total_products" value="<?= htmlspecialchars(implode(", ", $cart_items)); ?>">
        <input type="hidden" name="total_price" value="<?= htmlspecialchars($grand_total); ?>">
        <input type="hidden" name="name" value="<?= htmlspecialchars($name); ?>">
        <input type="hidden" name="number" value="<?= htmlspecialchars($number); ?>">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email); ?>">
        <input type="hidden" name="address" value="<?= htmlspecialchars($address); ?>">

        <div class="user-info">
            <h3>Thông tin của bạn</h3>
            <p><i class="fas fa-user"></i><span><?= htmlspecialchars($name); ?></span></p>
            <p><i class="fas fa-phone"></i><span><?= htmlspecialchars($number); ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= htmlspecialchars($email); ?></span></p>
            <a href="update_profile.php" class="btn">Cập nhật thông tin</a>
            <h3>Địa chỉ giao hàng</h3>
            <p><i class="fas fa-map-marker-alt"></i><span><?= htmlspecialchars($address) ?: 'Vui lòng nhập địa chỉ của bạn!'; ?></span></p>
            <a href="update_address.php" class="btn">Cập nhật địa chỉ</a>
            <select name="method" class="box" required>
                <option value="" disabled selected>Chọn phương thức thanh toán --</option>
                <option value="COD">COD</option>
                
            </select>
            <input type="submit" value="Đặt Hàng" class="btn <?= empty($address) ? 'disabled' : ''; ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit" <?= empty($address) ? 'disabled' : ''; ?>>
        </div>
    </form>
</section>

<?php Template::footer(); ?>
<?php Template::foot(); ?>