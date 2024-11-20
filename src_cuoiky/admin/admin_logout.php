<?php

require_once "../db/connect.php";

session_start();
session_unset();
session_destroy();

header('location: admin_login.php');
