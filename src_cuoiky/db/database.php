<?php

// Hàm kết nối dữ liệu
function db_connect()
{
  global $conn;

  $db = func_get_arg(0); // Mảng chứa thông tin kết nối DB

  try {
    // Kết nối với cơ sở dữ liệu MySQL sử dụng PDO
    $dsn = "mysql:host={$db['hostname']};dbname={$db['database']};port={$db['port']};charset=utf8";
    $conn = new PDO($dsn, $db['username'], $db['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Thiết lập chế độ lỗi của PDO
  } catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
  }
}


//Thực thi chuổi truy vấn
function db_query($query_string)
{
  global $conn;

  try {
    $stmt = $conn->query($query_string);
    return $stmt;
  } catch (PDOException $e) {
    db_sql_error('Query Error', $query_string, $e->getMessage());
  }
}



// Lấy một dòng trong CSDL
function db_fetch_row($query_string, $params = [])
{
  global $conn;

  try {
    // Chuẩn bị câu truy vấn
    $stmt = $conn->prepare($query_string);

    // Thực thi câu truy vấn với các tham số đã bind
    $stmt->execute($params);

    // Lấy kết quả
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    db_sql_error('Query Error', $query_string, $e->getMessage());
  }
}



//Lấy một mảng trong CSDL
function db_fetch_array($query_string)
{
  global $conn;

  $stmt = db_query($query_string);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//Lấy số bản ghi
function db_num_rows($query_string)
{
  global $conn;

  $stmt = db_query($query_string);
  return $stmt->rowCount();
}


function db_insert($table, $data)
{
  global $conn;

  $fields = implode(", ", array_keys($data));
  $placeholders = implode(", ", array_fill(0, count($data), "?"));
  $query = "INSERT INTO `{$table}` ($fields) VALUES ($placeholders)";

  $stmt = $conn->prepare($query);
  $stmt->execute(array_values($data));

  return $conn->lastInsertId();
}


function db_update($table, $data, $where)
{
  global $conn;

  $fields = implode(" = ?, ", array_keys($data)) . " = ?";
  $query = "UPDATE `{$table}` SET $fields WHERE $where";

  $stmt = $conn->prepare($query);
  $stmt->execute(array_values($data));

  return $stmt->rowCount();
}


function db_delete($table, $where)
{
  global $conn;

  $query = "DELETE FROM `{$table}` WHERE $where";
  $stmt = $conn->prepare($query);
  $stmt->execute();

  return $stmt->rowCount();
}

function escape_string($str)
{
  global $conn;
  return $conn->quote($str);
}

// Hiển thị lỗi SQL

function db_sql_error($message, $query_string = "", $error_message = "")
{
  $sqlerror = "<table width='100%' border='1' cellpadding='0' cellspacing='0'>";
  $sqlerror .= "<tr><th colspan='2'>{$message}</th></tr>";
  $sqlerror .= ($query_string != "") ? "<tr><td nowrap> Query SQL</td><td nowrap>: " . $query_string . "</td></tr>\n" : "";
  $sqlerror .= "<tr><td nowrap> Error Message</td><td nowrap>: " . $error_message . "</td></tr>\n";
  $sqlerror .= "<tr><td nowrap> Date</td><td nowrap>: " . date("D, F j, Y H:i:s") . "</td></tr>\n";
  $sqlerror .= "</table>";
  echo $sqlerror;
  exit;
}
