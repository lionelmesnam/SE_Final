<?php

class CartService
{
  static public function find_all()
  {
    $data = db_fetch_array('SELECT * FROM ' . Database::CART);

    return $data;
  }

  static public function find_by_id($id)
  {
    $sql = 'SELECT * FROM ' . Database::CART . ' WHERE id = ' . $id;
    $data = db_fetch_row($sql);
    return empty($data) ? null : $data;
  }

  static public function find_by_id_user($user_id)
  {
    $sql = 'SELECT * FROM ' . Database::CART . ' WHERE user_id = ' . $user_id;
    $data = db_fetch_row($sql);
    return empty($data) ? null : $data;
  }

  static public function count_products_by_user($user_id)
  {
    $sql = "SELECT COUNT(*) as total_products FROM cart WHERE user_id = ?";
    // Truyền mảng tham số vào hàm db_fetch_row để bind giá trị cho prepared statement
    $result = db_fetch_row($sql, [$user_id]);
    return $result['total_products'] ?? 0;
  }

  static public function getCheckCart($id)
  {
    $sql = 'SELECT * FROM ' . Database::CART . ' WHERE pid = \'' . $id . '\'';
    $data = db_fetch_row($sql);
    return empty($data) ? null : $data;
  }

  static public function create($data)
  {
    db_insert(Database::CART, $data);
    return true;
  }

  static public function update($data, $id)
  {
    db_update(Database::CART, $data, 'id = ' . $id);
    return true;
  }

  static public function delete($id)
  {
    return db_delete(Database::CART, 'id = ' . $id) == 1;
  }

  static public function deleteAllCart($user_id)
  {
    return db_delete(Database::CART, 'user_id = ' . $user_id) >= 1;
  }

  // Hàm cập nhật số lượng sản phẩm trong giỏ hàng
  static public function updateCartQuantity($pid, $user_id, $new_qty)
  {
    global $conn; // Sử dụng kết nối PDO từ biến toàn cục

    // Chuẩn bị câu lệnh SQL để cập nhật số lượng
    $sql = 'UPDATE ' . Database::CART . ' SET quantity = :quantity WHERE pid = :pid AND user_id = :user_id';

    // Sử dụng prepare và execute với PDO để tránh lỗi
    $stmt = $conn->prepare($sql);

    // Thực thi truy vấn với các giá trị đã bind
    $stmt->execute([
      ':quantity' => $new_qty,
      ':pid' => $pid,
      ':user_id' => $user_id
    ]);

    return true;
  }
}
