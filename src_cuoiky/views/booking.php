<?php
require_once "../core/Template.php";
require_once "../controller/ProductService.php";
require_once "../controller/BookingService.php";

$id = $_GET['id'] ?? '';
$user = Session::get_user();

if (empty($id)) {
  go_back();
}

$tour = ProductService::find_by_id($id, true);
$error = '';

if (empty($tour)) {
  go_back();
}

if (is_post() && is_exists_post('submit_booking')) {
  $booking_fullName = $_POST['fullName'] ?? '';
  $booking_phone = $_POST['phone'] ?? '';
  $booking_payment = $_POST['booking_payment'] ?? '';
  $total_price = $tour['tour_price'];

  $total_count_people = $tour['tour_count_people'] + 1;

  // Nếu số lượng người đã hết
  if ($tour['tour_count_people'] >= $tour['tour_totalPeople']) {
    $error = 'Số lượng đặt đã hết';
  } else {
    $data_booking = [
      'booking_date' => date('Y-m-d'),
      'booking_fullName' => $booking_fullName,
      'booking_phone' => $booking_phone,
      'total_price' => $total_price,
      'booking_payment' => $booking_payment,
      'id' => $id,
    ];

    if (isset($user)) {
      $data_booking['id'] = $user['id'];
    }

    // Tạo booking
    $booking_id = BookingService::create($data_booking);

    // Cập nhật số lương khách trong tour
    $data_tour_update = [
      'tour_count_people' => $total_count_people,
    ];

    ProductService::update($data_tour_update, $id);

    redirect("/success.php?booking_id=" . $booking_id);
  }
}

?>

<?php Template::head("Booking"); ?>

<!-- <div class="page-inner"> -->
<?php Template::header(); ?>

<header id="gtco-header" class="gtco-cover gtco-cover-sm" role="banner" style="background-image: url(./public/assets/images/img_bg_3.jpg)">
  <div class="overlay"></div>
  <div class="gtco-container">
    <div class="row">
      <div class="col-md-12 col-md-offset-0 text-left">
        <div class="row row-mt-15em">

          <div class="col-md-7 mt-text animate-box" data-animate-effect="fadeInUp">
            <span class="intro-text-small">Easy booking</span>
            <h1>Booking now</h1>
          </div>

        </div>

      </div>
    </div>
  </div>
</header>


<div class="gtco-section border-bottom">
  <div class="gtco-container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6 animate-box">
          <h3>Customer Information</h3>
          <?php if (!empty($error)) : ?>
            <p class='text-danger'><?= $error  ?></p>
          <?php endif; ?>
          <form action="" method="post">
            <div class="row form-group">
              <div class="col-md-12">
                <label class="sr-only" for="fullName">Full Name</label>
                <input type="text" required id="fullName" value="<?= isset($user['user_fullName']) ? $user['user_fullName'] : '' ?>" name="fullName" class="form-control" placeholder="Full name">
              </div>

            </div>

            <div class="row form-group">
              <div class="col-md-12">
                <label class="sr-only" for="phone">PhoneNumber</label>
                <input type="number" required name="phone" id="phone" value="<?= isset($user['user_phone']) ? $user['user_phone'] : '' ?>" class="form-control" minlength="10" placeholder="Phone number">
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="sr-only">Phương thức thanh toán</label>
                <div>
                  <input type="checkbox" name="booking_payment" value="offline"> Thanh toán tiền mặt
                </div>
                <div>
                  <input type="checkbox" name="booking_payment" value="online"> Thanh toán online
                </div>
              </div>
            </div>

            <div class="form-group">
              <input type="submit" value="Booking" name="submit_booking" class="btn btn-primary">
            </div>

          </form>
        </div>
        <div class="col-md-5 col-md-push-1 animate-box">

          <div class="price-box popular h-100" style="margin-top: 20px;">
            <div class="popular-text">Popular</div>

            <h2 class="pricing-plan"><?= $tour['tour_title'] ?></h2>

            <div class="price">
              <?= format_price($tour['tour_price']) ?>
            </div>

            <p><?= $tour['tour_content'] ?></p>

            <hr>

            <ul class="pricing-info">
              <li>Thông tin</li>
              <li>Điểm đến: <?= $tour['dest_name'] ?></li>
              <li>Số lượng hiện tại: <?= $tour['tour_count_people'] . '/' . $tour['tour_totalPeople'] . ' người' ?></li>
              <li>Thời gian khởi hành: <?= format_date($tour['tour_timeStart'], 'd/m/Y H:i:s') ?></li>
              <li>Thời gian kết thúc: <?= format_date($tour['tour_timeEnd'], 'd/m/Y H:i:s') ?></li>
            </ul>
          </div>
        </div>
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