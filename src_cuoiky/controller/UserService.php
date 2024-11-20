<?php

class UserService
{
  static public function find_all()
  {
    $data = db_fetch_array('SELECT * FROM ' . Database::USER);

    return $data;
  }

  static public function find_by_id($id)
  {
    $sql = 'SELECT * FROM ' . Database::USER . ' WHERE id = ' . $id;
    $data = db_fetch_row($sql);
    return empty($data) ? null : $data;
  }

  static public function getUserByEmail($email)
  {
    $sql = 'SELECT * FROM ' . Database::USER . ' WHERE email = \'' . $email . '\'';
    $data = db_fetch_row($sql);
    return empty($data) ? null : $data;
  }


  static public function create($data)
  {
    return db_insert(Database::USER, $data);
  }

  static public function update($data, $id)
  {
    db_update(Database::USER, $data, 'id = ' . $id);
    return true;
  }

  static public function delete($id)
  {
    return db_delete(Database::USER, 'id = ' . $id) == 1;
  }
}
