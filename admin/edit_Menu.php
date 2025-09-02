<?php
include('../service/database.php');

// Ambil data dari URL
$id = $_GET['id'] ?? '';

// Query untuk mendapatkan data menu berdasarkan id
$result = mysqli_query($db, "SELECT * FROM menu WHERE id_Menu = '$id'");
$menu = mysqli_fetch_assoc($result);
$gambarLama = $menu['gamb_Menu']; // Ambil gambar lama dari database

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $nama_menu = htmlspecialchars($_POST['nama_Menu']);
    $kategori = htmlspecialchars($_POST['katg_Menu']);
    $harga = htmlspecialchars($_POST['harga_Menu']);

    $targetDir = "../img/";

    // Fungsi Upload Gambar Baru
    function upload()
    {
        global $targetDir;

        if (!isset($_FILES['gamb_Menu']) || $_FILES['gamb_Menu']['error'] === UPLOAD_ERR_NO_FILE) 
        {
            return false; // Jika tidak ada file baru, return false
        }

        $namaFile = $_FILES['gamb_Menu']['name'];
        $ukFile = $_FILES['gamb_Menu']['size'];
        $error = $_FILES['gamb_Menu']['error'];
        $tmpName = $_FILES['gamb_Menu']['tmp_name'];

        if ($error !== 0) 
        {
            echo "<script>alert('Terjadi kesalahan saat upload!');</script>";
            return false;
        }

        // Validasi ekstensi file
        $ekstenBenar = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstenGambar = strtolower(pathinfo(trim($namaFile), PATHINFO_EXTENSION));

        if (!in_array($ekstenGambar, $ekstenBenar)) 
        {
            echo "<script>alert('File bukan gambar!');</script>";
            return false;
        }

        // Cek ukuran gambar (maksimal 2MB)
        if ($ukFile > 2000000) 
        {
            echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
            return false;
        }

        // Generate nama unik untuk file
        $namaFileBaru = uniqid() . "." . $ekstenGambar;
        move_uploaded_file($tmpName, $targetDir . $namaFileBaru);
        return $namaFileBaru;
    }

    // Cek apakah ada gambar baru yang diupload
    $gambarBaru = upload();

    // Jika ada gambar baru, hapus gambar lama
    if ($gambarBaru) 
    {
        if ($gambarLama && file_exists($targetDir . $gambarLama)) 
        {
            unlink($targetDir . $gambarLama); // Hapus gambar lama dari folder
        }
        $gambar = $gambarBaru; // Pakai gambar baru
    } else 
    {
        $gambar = $gambarLama; // Gunakan gambar lama jika tidak ada yang diunggah
    }

    // Query update data menu
    $sql_update = "UPDATE menu 
                   SET nama_Menu = '$nama_menu', 
                       gamb_Menu = '$gambar', 
                       katg_Menu = '$kategori', 
                       harga_Menu = '$harga' 
                   WHERE id_Menu = '$id'";

    if (mysqli_query($db, $sql_update)) 
    {
        echo "<script>
            alert('Menu berhasil diperbarui!');
            document.location.href = '../menu.php';
        </script>";
    } else 
    {
        echo "<script>
            alert('Gagal memperbarui menu!');
            document.location.href = '../menu.php';
        </script>";
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
            <h3 class="text-green-600 text-2xl font-bold mb-4">Edit Menu</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="nama_Menu" class="flex items-center text-base font-medium">Nama Menu</label>
                <input type="text" name="nama_Menu" value="<?= $menu['nama_Menu'] ?>" class="w-full p-2 border border-gray-300 rounded mb-4" required />

                <label for="gamb_Menu" class="flex items-center text-base font-medium">Gambar</label>
                <input type="file" name="gamb_Menu" class="w-full border border-gray-300 p-2 rounded mb-4 file:bg-green-600 file:text-white file:px-3 file:py-1 file:rounded-full file:cursor-pointer" />
                <!-- Menampilkan gambar lama jika ada -->
                <?php if ($gambarLama): ?>
                    <img src="../img/<?= $gambarLama ?>" alt="Gambar Lama" class="w-full h-32 object-cover rounded mb-4">
                <?php endif; ?>

                <label for="katg_Menu" class="flex items-center text-base font-medium">Kategori</label>
                <select name="katg_Menu" class="w-full border border-gray-300 p-2 rounded mb-4" required>
                    <option hidden>Pilih Kategori</option>
                    <option value="Snack" <?= $menu['katg_Menu'] == 'Snack' ? 'selected' : '' ?>>Snack</option>
                    <option value="Food" <?= $menu['katg_Menu'] == 'Food' ? 'selected' : '' ?>>Food</option>
                    <option value="Drink" <?= $menu['katg_Menu'] == 'Drink' ? 'selected' : '' ?>>Drink</option>
                </select>

                <label for="harga_Menu" class="flex items-center text-base font-medium">Harga</label>
                <input type="number" name="harga_Menu" value="<?= $menu['harga_Menu'] ?>" class="w-full p-2 border border-gray-300 rounded mb-4" required />

                <button type="submit" class="w-full py-2 mt-6 text-white bg-green-600 rounded hover:bg-green-500">Simpan</button>
            </form>
        </div>
    </div>
</body>

</html>