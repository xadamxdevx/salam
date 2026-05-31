<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];

    if ($login == "Admin" && $password = "Grass") {
        $_SESSION["user_id"] = 0;
        $_SESSION["login"] = "Admin";
        $_SESSION["role"] = "admin";

        header("Location: admin.php");
        exit;
    }

    $sql = "SELECT * FROM users WHERE login = '$login' and password = '$password'";
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["login"] = $user["login"];
        $_SESSION["role"] = $user["role"];

        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Неправильный логин или пароль";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Вход</title>
</head>
<body>
    <div class="container auth-box">
        <h1>Вход</h1>

        <p class="error"><?php echo $message; ?></p>

        <form method="POST">
            <input type="text" name="login" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>

            <button type="submit">Войти</button>
        </form>

        <br>

        <a href="register.php">Нету аккаунта? Регистрация</a>
    </div>
</body>
</html>