<?php

$config = array(
  'hostname' => 'localhost',
  'username' => 'root',
  'password' => '',
  'database' => 'db',
  'port' => 3306,
);

if (!function_exists('currency_format')) {
  function currency_format($number, $suffix = 'đ')
  {
    if (!empty($number)) {
      return number_format($number, 0, ',', '.') . "<span>{$suffix}</span>"; // <span>đ</span>
    }
  }
}
