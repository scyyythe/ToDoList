<?php
  include_once("connection.php");

  $statement = $conn->prepare("SELECT * FROM accounts WHERE u_status = 'pending'");
  $statement->execute();
  $user = $statement->fetchAll(PDO::FETCH_ASSOC);

  $statement = $conn->prepare("SELECT * FROM accounts WHERE u_status = 'active'");
  $statement->execute();
  $users = $statement->fetchAll(PDO::FETCH_ASSOC);

  $getInfo = null; // Initialize $getInfo as null to prevent access errors

  if (isset($_GET['u_id'])) {
      $user_id = $_GET['u_id'];
      $statement = $conn->prepare("SELECT * FROM accounts WHERE u_id = :user_id");
      $statement->bindParam(':user_id', $user_id);
      $statement->execute();
      $getInfo = $statement->fetch(PDO::FETCH_ASSOC); // Fetch single record
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/user.css">   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="img/icon-logo.jpg" type="image/x-icon"> 
    <title>ToDo</title>
</head>
<body>

    <div class="update-form">
        <p><b>Update User Information</b></p>

        <form method="POST" class="register">

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                value="<?php echo $getInfo['u_name']?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" class="form-control" id="status" name="status" placeholder="Status">
            </div>

            <div class="mb-3">
                <label for="plan" class="form-label">Plan</label>
                <input type="text" class="form-control" id="plan" name="plan" placeholder="Plan">
            </div>

            <button type="submit" class="btn btn-primary login-btn" id="signup-btn">Save Changes</button>
        </form>

    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
