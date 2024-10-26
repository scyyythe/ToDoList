<?php
include_once("include/connection.php");
include("include/classAccounts.php");

$accountManager = new accountManage($conn);
$pendingAccounts = $accountManager->getPendingAccounts();
$activeAccounts = $accountManager->getActiveAccounts();

$getInfo = null; 
if (isset($_GET['u_id'])) {
    $user_id = $_GET['u_id'];
    $getInfo = $accountManager->getUserInfo($user_id); 
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $status = $_POST['status'];
    $plan = $_POST['plan'];


    $updateSuccess = $accountManager->updateUser($user_id, $name, $email, $username, $status, $plan);

    if ($updateSuccess) {
       
        header("Location: admin.php"); 
        exit();
    } else {
       
        echo "Error updating user information.";
    }
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
    <link rel="shortcut icon" href="img/icons8-to-do-50.png" type="image/x-icon">
    <title>ToDo</title>
</head>
<body>

<div class="update-form">
    <p><b>Update User Information</b></p>

    <form method="POST" class="register">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" 
            value="<?php echo $getInfo['u_name']; ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label email">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
            value="<?php echo $getInfo['email']; ?>" placeholder="Email Address" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" 
            value="<?php echo $getInfo['username']; ?>" placeholder="Username" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="text" class="form-control" id="status" name="status" 
            value="<?php echo $getInfo['u_status']; ?>" placeholder="Status">
        </div>

        <div class="mb-3">
            <label for="plan" class="form-label">Plan</label>
            <input type="text" class="form-control" id="plan" name="plan" 
            value="<?php echo $getInfo['plan']; ?>" placeholder="Plan">
        </div>

        <button type="submit" class="btn btn-primary login-btn" id="signup-btn">Save Changes</button>
    </form>
</div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
