<?php

// Sử dụng cho database chi cần require file này là sử dụng được
require_once(realpath(dirname(__FILE__) . "/../db/config.php"));
require_once(realpath(dirname(__FILE__) . "/../db/database.php"));
require_once(realpath(dirname(__FILE__) . "/../utils/function.php"));
require_once(realpath(dirname(__FILE__) . "/../model/Database.php"));
require_once(realpath(dirname(__FILE__) . "/../model/Session.php"));

db_connect($config);
start_session();

$array_request_uri = explode("/", $_SERVER['REQUEST_URI']);

define("BASE_URL", $_SERVER['REQUEST_SCHEME'] . ":" . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . $_SERVER['SERVER_NAME'] . DIRECTORY_SEPARATOR . $array_request_uri[1]);

define("PUBLIC_URL_ADMIN", BASE_URL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR);
