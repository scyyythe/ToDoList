<?php
include_once("include/connection.php");
include("include/classAccounts.php");

$accountManager = new accountManage($conn);
$user = $accountManager->getPendingAccounts();
$users = $accountManager->getActiveAccounts();

if (isset($_POST['delete'])) {
    $user_id = $_POST['u_id'];
    
    $deleteSuccess = $accountManager->deleteUser($user_id);
    
    if ($deleteSuccess) {
        header("Location: admin.php?message=User deleted successfully");
        exit();
    } 
}


if (isset($_POST['u_id'])) {
    $user_id = $_POST['u_id'];
    $isUpdated = $accountManager->updateUserStatus($user_id, 'Active');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="img/icons8-to-do-50.png" type="image/x-icon">
    <title>ToDo</title>
</head>
<body>
    <div class="sidebar">
        <h3>ToDo</h3>
        <div class="menu">
            <ul>
                <li><i class='bx bxs-dashboard'></i>&nbsp;&nbsp;<a href="" class="dashboard">Dashboard</a></li>
                <li><i class='bx bxs-user'></i>&nbsp;&nbsp;<a href="" class="manage">Manage</a></li>
                <li><i class='bx bx-credit-card'></i>&nbsp;&nbsp;<a href="" class="payments">Payments</a></li>
                <li><i class='bx bxs-arrow-to-left'></i>&nbsp;&nbsp;<a href="login.php">Sign Out</a></li>
            </ul>
        </div>
    </div> 

    <div class="wrapper">
        <div class="container dashboard-con" id="dashboard-con">
            <div class="container top-head">
                <p><strong>Welcome <span>Admin</span></strong></p>
                <div id="datetime"></div>
            </div>

            <div class="container dash-count">
                <div class="basic-plan">
                    <p>Basic Plan  <br>
                        <span>100</span></p>
                </div>

                <div class="premiuim-plan">
                    <p>Premium Plan  <br>
                        <span>50</span></p>
                </div>

                <div class="image">
                    <img src="img/image1.png" class="image-girl" alt="Image">
                </div>
            </div>

        
            <div class="container pending-accounts">
                <h5><strong>Pending Accounts</strong></h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($user as $i => $user): ?>
                        <tr>
                            <th scope="row"><?php echo $i + 1; ?></th>
                            <td><?php echo $user['u_id']?></td>
                            <td><?php echo $user['u_name']?></td>
                            <td><?php echo $user['email']?></td>
                            <td><?php echo $user['u_status']?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="u_id" value="<?php echo $user['u_id']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Activate</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div> 

        <div class="container manage-con" id="manage-con">
            <h5><strong>Manage Users</strong></h5>
            <table class="table users">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Plan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Update</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $i => $user): ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td><?php echo $user['u_id']; ?></td>
                        <td><?php echo $user['u_name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['plan']; ?></td>
                        <td><?php echo $user['u_status']; ?></td>
                        <td>
                            <div class="edit-button">
                                <a href="updateUser.php?u_id=<?php echo $user['u_id']; ?>">Edit</a>
                            </div>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="u_id" value="<?php echo $user['u_id']; ?>">
                                <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div> 

        <div class="container payments-con" id="payments-con">
            <h5><strong>Payments</strong></h5>
            <table class="table users">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Date of Payment</th>
                        <th scope="col">Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Angel Canete</td>
                        <td>09/10/24</td>
                        <td>Paid</td>
                    </tr>
                </tbody>
            </table>
        </div> 
    </div> 

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
