<?php
require_once "../core/Template.php";
require_once "../db/config.php";
require_once "../controller/AdminService.php";

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['add_product'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../public/uploads/' . $image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if ($select_products->rowCount() > 0) {
      $message[] = 'Tên sản phẩm đã tồn tại!';
   } else {
      if ($image_size > 2000000) {
         $message[] = 'Kích thước hình ảnh không được quá 20 MB';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image]);

         $message[] = 'Thêm sản phẩm thành công!';
      }
   }
}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../public/uploads/' . $fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:products.php');
}
?>

<?php Template::headerAdmin("Sản phẩm"); ?>

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

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Thêm Sản phẩm</h3>
      <input type="text" required placeholder="Nhập tên sản phẩm" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Nhập giá sản phẩm" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Chọn Thể Loại -- </option>
         <option value="APPLE">APPLE</option>
         <option value="SAMSUNG">SAMSUNG</option>
         <option value="HUAWEI">HUAWEI</option>
         <option value="XIAOMI">XIAOMI</option>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <input type="submit" value="Thêm sản phẩm" name="add_product" class="btn">
   </form>

</section>

<!-- add products section ends -->

<!-- show products section starts  -->

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">

      <?php
      $show_products = $conn->prepare("SELECT * FROM `products`");
      $show_products->execute();
      if ($show_products->rowCount() > 0) {
         while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <img src="../public/uploads/<?= $fetch_products['image']; ?>" alt="">
               <div class="flex">
                  <div class="price"> <?php echo currency_format($fetch_products['price']); ?></div>

                  <div class="category"><?= $fetch_products['category']; ?></div>
               </div>
               <div class="name"><?= $fetch_products['name']; ?></div>
               <div class="flex-btn">
                  <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Sửa</a>
                  <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
               </div>
            </div>
      <?php
         }
      } else {
         echo '<p class="empty">Chưa có sản phẩm nào được thêm vào!</p>';
      }
      ?>

   </div>

</section>

<!-- show products section ends -->

<?php Template::footerAdmin(); ?>