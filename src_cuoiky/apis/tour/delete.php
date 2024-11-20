<?php

require_once "../../db/connect.php";
require_once "../../model/ProductService.php";

$id = $_POST['id'] ?? '';

if (empty($id)) {
  echo json_encode(['error' => true, 'message' => 'Có Lỗi xảy ra!']);
  die();
}

$deleted = ProductService::delete($id);

if (!$deleted) {
  echo json_encode(['error' => true, 'message' => 'Không tìm thấy id!']);
  die();
}

echo json_encode(['error' => false, 'message' => 'Xóa thành công!']);

die();
