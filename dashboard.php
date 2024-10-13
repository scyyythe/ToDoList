<?php
    session_start();
    include("include/connection.php");
    include("include/addNote.php");

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User'; 
    $u_id = $_SESSION['u_id'];  

    //pending na notes
    $statement = $conn->prepare("SELECT title, note FROM note WHERE u_id = :u_id AND status = 'Pending'");
    $statement->bindValue(':u_id', $u_id);
    $statement->execute();
    $notes= $statement->fetchAll(PDO::FETCH_ASSOC);

    //completed na notes
    $statement = $conn->prepare("SELECT title, note FROM note WHERE u_id = :u_id AND status = 'Completed'");
    $statement->bindValue(':u_id', $u_id);
    $statement->execute();
    $completedTasks = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    // Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status']) && $_POST['status'] === 'completed') {
        // Update the note status to completed
        $noteId = $_POST['note_id'];
        $stmt = $conn->prepare("UPDATE note SET status = 'Completed' WHERE note_id = :note_id AND u_id = :u_id");
        $stmt->bindValue(':note_id', $noteId);
        $stmt->bindValue(':u_id', $u_id);
        $stmt->execute();
    } elseif (isset($_POST['delete_note'])) {
        // Delete the note
        $noteId = $_POST['note_id'];
        $stmt = $conn->prepare("DELETE FROM note WHERE note_id = :note_id AND u_id = :u_id");
        $stmt->bindValue(':note_id', $noteId);
        $stmt->bindValue(':u_id', $u_id);
        $stmt->execute();
    }
}

// pending notes
$statement = $conn->prepare("SELECT note_id, title, note FROM note WHERE u_id = :u_id AND status = 'Pending'");
$statement->bindValue(':u_id', $u_id);
$statement->execute();
$notes = $statement->fetchAll(PDO::FETCH_ASSOC);

// completed notes
$statement = $conn->prepare("SELECT title, note FROM note WHERE u_id = :u_id AND status = 'Completed'");
$statement->bindValue(':u_id', $u_id);
$statement->execute();
$completedTasks = $statement->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="img/icons8-to-do-50.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style-user.css">
    <title>ToDo</title>
