<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];

    // Validasi server untuk form
    $errors = [];

    if (empty(trim($nama))) 
    {
        $errors[] = "Nama harus diisi.";
    }

if (empty(trim($telepon))) 
    {
        $errors[] = "Nomor telepon harus diisi.";
    }

    if (empty(trim($email))) 
    {
        $errors[] = "Email harus diisi.";
    }


    if(!preg_match('/^\d{10,13}$/', $telepon))
    {
        $errors[] = "Nomor telepon harus terdiri dari 10 sampai 13 digit angka.";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $errors[] = "Email tidak valid.";
    }

    if(!empty($errors))
    {
        $_SESSION['checkout_errors'] = $errors;
        header("Location: checkout.php");
        exit;
    }

   $query = $db->query("SELECT MAX(CAST(RIGHT(kode_Pelanggan, 4) AS UNSIGNED)) AS maxKode FROM pelanggan");
    $lj = 1;
    if($row = $query->fetch_assoc())
    {
        $lj = intval($row['maxKode']) +1;
    }
    $kodePel= 'CUST_' . str_pad($lj, 4, '0', STR_PAD_LEFT);

    //Menyimpan pelanggan
    $stmt = $db->prepare("INSERT INTO pelanggan (nama_Pelanggan, telp_Pelanggan, email_Pelanggan, kode_Pelanggan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $telepon, $email, $kodePel);
    $stmt->execute();
    $idPelanggan = $stmt->insert_id;
    $stmt->close();

    //Kode Order
    $prefix = "ORD-2025-";
    $query = $db->query("SELECT MAX(RIGHT(kode_Order,3)) as maxKode FROM pesan");
    $kodeBaru = 1;
    if ($row = $query->fetch_assoc()) 
    {
        $kodeBaru = intval($row['maxKode']) + 1;
    }
    $kodeOrder = $prefix . str_pad($kodeBaru, 3, '0', STR_PAD_LEFT);

    $cart = $_SESSION['cart'] ?? [];

    foreach ($cart as $id_Menu => $item)
    {
        $jumlah = $item['jumlah'];

        $stmt_menu = $db->prepare("SELECT harga_Menu FROM menu WHERE id_Menu = ?");
        $stmt_menu->bind_param("i", $id_Menu);
        $stmt_menu->execute();
        $q = $stmt_menu->get_result();
        $menu = $q->fetch_assoc();
        $stmt_menu->close();
    
        if ($menu && isset($menu['harga_Menu']))
        {
            $harga_menu = $menu['harga_Menu'];
            $total = $harga_menu * $jumlah;
    
            $stmt = $db->prepare("INSERT INTO pesan (kode_Order, id_Pelanggan, id_Menu, jumlah_Pesan, total_Harga)
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("siiii", $kodeOrder, $idPelanggan, $id_Menu, $jumlah, $total);
            $stmt->execute();
            $stmt->close();
        }
        else
        {
            continue;
        }
    }

    $total_amount = 0;
    foreach($cart as $id_Menu => $item)
    {
        $stmt_harga = $db -> prepare("SELECT harga_Menu FROM menu WHERE id_Menu = ?");
        $stmt_harga->bind_param("i", $id_Menu);
        $stmt_harga->execute();
        $res = $stmt_harga->get_result();
        $m = $res->fetch_assoc();
        $stmt_harga->close();

    if ($m && isset($m['harga_Menu'])) 
        {
            $total_amount += $m['harga_Menu'] * $item['jumlah'];
        }

    }



//Struk
$_SESSION['orderAkhir'] = $kodeOrder;
$_SESSION['menusForRating'] = array_map(function($item) 
{
    return 
    [
        'id_Menu' => $item['id_Menu'],
        'nama_Menu' => $item['nama_Menu'] ?? '', // jika kamu ingin pakai nama
    ];
}, array_map(function($id, $item) 
{
    return ['id_Menu' => $id] + $item;
}, array_keys($cart), $cart));

    $_SESSION['id_Pelanggan'] = $idPelanggan;
    unset($_SESSION['cart']);
    header("Location: /struk");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="../img/logo.svg" rel="icon" type="image/x-icon">
</head>

<body>
    <section class="bg-gray-50 bg-gray-100">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow  md:mt-0 sm:max-w-md xl:p-0 ">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold text-black justify-self-center">
                        Checkout
                    </h1>
                    <?php
                        if (!empty($_SESSION['checkout_errors'])): ?>
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
                        <ul>
                            <?php foreach ($_SESSION['checkout_errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                        </ul>
                        </div>
                    <?php 
                        unset($_SESSION['checkout_errors']);
                    endif; 
                    ?>

                    <form class="space-y-4 md:space-y-6" method="POST">
                        <div>
                            <label class="block mb-2 text-md font-medium text-gray-700">Nama</label>
                            <input type="text" 
                                    name="nama" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" 
                                    placeholder="Masukkan nama" required>
                        </div>
                        <div >
                            <label class="block mb-2 text-md font-medium text-gray-700">No Telepon</label>
                            <input type="text" 
                                    name="telepon" 
                                    pattern="\d{10,13}" 
                                    title="Nomor telepon harus 10-13 digit angka " 
                                    placeholder="Masukkan No Telp" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600  focus:border-primary-600 invalid:border-red-500 block w-full p-2.5" required>
                        </div>
                        <div >
                            <label class="block mb-2 text-md font-medium text-gray-700">Email</label>
                            <input type="email" name="email"
                            title="Masukkan alamat email yang valid" 
                            placeholder="Masukkan Email" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 invalid:border-red-500 block w-full p-2.5" required>
                        </div>
                        <button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Submit & Cetak Struk</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>