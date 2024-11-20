<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/CartService.php";

// Lấy tất cả các giỏ hàng
$carts = CartService::find_all();

if (isset($_SESSION['__user'])) {
    $user = $_SESSION['__user'];
    $user_id = $user['id']; // Giả sử bạn lưu user dưới dạng mảng hoặc đối tượng chứa ID
} else {
    header('location: ../index.php');
    exit; // Thoát sau khi chuyển hướng
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];

    // Xóa sản phẩm bằng CartService
    if (CartService::delete($cart_id)) {
        $message[] = 'Item đã được xóa!';
        // Làm mới lại trang sau khi xóa
        header("Location: cart.php");
        exit();
    } else {
        $message[] = 'Xóa không thành công!';
    }
}

if (isset($_POST['delete_all'])) {
    // Xóa sản phẩm bằng CartService
    if (CartService::deleteAllCart($user_id)) {
        $message[] = 'Item đã được xóa!';
        // Làm mới lại trang sau khi xóa
        header("Location: cart.php");
        exit();
    } else {
        $message[] = 'Xóa không thành công!';
    }
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    // Cập nhật số lượng trong cơ sở dữ liệu
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);

    $message[] = 'Số lượng đã được cập nhật';

    // Lấy lại tất cả các giỏ hàng sau khi cập nhật
    $carts = CartService::find_all();
}


$grand_total = 0;

?>

<?php Template::head("Giỏ hàng"); ?>
<?php Template::header(); ?>
<!-- header section ends -->

<div class="heading">
    <h3>Thông tin giỏ hàng</h3>
    <p><a href="<?= route("/index.php") ?>">Trang chủ</a> <span> / Giỏ hàng</span></p>
</div>

<!-- shopping cart section starts  -->

<section class="products">

    <h1 class="title">Đơn hàng của bạn</h1>

    <div class="box-container">

        <?php
        $cart_empty = true; // Biến kiểm tra nếu giỏ hàng trống
        foreach ($carts as $cart) {
            // Chỉ hiển thị sản phẩm của user hiện tại
            if ($cart['user_id'] == $user_id) {
                $cart_empty = false; // Giỏ hàng có sản phẩm

                // Hiển thị sản phẩm của user
                $sub_total = $cart['price'] * $cart['quantity'];
        ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="cart_id" value="<?= $cart['id']; ?>">
                    <a href="quick_view.php?id=<?= $cart['pid']; ?>" class="fas fa-eye"></a>
                    <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('Xóa sản phẩm này?');"></button>
                    <img src="<?= $cart['image']; ?>" alt="">
                    <div class="name"><?= $cart['name']; ?></div>
                    <div class="flex">
                        <div class="price"><?php echo currency_format($cart['price']); ?></div>
                        <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $cart['quantity']; ?>" maxlength="2">
                        <button type="submit" class="fas fa-edit" name="update_qty"></button>
                    </div>
                    <div class="sub-total"> Tổng chi phí :
                        <span>
                            <?php echo currency_format($sub_total); ?>
                        </span>
                    </div>
                </form>
        <?php
                $grand_total += $sub_total;
            }
        }

        if ($cart_empty) {
            echo '<p class="empty">Giỏ hàng bạn không có gì!</p>';
        }
        ?>

    </div>

    <div class="cart-total">
        <p>Tổng tiền : <span><?php echo currency_format($grand_total); ?></span></p>
        <a href="checkout.php" class="btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">Tiến hành thanh toán</a>
    </div>

    <div class="more-btn">
        <form action="" method="post">
            <button type="submit" class="delete-btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>" name="delete_all" onclick="return confirm('Bạn có chắc muốn xóa tất cả?');">Xóa tất cả</button>
        </form>
        <a href="./product.php" class="btn">Tiếp tục mua sắm</a>
    </div>

</section>

<!-- shopping cart section ends -->

<?php Template::footer(); ?>
<?php Template::foot(); ?>