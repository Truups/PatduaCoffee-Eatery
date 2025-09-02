<?php
include('../service/database.php');
session_start();


$login_Message = "";

// if(isset($_SESSION["is_login"]))
// {
//     header("location: index.php");
// }

if (isset($_POST['login'])) 
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(empty($username) || empty($password))
    {
        $login_Message = "Silahkan isi username dan password telebih dahulu";
    } else
    {
        // Cek tabel admin
    $sqlAdmin = "SELECT * FROM admin WHERE username_Admin= ?";
    $stmtAdmin = $db->prepare($sqlAdmin);
    $stmtAdmin->bind_param("s", $username);
    $stmtAdmin->execute();
    $resAdmin = $stmtAdmin->get_result();

    if ($resAdmin->num_rows > 0) 
    {
        $data = $resAdmin->fetch_assoc();
        if(password_verify($password, $data['pass_Admin']))
        {
            $_SESSION["username"] = $data["username_Admin"];
            $_SESSION["role"] = "admin";
            $_SESSION["is_login"] = true;
            $_SESSION["id_admin"] = $data['id_Admin'];
            header("Location: /dashboard.php");
            exit();
        }
    }

    // Cek tabel owner
    $sqlOwner = "SELECT * FROM owner WHERE username_Owner= ?";
    $stmtOwner = $db->prepare($sqlOwner);
    $stmtOwner->bind_param("s", $username);
    $stmtOwner->execute();
    $resOwner = $stmtOwner->get_result();

    if ($resOwner->num_rows > 0) 
    {
        $data = $resOwner->fetch_assoc();
        if(password_verify($password, $data['pass_Owner']))
        {
            $_SESSION["username"] = $data["username_Owner"];
            $_SESSION["role"] = "owner";
            $_SESSION["is_login"] = true;
            $_SESSION["id_admin"] = $data['id_Owner'];
            header("Location: /dashboard.php");
            exit();
        }
    }


    $login_Message = "Username atau password salah.";
    $db->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATDUA</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://patdua.store/img/logo.svg" rel="icon" type="image/x-icon">
</head>

<body>
<!-- Login Form -->
    <div class="flex items-center justify-center h-screen bg-gray-200">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96 text-center">
            <h3 class="text-2xl font-bold mb-4 ">Login</h3>
            <?php if (!empty($login_Message)): ?>
                <div class="mb-4 text-red-600 font-semibold"><?= $login_Message ?></div>
            <?php endif; ?>
            <form action="index.php" method="POST">
                <label for="username" class="flex items-center  text-base font-medium">Username</label>
                <input type="text" placeholder="username" name="username"
                    class="w-full p-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-green-500" />
                <label for="password" class="flex items-center text-base font-medium">Password</label>
                <input type="password" placeholder="password" name="password"
                    class="w-full p-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-green-500" />
                <button type="submit" name="login"
                    class="w-full py-2 mt-6 mb-2 text-white bg-green-600 rounded hover:bg-green-500">Login</button>
            </form>
            <p class="mt-4 text-center text-sm/6 text-gray-500">Belum memilki akun ?
                <a href="/regist.php" class="font-semibold text-green-600 hover:text-green-500">Daftar disini</a>
            </p>
        </div>

    </div>

<!-- Login Form end -->
</body>

</html>