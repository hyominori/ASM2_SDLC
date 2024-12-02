<?php
include "connect.php";

$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phonenumber'];
    $role = $_POST['role'];

    if (empty($role) || !in_array($role, ['Student', 'Teacher', 'Admin'])) {
        $error = "Please select a valid role.";
    } else {
        $stmt = $conn->prepare("SELECT Email FROM Users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "This Email has already existed, Please try another Email!";
        } else {
            $stmt = $conn->prepare("INSERT INTO Users (FullName, Email, Phone, Address, Password, Role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $fullname, $email, $phoneNumber, $address, $password, $role);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Lỗi: " . $stmt->error;
            }
        }
        $stmt->close();
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


        div select {
            width: 35%;
            text-align: center;
        }

        .Register-button {
            width: 30%;
            padding: 5px;
            margin-top: 10px;
            background-color: black;
            color: rgb(233, 94, 13);
            font-weight: bold;
            border: 2px solid black;
            border-radius: 20px;
        }

        .Register-button:hover {
            transform: scale(1.1);
        }

        .already-have-an-account {
            margin-top: 10px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form class="register-box" action="register.php" method="POST">
            <h2>Registration</h2>
            <div class="user-box">
                <label>Full Name</label><br>
                <input type="text" name="fullname" required=""> 
            </div>
            <div class="user-box">
                <label>Password</label><br>
                <input type="password" name="password" required="">
            </div>
            <div class="user-box">
                <label>Email</label><br>
                <input type="email" name="email" required="">
            </div>
            <div class="user-box">
                <label>Address</label><br>
                <input type="text" name="address" required="">
            </div>
            <div class="user-box">
                <label>Phone Number</label><br>
                <input type="text" name="phonenumber" required="">
            </div>
            <div class="user-box">
                <select name="role" required="">
                    <option value="">Select Role</option>
                    <option value="Student">Student</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <button class="Register-button" type="submit">Register</button><br>
            <h5 class="already-have-an-account">
                Already have an account? <a href="login.php"><b>Login</b></a>
            </h5>

            <span class="text-danger"><?php echo $error; ?></span>
        </form>
    </div>
</body>
</html>