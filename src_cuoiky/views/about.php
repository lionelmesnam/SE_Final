<?php
require_once "../core/Template.php";
require_once "../controller/CartService.php";

?>

<?php Template::head("Giới thiệu"); ?>

<!-- header section starts  -->
<?php Template::header(); ?>
<!-- header section ends -->

<div class="heading">
    <h3>ABOUT US </h3>
    <p><a href="<?= route("/index.php") ?>">Trang chủ</a> <span> / Giới thiệu</span></p>
</div>

<!-- about section starts  -->

<section class="about">

    <div class="row">

        <div class="image">
            <img src="../public/assets/imgs/Johnny.jpeg" alt="">
        </div>

        <div class="content">
            <h3>Hồ sơ của chúng tôi</h3>
            <p>Giao hàng toàn quốc, qúy khách hàng vui lòng thanh toán tiền trước, phí ship vui lòng thanh toán cho nhân viên bưu điện lúc nhận hàng. Miễn phí ship khu vực nội TPHCM</p>
            <a href="<?= route("/views/product.php") ?>" class="btn">Sản phẩm</a>
        </div>

    </div>

</section>

<!-- about section ends -->

<!-- steps section starts  -->

<section class="steps">

    <h1 class="title">Các bước mua hàng</h1>

    <div class="box-container">

        <div class="box">
            <img src="../public/assets/imgs/step-1.png" alt="">
            <h3>Chọn sản phẩm</h3>
            <p>Nhấn vào sản phẩm muốn mua, thêm vào giỏ hàng và điền thông tin thanh toán.</p>
        </div>

        <div class="box">
            <img src="../public/assets/imgs/step-2.png" alt="">
            <h3>Giao hàng nhanh</h3>
            <p>Giao hàng toàn quốc trong vòng 3 ngày, quý khách vui lòng thanh toán khi nhận hàng.</p>
        </div>

        <div class="box">
            <img src="../public/assets/imgs/5475166.png" alt="">
            <h3>Đập hộp thôi</h3>
            <p>Chúc quý khách sử dụng sản phẩm tốt lành, đừng quên giới thiệu cho bạn bè biết.</p>
        </div>

    </div>

</section>

<!-- steps section ends -->

<!-- reviews section starts  -->

<section class="reviews">

    <h1 class="title">Đội ngũ nhân viên</h1>

    <div class="swiper reviews-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide slide">
    
                <h3>NGUYỄN THÀNH LONG</h3>
                <p>QUẢN LÝ
                </p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <div class="swiper-slide slide">
                
                <h3>LÝ HƯNG LÂM</h3>
                <p>
                    NHÂN VIÊN BÁN HÀNG </p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <div class="swiper-slide slide">
         
                <h3>NGUYỄN NHẬT NAM</h3>
                <p>
                    NHÂN VIÊN BÁN HÀNG </p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>


        </div>

        <div class="swiper-pagination"></div>

    </div>

</section>

<!-- reviews section ends -->


<!-- footer section starts  -->
<?php Template::footer(); ?>
<!-- footer section ends -->

<?php Template::foot(); ?>