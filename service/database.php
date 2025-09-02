<?php 

$host ="localhost";
$username = "u943349902_Truups";
$pass = "Gwentcard_10";
$database = "u943349902_db_cafe42";


$db = mysqli_connect($host,$username,$pass,$database);

if($db -> connect_error)
{
    echo "koneksi rusak";
    die("error!");
}


?>