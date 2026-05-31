<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $work_type = $_POST["work_type"];
    $work_date = $_POST["work_date"];
    $area = $_POST["area"];
    $payment_method = $_POST["payment_method"];
    $phone = $_POST["phone"];

    $sql = "INSERT INTO orders (user_id, work_type, work_date, area, payment_method, phone)
            VALUES ('$user_id', '$work_type', '$work_date', '$area', '$payment_method', '$phone')";

    if (mysqli_query($connect, $sql)) {
        $message = "Заказ успешно создан";
    } else {
        $message = "Ошибка: " . mysqli_error($connect);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Создать заказ</title>
</head>
<body>
    <div class="container">

        <h1>Создание заказа</h1>

        <p><?php echo $message; ?></p>

        <form method="POST">
        <label>Вид работ:</label> <br>
        <select name="work_type" required>
            <option value="">Выберите вид работ</option>
            <option value="Газон">Газон</option>
            <option value="Сорняки">Сорняки</option>
            <option value="Кусты">Кусты</option>
        </select>
        <br><br>

        <label>Желаемая дата</label> <br>
        <input type="text" name="work_date" placeholder="ДД.ММ.ГГГГ" required>
        <br><br>

        <label>Площадь территории: </label> <br>
        <input type="text" name="area" placeholder="Например: 30 кв.м" required>
        <br><br>

        <label>Способ оплаты: </label><br>
        <select name="payment_method" required>
            <option value="">Выберите способ оплаты</option>
            <option value="Наличные">Наличные</option>
            <option value="Перевод по номеру телефона">Перевод по номеру телефона</option>
        </select>
        <br><br>

        <label>Телефон: </label><br>
        <input type="text" name="phone" placeholder="8(XXX)XXX-XX-XX" required>
        <br><br>

        <button type="submit">Отправить заказ</button>

        </form>

        <br>

        <a href="dashboard.php">Назад в личный кабинет</a>

    </div>
</body>
</html>