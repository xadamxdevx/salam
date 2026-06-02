<?php
/*

// - SQL

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    login VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    work_type VARCHAR(255) NOT NULL,
    work_date VARCHAR(50) NOT NULL,
    area VARCHAR(100) NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    status VARCHAR(50) DEFAULT 'Новый'
);


---------------------------------


// - db.php

<?php
$connect = mysqli_connect("localhost", "root", "", "demo_exam");

if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($connect, "utf8mb4");
?>


------------------------------------------------------------


// - logout.php

<?php
session_start();

session_unset();
session_destroy();

header("Location: index.php");
exit;
?>


------------------------------------------------------------


// - admin.php


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


------------------------------------------------------------


// - create_order.php

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


------------------------------------------------------------


// - dashboard.php

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


------------------------------------------------------------


// - index.php

<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];

    if ($login == "Admin" && $password == "Grass") {
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
        $message = "Неверный логин или пароль";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Авторизация</title>
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


------------------------------


// - register.php

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
            $error = "Ошибка: " . mysqli_error($connect);
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


--------------------------------------------


// - slider.js

let slides = document.querySelectorAll(".slide");
let currentSlide = 0;

function showSlide(index) {
    slides[currentSlide].classList.remove("active");
    currentSlide = index;
    slides[currentSlide].classList.add("active");
}

document.querySelector(".next").onclick = function () {
    let nextSlide = currentSlide + 1;

    if (nextSlide >= slides.length) {
        nextSlide = 0;
    }

    showSlide(nextSlide);
};

document.querySelector(".prev").onclick = function () {
    let prevSlide = currentSlide - 1;

    if (prevSlide < 0) {
        prevSlide = slides.length - 1;
    }

    showSlide(prevSlide);
};

setInterval(function () {
    let nextSlide = currentSlide + 1;

    if (nextSlide >= slides.length) {
        nextSlide = 0;
    }

    showSlide(nextSlide);
}, 3000);


----------------------------------------------


// - style.css

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    font-family: Arial, sans-serif;
    background: #101111;
    color: #222;
}

.container {
    width: 90%;
    max-width: 1150px;
    margin: 40px auto;
    padding: 30px;
    background: white;
    border-radius: 14px;
    overflow-x: auto;
}

.auth-box {
    max-width: 430px;
    margin: 70px auto;
}

h1, h2 {
    color: #1f5f3b;
    margin-top: 0;
}

a {
    color: #1f5f3b;
    font-weight: bold;
    text-decoration: none;
}

form {
    width: 100%;
    max-width: 450px;
}

input, select, textarea, button {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    font-size: 16px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button {
    background: #1f5f3b;
    color: white;
    border: none;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #17472d;
}

.error {
    color: red;
    font-weight: bold;
}

.message {
    color: #1f5f3b;
    font-weight: bold;
}

table {
    width: 100%;
    min-width: 850px;
    margin-top: 15px;
    border-collapse: collapse;
    background: white;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: #1f5f3b;
    color: white;
}

td form {
    max-width: 160px;
}

td select,
td button {
    font-size: 14px;
    padding: 10px;
}

hr {
    border: none;
    border-top: 1px solid #ddd;
    margin: 20px 0;
}

.slider {
    width: 100%;
    height: 260px;
    position: relative;
    overflow: hidden;
    border-radius: 14px;
    margin-top: 15px;
    background: #eee;
}

.slide {
    width: 100%;
    height: 260px;
    object-fit: cover;
    display: none;
}

.slide.active {
    display: block;
}

.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 42px;
    height: 42px;
    padding: 0;
    margin: 0;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.55);
    color: white;
    border: none;
    font-size: 28px;
    line-height: 42px;
    cursor: pointer;
}

.prev {
    left: 12px;
}

.next {
    right: 12px;
}

@media (max-width: 600px) {
    .slider,
    .slide {
        height: 180px;
    }
}

@keyframes slider {
    0% { opacity: 0; }
    8% { opacity: 1; }
    25% { opacity: 1; }
    33% { opacity: 0; }
    100% { opacity: 0; }
}

@media (max-width: 600px) {
    body {
        font-size: 15px;
    }

    .container {
        width: 95%;
        margin: 15px auto;
        padding: 16px;
    }

    .auth-box {
        margin: 35px auto;
    }

    h1 {
        font-size: 24px;
    }

    form {
        max-width: 100%;
    }

    table {
        min-width: 850px;
        font-size: 14px;
    }

    .slider,
    .slider img {
        height: 180px;
    }
}


---------------------------------------------------
*/
?>