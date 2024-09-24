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
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Plan</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Angel Canete</td>
                            <td>Basic</td>
                            <td>Pending</td>
                            <td> <button type="button" class="btn btn-primary btn-sm">Activate</button></td>
                          </tr>
                
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
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Plan</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Angel Canete</td>
                <td>caneteangel187@gmail.com</td>
                <td>Basic</td>
                <td>Pending</td>
                <td>
         <button type="button" class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#modalview" data-bs-whatever="@mdo">View</button>

         <button type="button" class="btn btn-primary btn-edit"
         data-bs-toggle="modal" data-bs-target="#editmodal" data-bs-whatever="@mdo">Edit</button>

         <button type="button" class="btn btn-primary btn-delete">Delete</button>
                </td>
              
              </tr>
    
            </tbody>
          </table>
           
          <!-- view modal -->
          <div class="modal fade" id="modalview" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">User Information</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">
                  <form class="user-info">
                    
                    <div class="mb-3">

                      <p><b>Name</b></p>
                      <p><span>Angel Canete</span></p>
                    <hr>
                      <p><b>Email Address</b></p>
                      <p><span>caneteangel187</span></p>
                    <hr>
                      <p><b>Username</b></p>
                      <p><span>angelcanete</span></p>
                    <hr>
                      <p><b>Subscription Plan</b></p>
                      <p><span>Basic</span></p>
                    </div>

                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  
                </div>
              </div>
            </div>
          </div>

            <!-- edit modal -->

            <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Update User Account</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      <form>
                        <div class="mb-3">
                          <label for="name" class="col-form-label">Name:</label>
                          <input type="text" class="form-control" id="name">
                        </div>

                        <div class="mb-3">
                          <label for="email" class="col-form-label">Email:</label>
                          <input type="text" class="form-control" id="email">
                        </div>

                        <div class="mb-3">
                            <label for="username" class="col-form-label">Username:</label>
                            <input type="text" class="form-control" id="username">
                          </div>
  

                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save Changes</button>
                    </div>
                  </div>
                </div>
              </div>

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