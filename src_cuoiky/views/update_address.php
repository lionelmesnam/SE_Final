<?php
session_start();
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/CartService.php";

if (isset($_SESSION['__user'])) {
    $user = $_SESSION['__user'];
    $user_id = $user['id'];
} else {
    header('location: ../index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $apartment_number = trim(filter_var($_POST['apartment_number'], FILTER_SANITIZE_STRING));
    $area = trim(filter_var($_POST['area'], FILTER_SANITIZE_STRING));
    $town = trim(filter_var($_POST['town'], FILTER_SANITIZE_STRING));
    $city = trim(filter_var($_POST['city'], FILTER_SANITIZE_STRING));
    $state = trim(filter_var($_POST['state'], FILTER_SANITIZE_STRING));
    $country = trim(filter_var($_POST['country'], FILTER_SANITIZE_STRING));
    $pin_code = trim(filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING)); // Chuyển sang kiểu chuỗi để không giới hạn số bắt đầu với 0

    // Gộp địa chỉ thành chuỗi
    $address = $apartment_number . ', ' . $area . ', ' . $town . ', ' . $city . ', ' . $state . ', ' . $country . ' - ' . $pin_code;
    $address = filter_var($address, FILTER_SANITIZE_STRING);

    // Cập nhật địa chỉ trong bảng users
    $update_address = $conn->prepare("UPDATE `users` SET address = :address WHERE id = :id");
    $update_address->bindParam(':address', $address);
    $update_address->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($update_address->execute()) {
        $message[] = 'Đã lưu địa chỉ.';

        // Thêm hoặc cập nhật địa chỉ trong bảng orders cho các đơn hàng chưa hoàn thành
        $update_orders = $conn->prepare("UPDATE `orders` SET address = :address WHERE user_id = :id AND payment_status = 'pending'");
        $update_orders->bindParam(':address', $address);
        $update_orders->bindParam(':id', $user_id, PDO::PARAM_INT);

        // if ($update_orders->execute()) {
        //     $message[] = 'Đã cập nhật địa chỉ trong bảng đơn hàng.';
        // } else {
        //     $message[] = 'Không thể cập nhật địa chỉ trong bảng đơn hàng.';
        // }
    } else {
        $message[] = 'Có lỗi xảy ra khi cập nhật địa chỉ trong bảng người dùng, vui lòng thử lại.';
    }
}

?>

<?php Template::head("Cập nhật địa chỉ"); ?>
<?php Template::header(); ?>

<section class="form-container">
    <?php if (!empty($message)): ?>
        <div class="message">
            <?php foreach ($message as $msg): ?>
                <p><?php echo htmlspecialchars($msg); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <h3>Địa chỉ của bạn</h3>
        <input type="text" class="box" placeholder="Số nhà" required name="apartment_number">
        <input type="text" class="box" placeholder="Đường" required name="area">
        <input type="text" class="box" placeholder="Phường" required name="town">
        <input type="text" class="box" placeholder="Quận" required name="city">
        <input type="text" class="box" placeholder="Thành Phố" required name="state">
        <input type="text" class="box" placeholder="Quốc gia" required name="country">
        <input type="text" class="box" placeholder="Mã bưu điện" required name="pin_code">
        <input type="submit" value="Lưu địa chỉ" name="submit" class="btn">
    </form>
</section>

<?php Template::footer(); ?>
<?php Template::foot(); ?>