</head>
<body>
    <header>
        <h3>ToDo</h3>
        <ul>
            <li><a href="#" id="dashboard-link">Dashboard</a></li>
            <li><a href="#" id="my-task-link">My List</a></li>
            <li><a href="#" id="settings-link">Settings</a></li>
            <li><a href="index.php">Sign Out</a></li>
        </ul>
    </header>

    <div class="wrapper">
        <section id="dashboard">
            <p id="live-date"></p>

            <div class="top-dashboard">
                <div class="left-dash">
                    <h1>Hi,<?php echo $name?></h1>
                    <p>Ready to start your day with ToDo?</p>
                </div>
                <div class="right-dash">
                    <img src="img/image1.png" alt="">
                </div>
            </div>
            <div class="below-dash">
                <p><b>Overview</b></p>
                <div class="count">
                    <div class="count-pending-task">
                        <p><span>5</span> Pending</p>
                    </div>
                    <div class="count-completed-task">
                        <p><span>5</span> Completed</p>
                    </div>
                </div>
                <h4>My List</h4>
                <div class="dash-list-container">
    <?php if (!empty($notes)) { ?>
        <?php foreach ($notes as $note) { ?>
            <div class="dash-list">
                <div class="left-dash-list">
                    <h3><?php echo htmlspecialchars($note['title']); ?></h3><br>
                    <p><?php echo htmlspecialchars($note['note']); ?></p>
                </div>
                <div class="right-dash-list">
                    <p>Due Date: September 30, 2024</p><br>

                
                    <form method="POST" action="">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <input type="hidden" name="status" value="completed">
                        <button type="submit">
                            <i class='bx bx-check-circle'></i>
                        </button>
                    </form>

                  
                    <form method="POST" action="">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <input type="hidden" name="delete_note" value="true">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this note?');">
                            <i class='bx bxs-trash'></i>
                        </button>
                    </form>

               
                    <form method="GET" action="edit_note.php">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <button type="submit">
                            <i class='bx bxs-edit'></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No pending tasks available.</p>
    <?php } ?>
</div>



            </div>
        </section>

        <section id="my-task" style="display: none;">

            <div class="head-mytask">
                <h3>To Do List</h3>
                <p id="live-date-mytask"></p>
            </div>
            
            <div class="tab-container">
                <div class="tabs">
                    <button class="tab-button active" id="tab1-button">Things To Do</button>
                    <button class="tab-button" id="tab2-button">My Folders</button>
                </div>


                <div class="tab-content">
                    <div class="tab-pane active" id="tab1-content">
                        <p><b>New List</b></p>
                        
                        <div class="inside-tab-pane">

                            <div class="left-tab">

                                <form action="" method="POST">
                                    
                                    <label for="title">Title</label><br>
                                    <input type="text" name="title" placeholder="Title"><br>

                                    <label for="note">Note</label><br>
                                    <textarea name="note" id="to-do-note" placeholder="Add your note"></textarea><br>

                                    
                                    <button name="addNote">Add Note</button>
                                </form>
                                 
                            </div>
                            
                        </div>

                        <hr>

                        <div class="tab1-below-list">
                            <div class="tab1-head-list">
                                <h3>My List</h3>
                                <a href="javascript:void(0);" onclick="showCompletedTask()">View Completed</a>
                           </div>
                           
                           <div class="dash-list-container">
    <?php if (!empty($notes)) { ?>
        <?php foreach ($notes as $note) { ?>
            <div class="dash-list">
                <div class="left-dash-list">
                    <h3><?php echo htmlspecialchars($note['title']); ?></h3><br>
                    <p><?php echo htmlspecialchars($note['note']); ?></p>
                </div>
                <div class="right-dash-list">
                    <p>Due Date: September 30, 2024</p><br>

                
                    <form method="POST" action="">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <input type="hidden" name="status" value="completed">
                        <button type="submit">
                            <i class='bx bx-check-circle'></i>
                        </button>
                    </form>

                  
                    <form method="POST" action="">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <input type="hidden" name="delete_note" value="true">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this note?');">
                            <i class='bx bxs-trash'></i>
                        </button>
                    </form>

               
                    <form method="GET" action="edit_note.php">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <button type="submit">
                            <i class='bx bxs-edit'></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No pending tasks available.</p>
    <?php } ?>
</div>
                       
                        <section id="completed-task" style="display: none;">
                            <div class="completed-header">
                                <h3>Completed Tasks</h3>
                                <a href="javascript:void(0);" onclick="hideCompletedTask()">Close</a> 
                            </div>
                            <!-- display completed -->

                            <div class="grid-container">
                                <?php if (!empty($completedTasks)) { ?>
                                    <?php foreach ($completedTasks as $task) { ?>
                                        <div class="grid-item">
                                            <div class="left-dash-list">
                                                <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                                                <p><?php echo htmlspecialchars($task['note']); ?></p>
                                            </div>
                                            <div class="right-dash-list">
                                                <p>Due Date: September 30, 2024</p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <p>No completed tasks available.</p>
                                <?php } ?>
                            </div>



                        
                        </section>

                       <a href="#" id="allTask-link">View All Task</a>                

                    </div>

                    <!-- tab 2 -->
                    <div class="tab-pane" id="tab2-content">                     
                     
                        <section id="folder-section">
                            <h4>Organize your list!</h4><br>
                            <h5>Create New Folder</h5>
                            <div class="add-folder">
                            <input type="text" id="folder-name" placeholder="Add Folder" />
                            <button id="add-folder-btn">Add</button>
                            </div>
                            <hr />
                        
                          
                            <div class="folders-container" id="folders-container">
                            <!-- created folders will appear here -->
                            </div>
                        </section>
                        
                     
        <section id="folder-content" style="display:none;">
                    <div class="folder-header">
                    <button id="back-btn">&larr; Back to Folders</button>
                    <button id="delete-folder-btn" class="delete-folder">Delete Folder</button>
                    </div>
      
                    <hr />
                    <div id="add-task-form">
                        <div class="right-task-form">
                            <input type="text" id="task-title" placeholder="Note Title" required />
                            <input type="text" id="task-description" placeholder="Note Description" required />
                        </div>
                        
                        <div class="due-date-task-form">
                            <label for="due-date-folder">Deadline</label><br>
                            <input type="date" id="task-due-date" name="due-date-folder" placeholder="Due Date" required /><br>
                            <label for="image-note">Upload Image</label><br>
                            <input type="file" id="task-image" accept="image/*"><br>
                        </div>
                        
                        <button id="add-task-btn">Add Task</button>
                    </div>
                    
                    <hr />
                
                    
                    <div id="task-list"></div>
                </section>
                
                            
        
                </div>
            </div>
        </section>


        <!-- VIEW ALL TASK CONTAINER -->
        <section id="viewtaskContainer" style="display: none;">

            <div class="head-viewtastContainer">
                  <h3>All Task</h3>
                <p><b>Organize your task!</b></p>
            </div>
          
            <div class="all-task-container">

            <div class="task-list">
            
                        <<?php foreach ($notes as $note) { ?>
                <div class="task-box">
                    <div class="check-task">
                    <form method="POST" action="">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <input type="hidden" name="status" value="completed">
                        <button type="submit">
                            <i class='bx bx-check-circle'></i>
                        </button>
                    </form>
                    </div>
                    <div class="task-box-top">
                        <h3><?php echo htmlspecialchars($note['title']); ?></h3>
                        <p><?php echo htmlspecialchars($note['note']); ?></p>
                    </div>
                    <div class="task-box-bottom">
                        <div class="task-due">
                        <p>Due Date: September 30, 2024</p><br>
                        </div>
                        <div class="task-actions">
                
                            <form method="POST" action="">
                                <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                                <input type="hidden" name="delete_note" value="true">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this note?');">
                                    <i class='bx bxs-trash'></i>
                                </button>
                            </form>


                            <form method="GET" action="edit_note.php">
                                <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                                <button type="submit">
                                    <i class='bx bxs-edit'></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>

            </div>
                
            
            </div>
            
        </section>



        <!-- settingss  -->
        <section id="settings" style="display: none;">
            <h3>Account Information</h3>

            <div class="settings-information">
            
                <label for="name">Name</label><br>
                <input type="text" value="<?php echo $name?>"><br>

                <label for="email">Email</label><br>
                <input type="text" value="<?php echo $email?>"><br>

                <label for="username">Username</label><br>
                <input type="text" value="<?php echo $username?>"><br>

                <label for="password">Password</label><br>
                <input type="password"><br><br>

                <button>Edit Profile</button>
            </div>
            
            <div class="settings-right">
                <p>Account Status: <span><b>Basic</b></span></p>
            
                <div class="upgrade-plan-container">
                    <h3>Upgrade your plan</h3>
                    <p>Get the right plan for your list</p>
            
                    <h5>Premium Plan</h5>
                    <p><span>P100</span></p>
                    
                    <ul>
                        <li>Set Deadlines (Date & Time)</li>
                        <li>Upload Images to your list</li>
                        <li>Create Folders to organize lists</li>
                    </ul>
            
                    <button onclick="window.location.href='payment.html'">Get Plan</button>
                </div>
            </div>
            
        </section>
        
    </div>

    <script src="js/dashboard.js">
        
    </script>
</body>
</html>
