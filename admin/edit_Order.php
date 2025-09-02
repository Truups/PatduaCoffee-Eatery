<?php
include('../service/database.php');

// Ambil data dari URL
$id = $_GET['id'] ?? '';

// Query untuk mendapatkan data pelanggan berdasarkan id
$result = mysqli_query($db, "SELECT * FROM pesan WHERE id_Pesan = '$id'");
$pesan = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $jmpesan = htmlspecialchars($_POST['jumlah_Pesan']);
    $stpesan = htmlspecialchars($_POST['status_Pesan']);

    //Query data per menu dari database
    $qharga = "SELECT menu.harga_Menu FROM pesan
                INNER JOIN menu on pesan.id_Menu = menu.id_Menu
                WHERE pesan.id_Pesan = '$id'";
    $resharga = $db -> query($qharga);

    if ($resharga->num_rows > 0) 
    {
        $row = $resharga->fetch_assoc();
        $harga = $row['harga_Menu'];

        // Hitung total harga baru
        $total_harga = $jmpesan * $harga;

        // Update jumlah pesan dan total harga
        $sql_update = "UPDATE pesan 
                       SET jumlah_Pesan = '$jmpesan', 
                           total_Harga = '$total_harga', 
                           status_Pesan = '$stpesan' 
                       WHERE id_Pesan='$id'";

        if ($db->query($sql_update) === TRUE) {
            echo "<script>
                alert('Pesanan berhasil diperbarui!'); 
                document.location.href='../order.php';
            </script>";
        } else {
            echo "Error: " . $sql_update . "<br>" . $db->error;
        }
    } else {
        echo "<script>alert('Pesanan tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="flex items-center justify-center h-screen bg-gray-200">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96 text-center">
            <h3 class="text-green-600 text-2xl font-bold mb-4">Edit Pesanan</h3>
            <form action="" method="POST">
                <label for="jumlah_Pesan" class="flex items-center text-base font-medium">Jumlah Pesan</label>
                <input type="text" name="jumlah_Pesan" value="<?= $pesan['jumlah_Pesan'] ?>" class="w-full p-2 border border-gray-300 rounded mb-4" required />

                <label for="status_Pesan" class="flex items-center text-base font-medium">Status Pesanan</label>
                <select name="status_Pesan" class="w-full border border-gray-300 p-2 rounded mb-4" required onchange="changeColor()">
                    <option value="Pending" class="text-yellow-600 bg-yellow-100" <?= $pesan['status_Pesan'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Diproses" class="text-blue-600 bg-blue-100" <?= $pesan['status_Pesan'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="Selesai" class="text-green-600 bg-green-100"<?= $pesan['status_Pesan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="Dibatalkan" class="text-red-600 bg-red-100"<?= $pesan['status_Pesan'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>

                <button type="submit" class="w-full py-2 mt-6 text-white bg-green-600 rounded hover:bg-green-500">Simpan</button>
            </form>
        </div>
    </div>
</body>

</html>