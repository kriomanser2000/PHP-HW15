<?php
session_start();
if (!isset($_SESSION['loggedin']) || (time() - $_SESSION['start_time'] > 3600))
{
    session_unset();
    session_destroy();
    header('Location: logEntrAdminBD2.php');
    exit();
}
$_SESSION['start_time'] = time();
include 'db.php';
$sector_count = $db->query("SELECT COUNT(*) FROM Sector")->fetchColumn();
$category_count = $db->query("SELECT COUNT(*) FROM Category")->fetchColumn();
$product_count = $db->query("SELECT COUNT(*) FROM Product")->fetchColumn();
$sectors = $db->query("SELECT id, name FROM Sector")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Main Web</title>
</head>
<body>
<h1>Головна</h1>
<p>Число Секторів: <?= $sector_count ?></p>
<p>Число Категорій: <?= $category_count ?></p>
<p>Число Продуктів: <?= $product_count ?></p>
<h2>Сектори</h2>
<ul>
    <?php foreach ($sectors as $sector): ?>
        <li><a href="category.php?sector_id=<?= $sector['id']; ?>"><?= $sector['name']; ?></a></li>
    <?php endforeach; ?>
</ul>
<form method="get" action="sectrEntrAdminBD2.php">
    <button type="submit">Додати Сектор</button>
</form>
<form method="get" action="category.php">
    <button type="submit" <?= $sector_count > 0 ? '' : 'disabled' ?>>Додати Категорію</button>
</form>
<form method="get" action="product.php">
    <button type="submit" <?= $category_count > 0 ? '' : 'disabled' ?>>Додати Продукт</button>
</form>
<form method="post" action="logoutEntrAdminBD2.php">
    <button type="submit">Вийти</button>
</form>
</body>
</html>