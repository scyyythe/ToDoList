<?php
session_start();
include("include/connection.php");

$error = "";
$success = "";
$result = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
            $statement = $conn->prepare("
            SELECT u_id, u_name, email, username, password, u_type, plan_id 
            FROM accounts 
            WHERE username = :username
        ");
        $statement->bindValue(':username', $username);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $result = $user && password_verify($password, $user['password']);

        if ($result) {

            $_SESSION['u_id'] = $user['u_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email']; 
            $_SESSION['name'] = $user['u_name']; 
            $_SESSION['u_type'] = $user['u_type'];
            $_SESSION['accPlan'] = $user['plan_id'] ?? 'default';


            if ($user['u_type'] === 'Admin') {
                header("Location: admin.php");
            } else if ($user['u_type'] === 'User') {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            // Handle login failure
            $error = "Invalid credentials";  
        }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/icons8-to-do-50.png" type="image/x-icon">
    <title>Login Form</title>
</head>
<body>

    <div class="wrapper">

        <div class="container form-con">
        <div class="login-form">    


        <div class="alert-message-container-login">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $success; ?>
                        </div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $error; ?>
                        </div>
                    <?php endif; ?>
                </div>

            <form  method="POST"  class="login">
                <h1><b>Sign in to ToDo</b></h1>
                <p>Please fill up information below.</p>

                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>

                  <input type="username" class="form-control" id="username" aria-describedby="emailHelp" name="username" placeholder="Username" required>
                 
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="checkbox-remember" name="checkbox-remember" required>
                  
                  <label class="form-check-label" for="checkbox-remember">Remember Me</label>
                </div>

                <button type="submit" class="btn btn-primary login-btn" id="login-btn">Sign In</button>
              </form>
        </div>

    <div class="container right-con">
        <h1><b>Hello, Friend!</b></h1>
        <p>Enter your personal details <br>
            and start your journey with us</p>
        
            <button class="btn btn-primary sign-up-btn" id="sign-up-btn" onclick="window.location.href='register.php'">Sign Up</button>

    </div>
    
</div>

        
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>