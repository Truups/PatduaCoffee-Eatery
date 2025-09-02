<?php
include('../service/database.php');
session_start();

$regist_Message = "";

// if(isset($_SESSION["is_login"]))
// {
//     header("location: index.php");
// }

if (isset($_POST['register'])) 
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    if (empty($username) || empty($password) || empty($role)) {
        $regist_Message = "Silakan isi semua data sebelum mendaftar.";
    } else {
        $hashed_Pass = password_hash($password, PASSWORD_DEFAULT);

        try {
            if ($role === "admin") 
            {
                $cekSql = "SELECT * FROM admin WHERE username_Admin = ?";
                $insertSql = "INSERT INTO admin (username_Admin, pass_Admin) VALUES (?, ?)";
            } 
            elseif ($role === "owner") 
            {
                $cekSql = "SELECT * FROM owner WHERE username_Owner = ?";
                $insertSql = "INSERT INTO owner (username_Owner, pass_Owner) VALUES(?, ?)";
            } 
            else 
            {
                throw new Exception("Role Tidak Valid.");
            }

            // Cek jika username double
            $checkST = $db->prepare($cekSql);
            $checkST->bind_param("s", $username);
            $checkST->execute();
            $result = $checkST->get_result();

            if ($result->num_rows > 0) 
            {
                $regist_Message = "Username telah digunakan.";
            } 
            else 
            {
                $inserST = $db->prepare($insertSql);
                $inserST->bind_param("ss", $username, $hashed_Pass);
                if ($inserST->execute()) 
                {
                    $regist_Message = "Akun berhasil didaftarkan sebagai $role. Mengarahkan ke login...";
                    header("Refresh: 2; URL=/index.php");
                } 
                else 
                {
                    $regist_Message = "Pendaftaran gagal.";
                }
            }
        } 
        catch (Exception $e) 
        {
            $regist_Message = $e->getMessage();
        }
        $db->close();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Admin</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="../img/logo.svg" rel="icon" type="image/x-icon">
</head>

<body>
    <div class="flex items-center justify-center h-screen bg-gray-200">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96 text-center">
            <h3 class="text-2xl font-bold mb-4">Daftar Akun</h3>
            <i> <?php echo $regist_Message ?> </i>
            <form action="" method="POST">
                <label for="username" class="flex items-center  text-base font-medium">Username</label>
                <input type="text" placeholder="username" name="username"
                    class="w-full p-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-green-500" />
                <label for="password" class="flex items-center text-base font-medium">Password</label>
                <input type="password" placeholder="password" name="password"
                    class="w-full p-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-green-500" />
                <label for="role" class="flex items-center text-base font-medium">Daftar Sebagai</label>
                <select name="role" required
                    class="w-full p-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="admin">Admin</option>
                    <option value="owner">Owner</option>
                </select>
                <button type="submit" name="register"
                    class="w-full py-2 mt-6 mb-2 text-white bg-green-600 rounded hover:bg-green-500">Daftar Sekarang</button>
            </form>
        </div>
    </div>

</body>

</html>