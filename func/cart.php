<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);



// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
{
  http_response_code(405);
  echo json_encode(['status'=>'error','message'=>'Method not allowed']);
  exit;
}

//Kurangi item pada keranjang
if(isset($_POST['action']) && $_POST['action'] === 'decrease')
{
  include ($_SERVER['DOCUMENT_ROOT']. '/service/database.php');

  $id = filter_input(INPUT_POST, 'id_menu', FILTER_VALIDATE_INT);
  if(!$id)
  {
    echo json_encode(['status' => 'error', 'message' => 'ID Menu tidak valid!']);
    exit;
  }

  if(isset($_SESSION['cart'][$id]))
  {
    $_SESSION['cart'][$id]['jumlah']--;
    if($_SESSION['cart'][$id]['jumlah'] <= 0)
    {
      unset($_SESSION['cart'][$id]);
    }

    $stmt = $db->prepare("SELECT harga_Menu FROM menu WHERE id_Menu = ?");
    $stmt ->bind_param('i',$id);
    $stmt ->execute();
    $result = $stmt->get_result();
    $menu = $result ->fetch_assoc();
    $price =$menu['harga_Menu'];

    $total = 0;
    if(!empty($_SESSION['cart']))
    {
      $ids = array_keys($_SESSION['cart']);
      $in = implode(',', array_fill(0, count($ids), '?'));
      $tipe = str_repeat('i', count($ids));
      $stmt = $db -> prepare("SELECT id_Menu, harga_Menu FROM menu WHERE id_Menu IN ($in)");
      $stmt -> bind_param($tipe, ...$ids);
      $stmt -> execute();
      $result = $stmt->get_result();
      while ($row = $result -> fetch_assoc())
      {
        $qty = $_SESSION['cart'][$row['id_Menu']]['jumlah'];
        $harga = $_SESSION['cart'][$row['id_Menu']]['harga'];
        $total += $qty * $harga;
      }

    }
    echo json_encode
    (
      [
        'status' => 'success',
        'message' => 'Item berhasil dikurangi',
        'newQuantity' => $_SESSION['cart'][$id] ?? 0,
        'itemPrice'  => $price,
        'newTotal'  => $total
      ]);
      exit;
  }
  
  echo json_encode(['status' => 'error', 'message' => 'Item tidak ditemukan di keranjang']);
  exit;
}


//Kosongkan keranjang
if(isset($_POST['action']) && $_POST['action'])
{
  unset($_SESSION['cart']); //Hapus isi keranjang
  echo json_encode(['status' => 'success', 'message' => 'Keranjang telah dikosongkan!']);
  exit;
}

// Ambil id_menu
$id = filter_input(INPUT_POST, 'id_menu', FILTER_VALIDATE_INT);
if (!$id) 
{
  echo json_encode(['status'=>'error','message'=>'ID menu tidak valid']);
  exit;
}

include ($_SERVER['DOCUMENT_ROOT']. '/service/database.php');

// Simpan ke session
if (!isset($_SESSION['cart'])) 
{
  $_SESSION['cart'] = [];
}

if(!isset($_SESSION['cart'][$id]))
{
  $stmt = $db -> prepare("SELECT nama_Menu, harga_Menu FROM menu WHERE id_Menu = ?");
  $stmt -> bind_param('i', $id);
  $stmt ->execute();
  $result = $stmt->get_result();
  $menu = $result -> fetch_assoc();

  if (!$menu)
  {
    echo json_encode(['status' => 'error', 'message' => 'Menu tidak ditemukan']);
    exit;
  }

  $_SESSION['cart'][$id] =
  [
  'nama' => $menu['nama_Menu'],
  'harga' => $menu['harga_Menu'],
  'jumlah' => 1
  ];

} else
{
  $_SESSION['cart'][$id]['jumlah']++;
}

// Debug: tulis isi session ke log
error_log('SESSION.cart = '.print_r($_SESSION['cart'], true));

echo json_encode(['status'=>'success','message'=>'Item ditambahkan']);
