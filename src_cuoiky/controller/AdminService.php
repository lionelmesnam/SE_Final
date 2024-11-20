<?php

class AdminService
{
  static public function find_all()
  {
    $data = db_fetch_array('SELECT * FROM ' . Database::ADMIN);

    return $data;
  }

  static public function find_by_id($id)
  {
    $sql = 'SELECT * FROM ' . Database::ADMIN . ' WHERE id = ' . $id;
    $data = db_fetch_row($sql);
    return empty($data) ? null : $data;
  }

  static public function create($data)
  {
    return db_insert(Database::ADMIN, $data);
  }

  static public function update($data, $id)
  {
    db_update(Database::ADMIN, $data, 'id = ' . $id);
    return true;
  }

  static public function delete($id)
  {
    return db_delete(Database::ADMIN, 'id = ' . $id) == 1;
  }

  // Hàm đăng nhập admin
  static public function login($name, $password)
  {
    global $conn;
    $sql = 'SELECT * FROM ' . Database::ADMIN . ' WHERE name = ? AND password = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $password]);

    // Kiểm tra xem có kết quả không
    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return false;
  }
}
