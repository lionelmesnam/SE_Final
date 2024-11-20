<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!function_exists('route')) {
  function route($path)
  {
    return BASE_URL . $path;
  }
}

if (!function_exists('is_post')) {
  function is_post()
  {
    return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
  }
}

if (!function_exists('is_exists_post')) {
  function is_exists_post($name)
  {
    return isset($_POST[$name]);
  }
}

if (!function_exists('start_session')) {
  function start_session()
  {
    if (!isset($_SESSION)) {
      session_start();
      ob_start();
    }
  }
}

if (!function_exists('re_array_files')) {
  function re_array_files(&$file_post)
  {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
      foreach ($file_keys as $key) {
        $file_ary[$i][$key] = $file_post[$key][$i];
      }
    }

    return $file_ary;
  }
}

if (!function_exists('upload_image')) {
  function upload_image($file)
  {
    if ($file['error'] > 0) {
      echo "Có lỗi xảy ra khi upload file.";
      die;
    }

    $filename = $file['name'];

    $destination = '../../public/uploads/' . $filename;

    $location = $file["tmp_name"];

    if (!move_uploaded_file($location, $destination)) {
      echo "Có lỗi xảy ra khi upload file.";
      die;
    }

    return $file['name'];
  }
}

if (!function_exists('filter_value_empty')) {
  function filter_value_empty($data)
  {
    $result = [];

    foreach ($data as $key => $value) {
      if (!empty($value))
        $result[$key] = $value;
    }

    return $result;
  }
}

if (!function_exists('redirect')) {
  function redirect($path)
  {
    header('Location: ' . route($path));
    die();
  }
}

if (!function_exists('dd')) {
  function dd($value)
  {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
    die();
  }
}

if (!function_exists('format_date')) {
  function format_date($date, $format = 'Y/m/d H:i:s')
  {
    $date_output = date_create($date);
    return date_format($date_output, $format);
  }
}

if (!function_exists('format_price')) {
  function format_price($price)
  {
    return number_format($price, 0, '', ',') . ' VND'; // 1,000,000
  }
}

if (!function_exists('is_outdate')) {
  function is_outdate($date)
  {
    $current_datetime = new DateTime();
    $date = new DateTime($date);
    return $date < $current_datetime;
  }
}

if (!function_exists('go_back')) {
  function go_back()
  {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
  }
}
