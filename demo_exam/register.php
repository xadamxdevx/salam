<?php
require_once "db.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $login = $_POST["login"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];

    $check_sql = "SELECT * FROM users WHERE login = '$login'";
    $check_result = mysqli_query($connect, $check_sql);

    if (mysqli_num_rows($check_result) > 0 ) {
        $error = "Такой логин уже занят";
    } else {
        $sql = "INSERT INTO users (fullname, login, password, phone, email)
                VALUES ('$fullname', '$login', '$password', '$phone', '$email')";
        
        if (mysqli_query($connect, $sql)) {
            $message = "Пользователь создан";
        } else {
            $error = "Ошибка: " . mysqli_error();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Регистрация</title>
</head>
<body>
    <div class="container auth-box">
        <h1>Регистрация</h1>

        <p class="message"><?php echo $message; ?></p>
        <p class="error"><?php echo $error; ?></p>

        <form method="POST">
            <input type="text" name="fullname" placeholder="ФИО" required>
            <input type="text" name="login" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="text" name="phone" placeholder="8(XXX)XXX-XX-XX" required>
            <input type="email" name="email" placeholder="Email" required>

            <button type="submit">Зарегистрироваться</button>
        </form>

        <br>

        <a href="index.php">Уже есть аккаунт? Войти</a>
    </div>
</body>
</html>