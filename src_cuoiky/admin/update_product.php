<?php

require_once "../core/Template.php";
require_once "../db/config.php";

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['update'])) {

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $pid]);

   $message[] = 'Đã cập nhật sản phẩm';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../public/uploads/' . $image;

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'Kích thước hình ảnh không được quá 2 MB';
      } else {
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);

         // Kiểm tra xem tệp hình ảnh cũ có tồn tại không trước khi xóa
         if (file_exists('../public/uploads/' . $old_image)) {
            unlink('../public/uploads/' . $old_image);
         }

         $message[] = 'Cập nhật hình ảnh thành công';
      }
   }
}

?>

<?php Template::headerAdmin("Cập nhật sản phẩm"); ?>

<!-- add products section starts  -->

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

<!-- update product section starts  -->

<section class="update-product">

   <h1 class="heading">Cập nhật sản phẩm</h1>

   <?php
   $update_id = $_GET['update'];
   $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $show_products->execute([$update_id]);
   if ($show_products->rowCount() > 0) {
      while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
   ?>
         <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
            <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
            <img src="../public/uploads/<?= $fetch_products['image']; ?>" alt="">
            <span>Tên mới</span>
            <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
            <span>Giá mới</span>
            <input type="number" min="0" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
            <span>Chọn Thể Loại</span>
            <select name="category" class="box" required>
               <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
               <option value="APPLE">APPLE</option>
               <option value="SAMSUNG">SAMSUNG</option>
               <option value="HUAWEI">HUAWEI</option>
               <option value="XIAOMI">XIAOMI</option>>
            </select>
            <span>Hình ảnh mới</span>
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
            <div class="flex-btn">
               <input type="submit" value="Cập nhật" class="btn" name="update">
               <a href="products.php" class="option-btn">Trở về</a>
            </div>
         </form>
   <?php
      }
   } else {
      echo '<p class="empty">Chưa có sản phẩm được thêm!</p>';
   }
   ?>

</section>

<?php Template::footerAdmin(); ?>