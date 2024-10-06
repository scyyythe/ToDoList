<?php
  include_once("connection.php");

  $statement = $conn->prepare("SELECT * FROM accounts WHERE u_status = 'pending'");
  $statement->execute();
  $user = $statement->fetchAll(PDO::FETCH_ASSOC);

  $statement = $conn->prepare("SELECT * FROM accounts WHERE u_status= 'active'");
  $statement->execute();
  $users = $statement->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="img/icons8-to-do-50.png" type="image/x-icon">x-icon"> 
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
                        <p>Premiuim Plan  <br>
                            <span>50</span></p>
                            
                    </div>

                    <div class="image">
                        <img src="img/image1.png" class="image-girl" alt="Image">
                    </div>
    
                   
                </div>

                    <!-- pending accounts -->
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
                       <?php foreach($user as $i =>$user):?>
                        <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                            <td><?php echo $user['u_id']?></td>
                            <td><?php echo $user['u_name']?></td>
                            <td><?php echo $user['email']?></td>
                            <td><?php echo $user['u_status']?></td>
                            <td> <button type="button" class="btn btn-primary btn-sm">Activate</button></td>
                          </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                </div>
                  
        </div>



        <!-- Manage -->
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

            <?php foreach($users as $i =>$users):?>
              <tr>
                  <th scope="row"><?php echo $i + 1; ?></th>
                     <td><?php echo $users['u_id']?></td>
                     <td><?php echo $users['u_name']?></td>
                     <td><?php echo $user['email']?></td>
                      <td><?php echo $user['plan']?></td>
                    <td><?php echo $users['u_status']?></td>

                    <td>
        <div class="edit-button"><a href="updateUser.php?<?php echo $users['u_id']?>" >Edit</a></div>
                </td>

                <td><button type="button" class="btn btn-primary btn-delete">Delete</button></td>
              </tr>
              
              <?php endforeach ?>
      
            </tbody>
          </table>
          
<!-- end of manage user -->
        </div>


        <!-- Payments -->
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
    <script src="js/script.js">
        
    </script>
</body>
</html>