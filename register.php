<?php 
session_start();
include("include/connection.php");

$error = "";
$success = "";
$result = [];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $accType = 'User';
    $accStatus = 'Pending';
    $accPlan='Basic';


    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $statement = $conn->prepare("INSERT INTO accounts (u_name, email, username, password, u_type, u_status, plan) VALUES (:name, :email, :username, :hashed_password, :accType, :accStatus, :accPlan)");


    $statement->bindValue(':name', $name);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':hashed_password', $hashed_password);
    $statement->bindValue(':accType', $accType);
    $statement->bindValue(':accPlan', $accPlan);
    $statement->bindValue(':accStatus', $accStatus);

    $result = $statement->execute();

    if ($result) {

        $_SESSION['username'] = $username;
        $_SESSION['name'] = $name; 
        $_SESSION['email'] = $email; 
        $_SESSION['accType'] = $accType;
        $_SESSION['accStatus'] = $accStatus;

        $success = "Registration successful!";


       
    } else {
      $error = "Registration failed. Please try again.";
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
    <title>Register</title>
</head>
<body>
    <div class="wrapper">
        <div class="container form-con">
            <div class="login-form">    
            <h3><b>Create an Account</b></h3>

    <div class="alert-message-container">
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
  
                <form method="POST"  class="register">
                      
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                      </div>

                      <div class="mb-3">
                        <label for="email" class="form-label email">Email</label>
                        <input type="emailHelp" class="form-control" id="email" name="email" placeholder="Email Address" required>
                      </div>

                    <div class="mb-3">
                      <label for="username" class="form-label">Username</label>
                      <input type="username" class="form-control" id="username" aria-describedby="text" name="username" placeholder="Username"required>
                     
                    </div>
    
                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
    
                    <button type="submit" class="btn btn-primary login-btn" id="signup-btn">Register</button>
                  </form>
            </div>
    
        <div class="container right-con">
            <h1><b>Welcome Back!</b></h1>
            <p>"A task management system streamlines workflow by organizing tasks, setting priorities, and ensuring timely completion for increased productivity."</p>
            
                <button class="btn btn-primary sign-up-btn" id="sign-up-btn" onclick="window.location.href='login.php'">Sign In</button>
    
        </div>
        
    </div>
</body>
</html>