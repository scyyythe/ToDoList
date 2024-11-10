<?php
session_start();
include("include/connection.php");
include("include/classNote.php");
include("include/classFolder.php");
include("include/classAccounts.php");

$username = $_SESSION['username'];
$email = $_SESSION['email'];
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User'; 
$u_id = $_SESSION['u_id'];  

$noteManager = new NoteManager($conn, $u_id);
$folderManager = new FolderManager($conn, $u_id);

$pendingCount = $noteManager->getPendingCount();
$completedCount = $noteManager->getCompletedCount();

$folders = $folderManager->getUserFolders();

$notes = $noteManager->getPendingNotes();
$completedTasks = $noteManager->getCompletedNotes();



if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['addNote'])) {
        $title = $_POST['title'];
        $note = $_POST['note'];

        if ($noteManager->addNote($title, $note)) {
            header('Location: dashboard.php');
            exit;
        }
        $_SESSION['error'] = "Failed to add note.";
    }elseif (isset($_POST['addNotePrem'])) {
        $title = $_POST['title'];
        $note = $_POST['note'];
        $folder_id = !empty($_POST['folder-id']) ? $_POST['folder-id'] : null;
        $due_date = $_POST['task-due-date'];
        $image = isset($_FILES['task-image']) ? $_FILES['task-image'] : null;


        if ($noteManager->uploadNote($image, $title, $note, $folder_id, $due_date)) {
            header('Location: dashboard.php');
            exit;
        }
        $_SESSION['error'] = "Failed to add note.";
    } elseif (isset($_POST['complete_note'])) {
        $noteId = $_POST['note_id'];
        
        if ($noteManager->markNoteAsCompleted($noteId)) {
            header('Location: dashboard.php');
            exit();
        }
        $_SESSION['error'] = "Failed to mark the note as completed.";
    } elseif (isset($_POST['deleteNote'])) {
        $noteId = $_POST['note_id'];

        if ($noteManager->deleteNote($noteId)) {
            header('Location: dashboard.php'); 
            exit();
        }
        $_SESSION['error'] = "Failed to delete note.";
    } elseif (isset($_POST['deleteAllCompleted'])) {
        if ($noteManager->deleteAllCompletedNotes()) {
            header('Location: dashboard.php'); 
            exit();
        }
        $_SESSION['error'] = "Failed to delete all completed notes.";
    } elseif (isset($_POST['createFolder'])) {
        $folderName = $_POST['folder_name'];

        if ($folderManager->createFolder($folderName)) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit(); 
        } else {
            $_SESSION['error'] = "Failed to create folder.";
        }
    }
}

