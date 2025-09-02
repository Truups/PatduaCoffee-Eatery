<?php
session_start();
include('../service/database.php');
$id = $_GET['id'] ?? '';

function delOrd($id)
{
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $query = "DELETE FROM pesan WHERE id_Pesan = '$id'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

if (delOrd($id) > 0) 
{
    echo "<script>alert('Pesanan berhasil dihapus!'); document.location.href = '../order.php'; </script>";
} else 
{
    echo "<script>alert('Pesanan gagal dihapus!'); document.location.href = '../order.php'; </script>";
}

?>