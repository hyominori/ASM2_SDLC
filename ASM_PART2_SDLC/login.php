<?php
session_start();
include "connect.php"; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!empty($email) && !empty($password)) {

        $stmt = $conn->prepare("SELECT UserID, FullName, Password, Role FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['Password'])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["userID"] = $user['UserID'];
                $_SESSION["fullname"] = $user['FullName'];
                $_SESSION["role"] = $user['Role'];

                header("Location: main.php");
                exit();
            } else {
                $error = "Invalid password. Please try again.";
            }
        } else {
            $error = "No account found with this email.";
        }
        $stmt->close();
    } else {
        $error = "Please enter both email and password.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            width: 70%;
            height: 100%;
            justify-self: center;
            margin: 30px 20px;
            padding: 20px 20px;
            background-image: url("image/orange2.jpg");
            background-size: cover;
        }

        .container {
            align-self: center;
            justify-self: center;
            width: 70%;
            height: auto;
        }

        form {
            width: 70%;
            height: auto;
            padding: 20px;
            justify-self: center;
            text-align: center;
            border-radius: 20px;
            border: solid 1px rgb(255, 255, 255);
            backdrop-filter: blur(6px);
            box-shadow: 3px 3px 4px rgba(255, 255, 255, 0.793);
        }


        .user-box {
            padding: 10px;
            margin-top: 10px;
        }

        .user-box input {
            width: 70%;
            height: auto;
        }

        .remember-forgot-box {
            padding: 10px;
            display: inline-flex;
        }

        .remember-me {
            width: fit-content;
            margin-right: 80px;
        }

        .login-button {
            width: 30%;
            padding: 5px;
            margin-top: 10px;
            background-color: black;
            color: rgb(233, 94, 13);
            font-weight: bold;
            border: 2px solid black;
            border-radius: 20px;
        }

        .login-button:hover {
            transform: scale(1.1);
        }

        .dont-have-an-account {
            margin-top: 10px;
            padding: 5px;
        }


    </style>
</head>
<body>
    <div class="container">
        <form action="login.php" method="post" class="login-box">
            <h2>Login</h2>
            
            <div class="user-box">
                <label>Email</label><br>
                <input type="email" name="email" required="">
            </div>
            <div class="user-box">
                <label>Password</label><br>
                <input type="password" name="password" required="">
            </div>

            <section class="remember-forgot-box">
                <div class="remember-me">
                    <label for="remember-me">Remember me</label>
                    <input type="checkbox" name="remember-me" id="remember-me"> 
                </div>
                <a class="forgot-password" href="#">Forgot password?</a>
            </section> <br>

            <button class="login-button" type="submit">Login</button>

            <h5 class="dont-have-an-account">
                Don't have an account? <a href="register.php"><b>Register</b></a>
            </h5>
            <?php if (!empty($error)): ?>
                <div class="text-danger"><?php echo $error; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
