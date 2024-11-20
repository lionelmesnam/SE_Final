<?php
require_once "../core/Template.php";
require_once "../controller/BookingService.php";
require_once "../db/config.php";
require_once "../controller/CartService.php";

$orders = BookingService::find_all();

if (isset($_SESSION['__user'])) {
    $user = $_SESSION['__user'];
    $user_id = $user['id']; // Giả sử bạn lưu user dưới dạng mảng hoặc đối tượng chứa ID
} else {
    header('location: ../index.php');
    exit; // Thoát sau khi chuyển hướng
}

?>

<?php Template::head("Đặt hàng"); ?>

<?php Template::header(); ?>

<div class="heading">
    <h3>Đặt hàng</h3>
    <p><a href="<?= route("/index.php") ?>">Trang chủ</a> <span> / Đặt hàng</span></p>
</div>

<section class="orders">

    <h1 class="title">Đơn hàng của bạn</h1>

    <div class="box-container">

        <?php
        $order_history = true; // Biến kiểm tra nếu giỏ hàng trống
        foreach ($orders as $order) {
            // Chỉ hiển thị sản phẩm của user hiện tại
            if ($order['user_id'] == $user_id) {
                $order_history = false; // Giỏ hàng có sản phẩm

                // Hiển thị sản phẩm của user
        ?>
                <div class="box">
                    <p>Thời gian : <span><?= $order['placed_on']; ?></span></p>
                    <p>Tên khách hàng : <span><?= $order['name']; ?></span></p>
                    <p>Email : <span><?= $order['email']; ?></span></p>
                    <p>Số điện thoại : <span><?= $order['number']; ?></span></p>
                    <p>Địa chỉ : <span><?= $order['address']; ?></span></p>
                    <p>Hình thức thanh toán : <span><?= $order['method']; ?></span></p>
                    <p>Đơn hàng của bạn : <span><?= $order['total_products']; ?></span></p>
                    <p>Tổng giá : <span><?php echo currency_format($order['total_price']); ?></span></p>
                    <p> Trạng thái thanh toán : <span style="color:<?php if ($order['payment_status'] == 'pending') {
                                                                        echo 'red';
                                                                    } else {
                                                                        echo 'green';
                                                                    }; ?>"><?php
                                                                            if ($order['payment_status'] == 'pending') {
                                                                                echo "Chưa giải quyết";
                                                                            } else {
                                                                                echo "Hoàn thành";
                                                                            }
                                                                            ?></span> </p>
                </div>

        <?php
            }
        }
        if ($order_history) {
            echo '<p class="empty">Chưa có đơn đặt hàng!</p>';
        }
        ?>

    </div>

</section>

<?php Template::footer(); ?>

<?php Template::foot(); ?>