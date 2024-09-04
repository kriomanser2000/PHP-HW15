<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $country = trim($_POST['country']);
    $city = trim($_POST['city']);
    $errors = [];
    if (strlen($login) < 3 || strlen($login) > 20)
    {
        $errors[] = "Username is too short / long";
    }
    if (strlen($password) < 6)
    {
        $errors[] = "Password is too short";
    }
    if (empty($errors))
    {
        $stmt = $db->prepare("SELECT * FROM User WHERE login = :login");
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user)
        {
            $errors[] = "User already exists. Please login.";
        }
        else
        {
            $stmt = $db->prepare("INSERT INTO User (login, password, name, surname, country, city) 
                                  VALUES (:login, :password, :name, :surname, :country, :city)");
            $stmt->execute([
                ':login' => $login,
                ':password' => password_hash($password, PASSWORD_BCRYPT),
                ':name' => $name,
                ':surname' => $surname,
                ':country' => $country,
                ':city' => $city
            ]);
            header('Location: login.php');
            exit();
        }
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
    <title>Sign In</title>
</head>
<body>
<form method="post" action="regEntrAdminBD2.php">
    <label for="login">Login: </label>
    <input type="text" id="login" name="login" required>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" required>
    <label for="name">Name: </label>
    <input type="text" id="name" name="name" required>
    <label for="surname">Surname: </label>
    <input type="text" id="surname" name="surname" required>
    <label for="country">Country: </label>
    <input type="text" id="country" name="country" required>
    <label for="city">City: </label>
    <input type="text" id="city" name="city" required>
    <button type="submit" id="submitBtn" disabled>Submit</button>
</form>
<script>
    const inputs = document.querySelectorAll('input');
    const submitBtn = document.getElementById('submitBtn');
    inputs.forEach(input =>
    {
        input.addEventListener('input', () =>
        {
            let allFilled = true;
            inputs.forEach(input =>
            {
                if (!input.value)
                {
                    allFilled = false;
                }
            });
            submitBtn.disabled = !allFilled;
        });
    });
</script>
</body>
</html>