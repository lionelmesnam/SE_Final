<?php

class BookingService
{
  static public function find_all()
  {
    $data = db_fetch_array('SELECT * FROM ' . Database::BOOKING);

    return $data;
  }

  static public function find_by_id($id, $is_get_info = false)
  {
    $sql = 'SELECT * FROM ' . Database::BOOKING . ' WHERE booking_id = ' . $id;

    if ($is_get_info) {
      $sql = 'SELECT * FROM ' . Database::BOOKING . ' b JOIN ' . Database::PRODUCT . ' t ON b.id = t.id WHERE booking_id = ' . $id;
    }

    $data = db_fetch_row($sql);
    return empty($data) ? null : $data;
  }

  static public function create($data)
  {
    return db_insert(Database::BOOKING, $data);
  }

  static public function update($data, $id)
  {
    db_update(Database::BOOKING, $data, 'booking_id = ' . $id);
    return true;
  }

  static public function delete($id)
  {
    return db_delete(Database::BOOKING, 'booking_id = ' . $id) == 1;
  }


  //admin
  public static function update_payment_status($order_id, $payment_status)
  {
    global $conn;
    try {
      $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
      $update_status->execute([$payment_status, $order_id]);
      return "Trạng thái thanh toán được cập nhật!";
    } catch (PDOException $e) {
      return "Lỗi: " . $e->getMessage();
    }
  }

  // Hàm xóa đơn hàng
  public static function delete_order($order_id)
  {
    global $conn;
    try {
      $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
      $delete_order->execute([$order_id]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }
}
