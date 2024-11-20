<?php

class ProductService
{
  static public function find_all()
  {
    $data = db_fetch_array('SELECT * FROM ' . Database::PRODUCT);

    if (empty($data)) return [];

    $results = [];

    foreach ($data as $index => $row) {
      $row['image'] = route('/public/uploads/') . $row['image'];
      $results[$index] = $row;
    }
    return $results;
  }

  static public function find_by_id($id)
  {
    $sql = 'SELECT * FROM ' . Database::PRODUCT . ' WHERE id = ' . $id;

    $data = db_fetch_row($sql);
    return empty($data) ? null : $data;
  }

  static public function find_by_category($category)
  {
    // Lọc dữ liệu đầu vào để ngăn ngừa SQL injection
    $category = filter_var($category, FILTER_SANITIZE_STRING);

    // Viết lại câu truy vấn với dấu nháy đơn để bảo vệ chuỗi
    $sql = 'SELECT * FROM ' . Database::PRODUCT . ' WHERE category = "' . $category . '"';

    // Thực hiện truy vấn và lấy dữ liệu
    $data = db_fetch_row($sql);

    // Trả về null nếu không có dữ liệu
    return empty($data) ? null : $data;
  }


  static public function create($data)
  {
    db_insert(Database::PRODUCT, $data);
    return true;
  }

  static public function update($data, $id)
  {
    return db_update(Database::PRODUCT, $data, 'id = ' . $id) == 1;
  }

  static public function delete($id)
  {
    return db_delete(Database::PRODUCT, 'id = ' . $id) == 1;
  }
}
