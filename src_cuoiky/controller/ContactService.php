<?php

class ContactService
{
  static public function find_all()
  {
    $data = db_fetch_array('SELECT * FROM ' . Database::CONTACT);

    return $data;
  }

  static public function find_by_id($id)
  {
    $data = db_fetch_row('SELECT * FROM ' . Database::CONTACT . ' WHERE id = ' . $id);
    return empty($data) ? null : $data;
  }

  static public function create($data)
  {
    db_insert(Database::CONTACT, $data);
    return true;
  }
  static public function update($data, $id)
  {
    db_update(Database::CONTACT, $data, 'id = ' . $id);
    return true;
  }

  static public function delete($id)
  {
    return db_delete(Database::CONTACT, 'id = ' . $id) == 1;
  }
}
