<?php
session_start();

// Проверка наличия идентификатора пользователя в сессии
if (isset($_SESSION['user_id'])) {
    if ($_SERVER['PHP_SELF'] !== '/index.php') {
        header('Location: index.php');
        exit;
    }
} else {
    if ($_SERVER['PHP_SELF'] !== '/login.php') {
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Task List</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    </head>
    <body>