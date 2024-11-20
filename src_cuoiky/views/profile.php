<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/CartService.php";

if (isset($_SESSION['__user'])) {
    $user = $_SESSION['__user'];
    $user_id = $user['id']; // Giả sử bạn lưu user dưới dạng mảng hoặc đối tượng chứa ID
} else {
    header('location: ../index.php');
    exit; // Thoát sau khi chuyển hướng
}

?>

<?php Template::head("Thông tin cá nhân"); ?>

<?php Template::header(); ?>

<section class="user-details">

    <div class="user">
        <img src="../public/assets/imgs/user-icon.png" alt="">
        <p><i class="fas fa-user"></i><span><span><?= $user['name']; ?></span></span></p>
        <p><i class="fas fa-phone"></i><span><?= $user['number']; ?></span></p>
        <p><i class="fas fa-envelope"></i><span><?= $user['email']; ?></span></p>
        <a href="update_profile.php" class="btn">Cập nhật thông tin</a>
        <p class="address"><i class="fas fa-map-marker-alt"></i><span><?php if ($user['address'] == '') {
                                                                            echo 'Vui lòng nhập địa chỉ';
                                                                        } else {
                                                                            echo $user['address'];
                                                                        } ?></span></p>
        <a href="update_address.php" class="btn">Cập nhật địa chỉ</a>
    </div>

</section>

<?php Template::footer(); ?>

<?php Template::foot(); ?>