<?php

require_once realpath(dirname(__FILE__) . "/../db/connect.php");

class Template
{
  static public function head(string $title = '')
  {
    return include realpath(dirname(__FILE__) . '/../layouts/head.php');
  }
  static public function header()
  {
    $path = parse_url($_SERVER['REQUEST_URI'])['path'];

    // echo $path;

    return include realpath(dirname(__FILE__) . '/../layouts/header.php');
  }

  static public function banner_home($error = "")
  {
    return include realpath(dirname(__FILE__) . '/../layouts/banner_home.php');
  }

  static public function foot()
  {
    return include realpath(dirname(__FILE__) . '/../layouts/foot.php');
  }

  static public function footer()
  {
    return include realpath(dirname(__FILE__) . '/../layouts/footer.php');
  }

  //admin
  static public function headerAdmin(string $title = '')
  {
    // $path = parse_url($_SERVER['REQUEST_URI'])['path'];

    // return include realpath(dirname(__FILE__) . '/../layouts/admin_header.php');
    return include realpath(dirname(__FILE__) . '/../layouts/admin_header.php');
  }

  static public function footerAdmin()
  {
    $path = parse_url($_SERVER['REQUEST_URI'])['path'];

    // echo $path;

    return include realpath(dirname(__FILE__) . '/../layouts/admin_footer.php');
  }
}
