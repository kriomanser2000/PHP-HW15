<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $name = trim($_POST['name']);
    if (!empty($name))
    {
        $stmt = $db->prepare("INSERT INTO Sector (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        header('Location: main.php');
        exit();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sector Add</title>
</head>
<body>
<form method="post" action="sectrEntrAdminBD2.php">
    <label for="name">Sector Name: </label>
    <input type="text" id="name" name="name" required>
    <button type="submit" id="addBtn" disabled>Add</button>
</form>
<script>
    const nameInput = document.getElementById('name');
    const addBtn = document.getElementById('addBtn');
    nameInput.addEventListener('input', () =>
    {
        addBtn.disabled = !nameInput.value;
    });
</script>
</body>
</html>