<?php 
session_start();
$ct = 0;

if (isset($_SESSION['cart']))
{
    foreach ($_SESSION['cart'] as $item)
    {
        $ct += $item['jumlah'];
    }
}

echo json_encode(['count'=> $ct] );

?>