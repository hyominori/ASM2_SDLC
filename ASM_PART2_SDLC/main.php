<?php
session_start();
include "connect.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

$fullname = $_SESSION["fullname"];
$role = $_SESSION["role"];

$content = "Welcome, {$fullname}! <br> Your role: {$role}";

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="main.css">
    <style>
        .logout-button {
            width: fit-content;
            padding: 2px 5px 2px 5px;
            border-radius: 20px;
            text-decoration: none;
            list-style-type: none;  
        }
        .logout-button p{
            text-decoration: none;
            list-style-type: none;
            font-size: 1.2rem;
            background-color: black;
            color: rgb(255, 64, 0);
            width: fit-content;
        }
    </style>
</head>
<body>
    <div class="logout-button">
        <a href="logout.php"><p>Logout</p></a>
    </div>
    <div class="container">
        <header>
            <h1><?php echo $content; ?></h1>
        </header>
    </div>
</body>
</html>
