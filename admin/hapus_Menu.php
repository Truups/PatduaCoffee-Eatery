<?php
session_start();
include('../service/database.php');
$id = $_GET['id'] ?? '';

function delMen($id)
{
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $query = "DELETE FROM menu WHERE id_Menu = '$id'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

if (delMen($id) > 0) 
{
    echo "<script>alert('Menu berhasil dihapus!'); document.location.href = '../menu.php'; </script>";
} else 
{
    echo "<script>alert('Menu gagal dihapus!'); document.location.href = '../menu.php'; </script>";
}

?>