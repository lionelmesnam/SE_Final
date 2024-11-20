<?php
require_once "../controller/AdminService.php";

$admin_id = $_SESSION['admin_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : '' ?></title>
    <link rel="shortcut icon" href="../public/assets/imgs/icon.png" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../public/assets/css/admin_style.css">

</head>

<body>

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

    <header class="header">

        <section class="flex">

            <a href="<?= route("/admin/dashboard.php") ?>" class="logo">DASH<span>BOARD</span></a>

            <nav class="navbar">
                <a href="<?= route("/admin/dashboard.php") ?>">Trang chủ</a>
                <a href="<?= route("/admin/products.php") ?>">Sản phẩm</a>
                <a href="<?= route("/admin/placed_orders.php") ?>">Đơn hàng</a>
                <a href="<?= route("/admin/admin_accounts.php") ?>">Admin</a>
                <a href="<?= route("/admin/users_accounts.php") ?>">Người dùng</a>
                <a href="<?= route("/admin/messages.php") ?>">Phản hồi</a>
            </nav>

            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="user-btn" class="fas fa-user"></div>
            </div>

            <div class="profile">
                <?php
                $admin = AdminService::find_by_id($admin_id);
                ?>
                <p><?= $admin['name']; ?></p>
                <a href="<?= route("/admin/update_profile.php") ?>" class="btn">Cập nhật thông tin</a>
                <div class="flex-btn">
                    <a href="<?= route("/admin/admin_login.php") ?>" class="option-btn">Đăng nhập</a>
                    <a href="<?= route("/admin/register_admin.php") ?>" class="option-btn">Đăng ký</a>
                </div>
                <a href="<?= route("/admin/admin_logout.php") ?>" onclick="return confirm('Bạn thực sự muốn đăng xuất?');" class="delete-btn">Đăng xuất</a>
            </div>

        </section>

    </header>