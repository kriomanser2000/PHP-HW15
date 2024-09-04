<?php
include 'db.php';
$sort_name = isset($_GET['sort_name']) ? $_GET['sort_name'] : 'asc';
$sort_price = isset($_GET['sort_price']) ? $_GET['sort_price'] : 'asc';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$selected_companies = isset($_GET['company']) ? $_GET['company'] : [];
$selected_countries = isset($_GET['country']) ? $_GET['country'] : [];
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$company_list = $db->query("SELECT DISTINCT make FROM Product")->fetchAll(PDO::FETCH_COLUMN);
$country_list = $db->query("SELECT DISTINCT country FROM Product")->fetchAll(PDO::FETCH_COLUMN);
$query = "SELECT id, name, price, make, model, country FROM Product WHERE category_id = :category_id";
$filters = [':category_id' => $category_id];
if ($min_price !== '')
{
    $query .= " AND price >= :min_price";
    $filters[':min_price'] = $min_price;
    $filters[':min_price'] = $min_price;
}
if ($max_price !== '')
{
    $query .= " AND price <= :max_price";
    $filters[':max_price'] = $max_price;
}
if (!empty($selected_companies))
{
    $query .= " AND make IN (" . implode(',', array_fill(0, count($selected_companies), '?')) . ")";
    $filters = array_merge($filters, $selected_companies);
}
if (!empty($selected_countries))
{
    $query .= " AND country IN (" . implode(',', array_fill(0, count($selected_countries), '?')) . ")";
    $filters = array_merge($filters, $selected_countries);
}
$query .= " ORDER BY name $sort_name, price $sort_price";
$stmt = $db->prepare($query);
$stmt->execute($filters);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product</title>
</head>
<body>
<h1>Продукти в Категорії <?= $category_id ?></h1>

<form method="get" action="product.php">
    <input type="hidden" name="category_id" value="<?= $category_id ?>">
    <label for="sort_name">Фільтр за Ім'ям: </label>
    <select name="sort_name" id="sort_name">
        <option value="asc" <?= $sort_name === 'asc' ? 'selected' : '' ?>>Зростаючий</option>
        <option value="desc" <?= $sort_name === 'desc' ? 'selected' : '' ?>>Спадаючий</option>
    </select>
    <label for="sort_price">Фільтр за Ціною: </label>
    <select name="sort_price" id="sort_price">
        <option value="asc" <?= $sort_price === 'asc' ? 'selected' : '' ?>>Зростаючий</option>
        <option value="desc" <?= $sort_price === 'desc' ? 'selected' : '' ?>>Спадаючий</option>
    </select>
    <label for="min_price">Мінімальна Ціна: </label>
    <input type="number" name="min_price" id="min_price" value="<?= htmlspecialchars($min_price) ?>">
    <label for="max_price">Максимальна Ціна: </label>
    <input type="number" name="max_price" id="max_price" value="<?= htmlspecialchars($max_price) ?>">
    <fieldset>
        <legend>Фільтр за Компанією: </legend>
        <?php foreach ($company_list as $company): ?>
            <label>
                <input type="checkbox" name="company[]" value="<?= htmlspecialchars($company) ?>"
                    <?= in_array($company, $selected_companies) ? 'checked' : '' ?>>
                <?= htmlspecialchars($company) ?>
            </label><br>
        <?php endforeach; ?>
    </fieldset>
    <fieldset>
        <legend>Filter by Country:</legend>
        <?php foreach ($country_list as $country): ?>
            <label>
                <input type="checkbox" name="country[]" value="<?= htmlspecialchars($country) ?>"
                    <?= in_array($country, $selected_countries) ? 'checked' : '' ?>>
                <?= htmlspecialchars($country) ?>
            </label><br>
        <?php endforeach; ?>
    </fieldset>

    <button type="submit">Apply Filters</button>
</form>

<ul>
    <?php foreach ($products as $product): ?>
        <li><?= htmlspecialchars($product['name']); ?> (<?= htmlspecialchars($product['make']); ?> <?= htmlspecialchars($product['model']); ?>, <?= htmlspecialchars($product['country']); ?>) - $<?= htmlspecialchars($product['price']); ?></li>
    <?php endforeach; ?>
</ul>

<a href="categories.php?sector_id=<?= htmlspecialchars($category_id); ?>">Back to Categories</a>
</body>
</html>