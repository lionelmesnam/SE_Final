<?php

require_once "../db/connect.php";

// Xóa session và chuyển hướng đến trang đăng nhập
unset($_SESSION['__user']);
redirect("/views/login.php");
