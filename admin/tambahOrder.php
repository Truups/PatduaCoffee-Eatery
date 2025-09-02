<?php
session_start();
header('Content-Type: application/json');
include('../service/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_Menu'])) 
{
    $id_Menu = $_POST['id_Menu'];

    // Ambil data menu dari database
    $query = "SELECT * FROM menu WHERE id_Menu = $id_Menu";
    $result = $db->query($query);
    $menu = $result->fetch_assoc();

    if (!isset($menu[$id_Menu])) 
    {
        echo json_encode(["status" => "error", "message" => "Menu tidak ditemukan"]);
        exit;
    }

    // Simpan ke session
    $_SESSION['cart'][] = [
        "id_Menu" => $id_Menu,
        "nama_Menu" => $menu[$id_Menu]["nama_Menu"],
        "harga_Menu" => $menu[$id_Menu]["harga_Menu"],
        "jumlah" => 1
    ];

    echo json_encode(["status" => "success", "message" => "Item berhasil ditambahkan"]);
    exit;
}


?>
