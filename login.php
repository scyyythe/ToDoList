<?php
  session_start();
  include("connection.php");

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database based on the username
    $statement = $conn->prepare("SELECT u_id, u_name, username, password FROM accounts WHERE username = :username");
    $statement->bindValue(':username', $username);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables on successful login
        $_SESSION['u_id'] = $user['u_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['u_name'];  

        header("Location:index.php");
        die;
    } else {
        echo "Invalid credentials";
    }
}
?>


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/icon-logo.jpg" type="image/x-icon">
    <title>Login Form</title>
</head>
<body>

    <div class="wrapper">

        <div class="container form-con">
        <div class="login-form">    

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
        
            <button class="btn btn-primary sign-up-btn" id="sign-up-btn" onclick="window.location.href='register.php'">Sign In</button>

    </div>
    
</div>

        
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>