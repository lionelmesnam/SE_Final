<?php
require_once "./core/Template.php";
require_once "./controller/BookingService.php";

$booking_id = $_GET['booking_id'] ?? '';

if (empty($booking_id)) {
  redirect("/");
}

$booking = BookingService::find_by_id($booking_id, true);

// dd($booking);

?>

<?php Template::head("Booking Successfully"); ?>

<!-- <div class="page-inner"> -->
<?php Template::header(); ?>

<header id="gtco-header" class="gtco-cover gtco-cover-sm" role="banner" style="background-image: url(./public/assets/images/img_6.jpg)">
  <div class="overlay"></div>
  <div class="gtco-container">
    <div class="row">
      <div class="col-md-12 col-md-offset-0 text-center">
        <div class="row row-mt-15em">

          <div class="col-md-12 mt-text animate-box" data-animate-effect="fadeInUp">
            <h1>Booking Successfully</h1>
          </div>

        </div>

      </div>
    </div>
  </div>
</header>

<div class="gtco-section">
  <div class="gtco-container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center gtco-heading">
        <div class="price-box popular h-100">
          <div class="popular-text">Popular</div>

          <h2 class="pricing-plan"><?= $booking['tour_title'] ?></h2>

          <div class="price">
            <?= format_price($booking['tour_price']) ?>
          </div>

          <p><?= $booking['tour_content'] ?></p>

          <hr>

          <ul class="pricing-info">
            <li>Thông tin</li>

            <li>Khách hàng: <?= $booking['booking_fullName'] ?></li>
            <li>Số điện thoại: <?= $booking['booking_phone'] ?></li>
            <li>Số lượng hiện tại: <?= $booking['tour_count_people'] . '/' . $booking['tour_totalPeople'] . ' người' ?></li>
            <li>Phương thức thanh toán: <?= $booking['booking_payment'] ?></li>
            <li>Thời gian khởi hành: <?= format_date($booking['tour_timeStart'], 'd/m/Y H:i:s') ?></li>
            <li>Thời gian khởi hành: <?= format_date($booking['tour_timeEnd'], 'd/m/Y H:i:s') ?></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>



<div id="gtco-subscribe">
  <div class="gtco-container">
    <div class="row animate-box">
      <div class="col-md-8 col-md-offset-2 text-center gtco-heading">
        <h2>Subscribe</h2>
        <p>Be the first to know about the new templates.</p>
      </div>
    </div>
    <div class="row animate-box">
      <div class="col-md-8 col-md-offset-2">
        <form class="form-inline">
          <div class="col-md-6 col-sm-6">
            <div class="form-group">
              <label for="email" class="sr-only">Email</label>
              <input type="email" class="form-control" id="email" placeholder="Your Email">
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <button type="submit" class="btn btn-default btn-block">Subscribe</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<footer id="gtco-footer" role="contentinfo">
  <div class="gtco-container">
    <div class="row row-p	b-md">

      <div class="col-md-4">
        <div class="gtco-widget">
          <h3>About Us</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore eos molestias quod sint ipsum possimus
            temporibus officia iste perspiciatis consectetur in fugiat repudiandae cum. Totam cupiditate nostrum ut
            neque ab?</p>
        </div>
      </div>

      <div class="col-md-2 col-md-push-1">
        <div class="gtco-widget">
          <h3>Điểm đến</h3>
          <ul class="gtco-footer-links">
            <li><a href="#">Europe</a></li>
            <li><a href="#">Australia</a></li>
            <li><a href="#">Asia</a></li>
            <li><a href="#">Canada</a></li>
            <li><a href="#">Dubai</a></li>
          </ul>
        </div>
      </div>

      <div class="col-md-2 col-md-push-1">
        <div class="gtco-widget">
          <h3>Hotels</h3>
          <ul class="gtco-footer-links">
            <li><a href="#">Luxe Hotel</a></li>
            <li><a href="#">Italy 5 Star hotel</a></li>
            <li><a href="#">Dubai Hotel</a></li>
            <li><a href="#">Deluxe Hotel</a></li>
            <li><a href="#">BoraBora Hotel</a></li>
          </ul>
        </div>
      </div>

      <div class="col-md-3 col-md-push-1">
        <div class="gtco-widget">
          <h3>Get In Touch</h3>
          <ul class="gtco-quick-contact">
            <li><a href="#"><i class="icon-phone"></i> +1 234 567 890</a></li>
            <li><a href="#"><i class="icon-mail2"></i> info@freehtml5.co</a></li>
            <li><a href="#"><i class="icon-chat"></i> Live Chat</a></li>
          </ul>
        </div>
      </div>

    </div>

    <div class="row copyright">
      <div class="col-md-12">
        <p class="pull-left">
          <small class="block">&copy; 2016 Free HTML5. All Rights Reserved.</small>
          <small class="block">Designed by <a href="https://freehtml5.co/" target="_blank">FreeHTML5.co</a> Demo
            Images: <a href="http://unsplash.com/" target="_blank">Unsplash</a></small>
        </p>
        <p class="pull-right">
        <ul class="gtco-social-icons pull-right">
          <li><a href="#"><i class="icon-twitter"></i></a></li>
          <li><a href="#"><i class="icon-facebook"></i></a></li>
          <li><a href="#"><i class="icon-linkedin"></i></a></li>
          <li><a href="#"><i class="icon-dribbble"></i></a></li>
        </ul>
        </p>
      </div>
    </div>

  </div>
</footer>
<!-- </div> -->

</div>

<div class="gototop js-top">
  <a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
</div>

<!-- jQuery -->
<script src="./public/assets/js/jquery.min.js"></script>
<!-- jQuery Easing -->
<script src="./public/assets/js/jquery.easing.1.3.js"></script>
<!-- Bootstrap -->
<script src="./public/assets/js/bootstrap.min.js"></script>
<!-- Waypoints -->
<script src="./public/assets/js/jquery.waypoints.min.js"></script>
<!-- Carousel -->
<script src="./public/assets/js/owl.carousel.min.js"></script>
<!-- countTo -->
<script src="./public/assets/js/jquery.countTo.js"></script>

<!-- Stellar Parallax -->
<script src="./public/assets/js/jquery.stellar.min.js"></script>

<!-- Magnific Popup -->
<script src="./public/assets/js/jquery.magnific-popup.min.js"></script>
<script src="./public/assets/js/magnific-popup-options.js"></script>

<!-- Datepicker -->
<script src="./public/assets/js/bootstrap-datepicker.min.js"></script>


<!-- Main -->
<script src="./public/assets/js/main.js"></script>

</body>

</html>