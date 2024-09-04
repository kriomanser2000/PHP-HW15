<?php
include 'db.php';
if (!isset($_GET['sector_id']))
{
    header('Location: mainEntrAdminBD2.php');
    exit();
}
$sector_id = $_GET['sector_id'];
$categories = $db->prepare("SELECT id, name FROM Category WHERE sector_id = :sector_id");
$categories->execute([':sector_id' => $sector_id]);
$categories = $categories->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Category</title>
</head>
<body>
<h1>Категорії в Секторі <?= $sector_id ?></h1>
<ul>
    <?php foreach ($categories as $category): ?>
        <li><a href="product.php?category_id=<?= $category['id']; ?>"><?= $category['name']; ?></a></li>
    <?php endforeach; ?>
</ul>
<a href="mainEntrAdminBD2.php">Повернутися на Головну</a>
</body>
</html>