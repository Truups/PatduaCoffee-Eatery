<?php
header('Content-Type: application/json');
include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

$idPel = $_POST['id_Pelanggan'] ?? null;
$idMenu = $_POST['id_Menu'] ?? null;
$rate = $_POST['rating'] ?? null;

if ($idPel && $idMenu && $rate) {
    $stmt = $db->prepare("INSERT INTO rating (id_Pelanggan, id_Menu, rating, tanggal_rating) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iii", $idPel, $idMenu, $rate);

    if ($stmt->execute()) 
    {
        echo json_encode(['status' => 'sukses']);
    } else 
    {
        echo json_encode(['status' => 'gagal', 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Data tidak lengkap.']);
}
