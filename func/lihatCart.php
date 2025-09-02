<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) 
{
    echo "<p class='text-gray-500'>Keranjang Anda kosong.</p>";
    exit;
}

$ids = array_keys($_SESSION['cart']);
$in = implode(',', array_fill(0, count($ids), '?'));

$stmt = $db->prepare("SELECT * FROM menu WHERE id_Menu IN ($in)");
$stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
$stmt->execute();
$result = $stmt->get_result();

echo "<ul class='divide-y divide-gray-200 space-y-2'>";
$total = 0;
while ($row = $result->fetch_assoc()) 
{
    $item = $_SESSION['cart'][$row['id_Menu']];
    $qty = $item['jumlah'];
    $harga = $item ['harga'];
    $subtotal = $qty * $harga;
    $total += $subtotal;

    echo "
    <li class='py-2 flex justify-between items-center'>
        <div>
            <p class='font-semibold'>
            {$row['nama_Menu']} x <span id=\"qty-{$row['id_Menu']}\"> {$qty}</span>
            </p>
            <p class='text-sm text-gray-500'>Rp " . number_format($row['harga_Menu'], 0, ',', '.') . " / item</p>
        </div>
        <div class='flex items-center gap-2'>
            <button onclick='kurangiItem({$row['id_Menu']})' class='bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded'>-</button>
            <p id=\"subtotal-{$row['id_Menu']}\" class='text-sm text-black'>Rp " . number_format($subtotal, 0, ',', '.') . "</p> 
        </div>
        
    </li>";
}
echo "</ul>";
echo "<div id=\"total\" class='mt-4 text-right font-bold text-lg text-green-700'>Total: Rp " . number_format($total, 0, ',', '.') . "</div>";
echo "<div class='mt-6 flex justify-end space-x-4'>
    <button onclick='hapusCart()' class='px-4 py-2 bg-red-500 hover:bg-red-400 text-white rounded-lg'>Kosongkan Keranjang</button>
    <button onclick='coCart()' class='px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg'>Checkout</button>
</div>";