<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Личный кабинет</title>
</head>
<body>
    <div class="container">

        <h1>Личный кабинет</h1>

        <p>Вы вошли как: <b><?php echo $_SESSION["login"]; ?></b></p>

        <a href="create_order.php">Создать заказ</a>
        <br><br>
        <a href="logout.php">Выйти</a>

        <hr>

        <h2>Мои заказы</h2>

        <?php if (mysqli_num_rows($result) > 0):?>
            <table border="1" cellpadding="10">
                <tr>
                    <th>ID</th>
                    <th>Вид работы</th>
                    <th>Дата</th>
                    <th>Площадь</th>
                    <th>Оплата</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                </tr>

                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $order["id"]; ?></td>
                        <td><?php echo $order["work_type"]; ?></td>
                        <td><?php echo $order["work_date"]; ?></td>
                        <td><?php echo $order["area"]; ?></td>
                        <td><?php echo $order["payment_method"]; ?></td>
                        <td><?php echo $order["phone"]; ?></td>
                        <td><?php echo $order["status"]; ?></td>
                    </tr>
                <?php endwhile; ?>

            </table>

        <?php else: ?>

            <p>У вас пока нет заказов.</p>
        
        <?php endif; ?>

        <hr>

        <h2>Наши работы</h2>

        <div class="slider">
            <img src="images/image1.jpg" class="slide active" alt="Работа 1">
            <img src="images/image2.jpg" class="slide" alt="Работа 2">
            <img src="images/image3.jpg" class="slide" alt="Работа 3">
            <img src="images/image4.jpg" class="slide" alt="Работа 4">

            <button class="slider-btn prev" type="button">‹</button>
            <button class="slider-btn next" type="button">›</button>
        </div>

    </div>
    <script src="slider.js"></script>
</body>
</html>