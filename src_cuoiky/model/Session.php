<?php

class Session
{
  const FLAT_SUCCESS = "__flat_success";

  static public function set_session($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  static public function get_session($key, $default_value = null)
  {
    return $_SESSION[$key] ?? $default_value;
  }

  static public function get_user()
  {
    return self::get_session("__user");
  }

  static public function is_admin()
  {
    $user = self::get_session("__user");

    if ($user['user_role'] != 'admin') {
      redirect("/");
    }

    return $user;
  }
}
