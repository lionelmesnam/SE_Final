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

$message = [];

if (isset($_POST['submit'])) {
    // Lấy và lọc dữ liệu từ form
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);

    // Cập nhật tên nếu khác rỗng
    if (!empty($name)) {
        $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
        $update_name->execute([$name, $user_id]);
        $message[] = 'Đã cập nhật tên!';
    }

    // Cập nhật email nếu khác rỗng và không trùng
    if (!empty($email)) {
        $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND id != ?");
        $select_email->execute([$email, $user_id]);
        if ($select_email->rowCount() > 0) {
            $message[] = 'Email đã tồn tại!';
        } else {
            $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
            $update_email->execute([$email, $user_id]);
            $message[] = 'Đã cập nhật email!';
        }
    }

    // Cập nhật số điện thoại nếu khác rỗng và không trùng
    if (!empty($number)) {
        $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ? AND id != ?");
        $select_number->execute([$number, $user_id]);
        if ($select_number->rowCount() > 0) {
            $message[] = 'Số điện thoại đã tồn tại!';
        } else {
            $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
            $update_number->execute([$number, $user_id]);
            $message[] = 'Đã cập nhật số điện thoại!';
        }
    }

    // Cập nhật mật khẩu
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if (!empty($old_pass) && !empty($new_pass) && !empty($confirm_pass)) {
        $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
        $select_prev_pass->execute([$user_id]);
        $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
        $prev_pass_hashed = $fetch_prev_pass['password'];

        if (!password_verify($old_pass, $prev_pass_hashed)) {
            $message[] = 'Mật khẩu cũ không đúng!';
        } elseif ($new_pass !== $confirm_pass) {
            $message[] = 'Mật khẩu mới và xác nhận mật khẩu không khớp!';
        } else {
            $new_pass_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$new_pass_hashed, $user_id]);
            $message[] = 'Đã cập nhật mật khẩu!';
        }
    } elseif (!empty($new_pass) || !empty($confirm_pass)) {
        $message[] = 'Bạn phải nhập đầy đủ các trường mật khẩu!';
    }

    // Cập nhật lại thông tin người dùng trong session
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_user->execute([$user_id]);
    $_SESSION['__user'] = $select_user->fetch(PDO::FETCH_ASSOC);
}

?>

<?php Template::head("Cập nhật thông tin cá nhân"); ?>
<?php Template::header(); ?>

<section class="form-container update-form">
    <form action="" method="post">
        <h3>Cập nhật thông tin</h3>
        <input type="text" name="name" placeholder="<?= htmlspecialchars($_SESSION['__user']['name']); ?>" class="box" maxlength="50">
        <input type="email" name="email" placeholder="<?= htmlspecialchars($_SESSION['__user']['email']); ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="number" name="number" placeholder="<?= htmlspecialchars($_SESSION['__user']['number']); ?>" class="box" min="0" max="9999999999" maxlength="10">
        <input type="password" name="old_pass" placeholder="Nhập mật khẩu cũ" class="box" maxlength="50">
        <input type="password" name="new_pass" placeholder="Nhập mật khẩu mới" class="box" maxlength="50">
        <input type="password" name="confirm_pass" placeholder="Xác nhận mật khẩu" class="box" maxlength="50">
        <input type="submit" value="Lưu thông tin" name="submit" class="btn">
    </form>
</section>

<?php Template::footer(); ?>
<?php Template::foot(); ?>