$userManager = new accountManage($conn);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_SESSION['u_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $status = "active"; 
    $plan = $_POST['plan']; 

    if ($userManager->updateUser($user_id, $name, $email, $username, $status, $plan, $password)) {
        header('Location: ' . $_SERVER['PHP_SELF']); 
        exit();
    } else {
        echo "<p>Failed to update profile.</p>";
    }
}
$user = $userManager->getUserInfo($u_id);


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
                    <h1>Hi,<?php echo $user['u_name']; ?></h1>
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
                        <p><span><?php echo $pendingCount; ?></span> Pending</p>
                    </div>
                    <div class="count-completed-task">
                        <p><span><?php echo $completedCount; ?></span> Completed</p>
                    </div>
                </div>
       <h4>My List</h4>

                <div class="dash-list-container">
    <?php if (!empty($notes)) { ?>
        <?php foreach ($notes as $note) { ?>
            <div class="dash-list">
                <div class="left-dash-list">
                    <h3><?php echo ($note['title']); ?></h3><br>
                    <p><?php echo ($note['note']); ?></p>
                </div>
                <div class="right-dash-list">
                    <p>Due Date: September 30, 2024</p><br>
                    <form method="POST" action="dashboard.php">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <button type="submit" name="complete_note" class="complete-note-btn mark-complete-btn">
                            <i class='bx bx-check-circle'></i>
                        </button>
                    </form>


                    <form method="POST" action="dashboard.php" onsubmit="return confirm('Are you sure you want to delete this note?');">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <button type="submit" class="delete-note-btn" name="deleteNote">
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
        <p>No tasks available.</p>
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
            <form id="noteForm" method="POST">
                <label for="title">Title</label><br>
                <input type="text" name="title" placeholder="Title" required><br>

                <label for="note">Note</label><br>
                <textarea name="note" id="to-do-note" placeholder="Add your note" required></textarea><br>

                <button type="submit" name="addNote">Add Note</button> 
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
                                <h3><?php echo($note['title']); ?></h3><br>
                                <p><?php echo($note['note']); ?></p>
                            </div>
                            <div class="right-dash-list">
                                <p>Due Date: September 30, 2024</p><br>

                                <form method="POST" action="dashboard.php">
                                    <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                                    <button type="submit" name="complete_note" class="complete-note-btn mark-complete-btn">
                                        <i class='bx bx-check-circle'></i>
                                    </button>
                                </form>

                                    <form method="POST" action="dashboard.php" onsubmit="return confirm('Are you sure you want to delete this note?');">
                                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                                        <button type="submit" class="delete-note-btn" name="deleteNote">
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
               <form method="POST" action="dashboard.php" onsubmit="return confirm('Are you sure you want to delete all completed notes?');">
                    <button type="submit" id="delete-completed-btn" name="deleteAllCompleted">
                        <i class='bx bxs-trash'></i>
                    </button>
                </form>

           
                <div class="completed-header">
                    <h3>Completed Tasks</h3>
                    <a href="javascript:void(0);" onclick="hideCompletedTask()">Close</a>
                </div>

                <div class="message">

                </div>
                <div class="dash-list-container">
                    <?php if (!empty($completedTasks)) { ?>
                        <?php foreach ($completedTasks as $task) { ?>
                            <div class="dash-list">
                                <div class="left-dash-list">
                                    <h3><?php echo($task['title']); ?></h3>
                                    <p><?php echo($task['note']); ?></p>
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
    </div>

    <!-- tab 2 -->
    <div class="tab-pane" id="tab2-content">
    <section id="folder-section">
    <h4>Organize your list!</h4><br>
    <h5>Create New Folder</h5>
    <div class="add-folder">
        <form id="add-folder-form" method="POST">
            <input type="text" id="folder-name" name="folder_name" placeholder="Add Folder" required />
            <button type="submit" name="createFolder">Add</button>
        </form>
    </div>

    <hr />
    <div class="folders-container" id="folders-container">
        <?php
        if (!empty($folders)) {
            foreach ($folders as $folder) {
                
                echo '<div class="folder-icon" data-folder-id="' . $folder['folder_id'] . '">';
                echo '<img src="img/icons8-folder-64.png" alt="">'; 
                echo '<p>' . htmlspecialchars($folder['folder_name']) . '</p>'; 
                echo '</div>';
            }
        } else {
            echo '<p>No folders found.</p>';
        }
        ?>
    </div>
</section>

           
          
          
        <section id="folder-content" style="display:none;">
    <div class="folder-header">
        <button id="back-btn">&larr; Back to Folders</button>
        <button id="delete-folder-btn" class="delete-folder">Delete Folder</button>
    </div>

    <hr />
    <form method="POST" action="" enctype="multipart/form-data">
    <div id="add-task-form">
        <div class="right-task-form">
         
            <input type="text" id="task-title" placeholder="Note Title" name="title" required />
            
          
            <textarea id="task-description" placeholder="Note Description" name="note" required></textarea>
        </div>

        <div class="due-date-task-form">
            <label for="due-date-folder">Deadline</label><br>
            <input type="date" id="task-due-date" name="task-due-date" required /><br>
            <label for="image-note">Upload Image</label><br>
            <input type="file" id="task-image" name="task-image" accept="image/*"><br>
        </div>

       
        <input type="hidden" id="folder-id" name="folder-id" value="">

        <button type="submit" id="add-task-btn" name="addNotePrem">Add Task</button>
    </div>
</form>




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
                    <button type="button" class="complete-note-btn mark-complete-btn" data-note-id="<?php echo $note['note_id']; ?>">
                            <i class='bx bx-check-circle'></i> 
                        </button>
                    </div>
                    <div class="task-box-top">
                        <h3><?php echo ($note['title']); ?></h3>
                        <p><?php echo ($note['note']); ?></p>
                    </div>
                    <div class="task-box-bottom">
                        <div class="task-due">
                        <p>Due Date: September 30, 2024</p><br>
                        </div>
                        <div class="task-actions">
            
                  
                        <button type="button" class="delete-note-btn" data-note-id="<?php echo $note['note_id']; ?>">
                            <i class='bx bxs-trash'></i>


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
                <form action="" method="POST">
                    <label for="name">Name</label><br>
                    <input type="text" name="name" value="<?php echo $user['u_name']; ?>"><br>

                    <label for="email">Email</label><br>
                    <input type="text" name="email" value="<?php echo $user['email']; ?>"><br>

                    <label for="username">Username</label><br>
                    <input type="text" name="username" value="<?php echo $user['username']; ?>"><br>

                    <label for="password">Password</label><br>
                    <input type="password" name="password" placeholder="Enter new password"><br><br>

                    <button type="submit" name="updateProfile">Edit Profile</button>
                </form>
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
            
                    <button onclick="window.location.href='payment.php'">Get Plan</button>

                </div>
            </div>
            
        </section>
        
    </div>
  
    <script src="js/dashboard.js">
        
    </script>
    
</body>
</html>
