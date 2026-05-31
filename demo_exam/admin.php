<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST["order_id"];
    $status = $_POST["status"];

    $sql = "UPDATE orders SET status = '$status' WHERE id = '$order_id'";
    mysqli_query($connect, $sql);

    header("Location: admin.php");
    exit;
}

$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Админ панель</title>
</head>
<body>
    <div class="container">
        <h1>Админ панель</h1>

        <p>Вы вошли как: <b><?php echo $_SESSION["login"]; ?></b></p>
        <a href="logout.php">Выйти</a>

        <hr>

        <h2>Все заказы</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Пользователь ID</th>
                    <th>Вид работ</th>
                    <th>Дата</th>
                    <th>Площадь</th>
                    <th>Оплата</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>

                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $order["id"]; ?></td>
                        <td><?php echo $order["user_id"]; ?></td>
                        <td><?php echo $order["work_type"]; ?></td>
                        <td><?php echo $order["work_date"]; ?></td>
                        <td><?php echo $order["area"]; ?></td>
                        <td><?php echo $order["payment_method"]; ?></td>
                        <td><?php echo $order["phone"]; ?></td>
                        <td><?php echo $order["status"]; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order["id"]; ?>">

                                <select name="status">
                                    <option value="Новый">Новый</option>
                                    <option value="Мастер выехал">Мастер выехал</option>
                                    <option value="Выполнен">Выполнен</option>
                                </select>
                                
                                <button type="submit">Сохранить</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>

        <?php else: ?>
            <p>Заказов пока нет</p>
        <?php endif;?>
    </div>
</body>
</html>