<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $stmt = $db->prepare("SELECT * FROM User WHERE login = :login");
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password']))
    {
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['user'] = $user['id'];
        $_SESSION['start_time'] = time();
        header('Location: main.php');
        exit();
    }
    else
    {
        $error = $user ? "Incorrect password" : "User is not found";
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
    <title>Log In</title>
</head>
<body>
<form method="post" action="logEntrAdminBD2.php">
    <label for="login">Login: </label>
    <input type="text" id="login" name="login" required>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Log In</button>
</form>
<?php if (isset($error)):?>
    <p><?= $error ?></p>
<?php endif; ?>
</body>
</html>