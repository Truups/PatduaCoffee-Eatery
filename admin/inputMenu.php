<?php
include('../service/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $nama_menu = htmlspecialchars($_POST['nama_Menu']);
    $kategori = htmlspecialchars($_POST['katg_Menu']);
    $harga = (int)$_POST['harga_Menu'];

    $targetDir = "../img/";

    // Fungsi Upload Gambar
    function upload()
    {
        global $targetDir;
        $namaFile = $_FILES['gamb_Menu']['name'];
        $ukFile = $_FILES['gamb_Menu']['size'];
        $error = $_FILES['gamb_Menu']['error'];
        $tmpName = $_FILES['gamb_Menu']['tmp_name'];

        // Cek apakah ada gambar yang diunggah
        if ($error === 4) 
        {
            echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
            return false;
        }
        
        // Cek hanya gambar yang boleh di upload
        $ekstenBenar = ['jpg', 'jpeg', 'png','webp'];
        $ekstenGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if(!in_array($ekstenGambar, $ekstenBenar))
        {
            echo "<script>
            alert('File yang anda upload bukan gambar!');
            document.location.href='/menu.php';
            </script>";
            return false; 
        }

        // Cek ukuran gambar
        if($ukFile > 2000000)
        {
            echo "<script>
            alert('Ukuran gambar terlalu besar!');
            document.location.href='/menu.php';
            </script>";
            return false; 
        }
        move_uploaded_file($tmpName, $targetDir . $namaFile);
        return $namaFile;

    } 

    $gambar = upload();
    if (!$gambar) {
        exit(); // Hentikan eksekusi jika upload gagal
    }

    $gambar = upload();
    if (!$gambar) {
        exit(); // Hentikan eksekusi jika upload gagal
    }

    // Kategori checker
    if(empty($kategori))
    {
        echo"<script>alert('Kategori harus dipilih!'); window.history.back();</script?";
        exit();
    }


    $sql_insert = "INSERT INTO menu (nama_Menu, gamb_Menu, katg_Menu, harga_Menu) VALUES ('$nama_menu', '$gambar', '$kategori', '$harga')";

    if ($db->query($sql_insert) === TRUE)
    {
        echo "<script>
            alert('Menu berhasil ditambahkan!'); 
            document.location.href='/menu.php';
            </script>";
    } else 
    {
        echo "Error: " . $sql_insert . "<br>" . $db->error;
    }
}
