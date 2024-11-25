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
$plan = $_SESSION['accPlan'];


$accountInfo= new accountManage($conn,$u_id);
$userPlan = $accountInfo->getUserPlan($username);


$noteManager = new NoteManager($conn, $u_id);
$folderManager = new FolderManager($conn, $u_id);

$pendingCount = $noteManager->getPendingCount();
$completedCount = $noteManager->getCompletedCount();

$folders = $folderManager->getUserFolders();

$deletedNotes=$noteManager->getDeletedNotes();
$notes = $noteManager->getPendingNotes();
$completedTasks = $noteManager->getCompletedNotes();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['addNote'])) {
        $title = $_POST['title'];
        $note = $_POST['note'];
        $deadline = $_POST['deadline'];
        if ($noteManager->addNote($title, $note,$deadline)) {
            header('Location: dashboard.php');
            exit;
        }
        $_SESSION['error'] = "Failed to add note.";
    }elseif (isset($_POST['addNotePrem'])) {
        $title = $_POST['title'];
        $note = $_POST['note'];
        $folder_id = !empty($_POST['folder-id']) ? $_POST['folder-id'] : null;
        $due_date = $_POST['deadline'];
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

        if ($noteManager->archivedNote($noteId)) {
            header('Location: dashboard.php'); 
            exit();
        }
        $_SESSION['error'] = "Failed to delete note.";
    }elseif (isset($_POST['deletePermanent'])) {
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
            header('Location: dashboard.php'); 
            exit(); 
        } else {
            $_SESSION['error'] = "Failed to create folder.";
        }
    }elseif (isset($_POST['deleteFolder'])) {
        $folder_id = $_POST['folder_id'];

        if ($folderManager-> deleteFolder($folder_id)) {
            header('Location: dashboard.php'); 
            exit();
        }
        $_SESSION['error'] = "Failed to delete folder.";
    }elseif (isset($_POST['restoreNote'])) {
        $note_id = $_POST['note_id'];
    
        if ($noteManager->restoreNote($note_id)) { 
            header('Location: dashboard.php?status=restored');
            exit();
        }
        $_SESSION['error'] = "Failed to restore note.";
    }
}

$userManager = new accountManage($conn);
if ($_SERVER['REQUEST_METHOD'] == "POST"&& isset($_POST['updateProfile'])) {
    $user_id = $_SESSION['u_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $status = "active"; 
    $plan = $_POST['plan']; 

    if ($userManager->updateUser($user_id, $name, $email, $username, $status, $plan)) {
        header('Location: ' . $_SERVER['PHP_SELF']); 
        exit();
    } else {
        echo "<p>Failed to update profile.</p>";
    }
}

// RETRIEVE NOTE EDIT
$user = $userManager->getUserInfo($u_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editNote'])) {
    if (isset($_POST['note_id'], $_POST['title'], $_POST['note'])) {
        $note_id = $_POST['note_id'];
        $title = $_POST['title'];
        $noteContent = $_POST['note'];
        $deadline = $_POST['deadline'];
        $file = isset($_FILES['task-image']) ? $_FILES['task-image'] : null;

        if ($noteManager->updateNote($note_id, $title, $noteContent, $deadline, $file)) {
            echo "Note updated successfully.";
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Error updating the note.";
        }
    }
}



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
            <li><a href="#" class="logout-btn" onclick="showLogoutModal()">Sign Out</a></li>

        </ul>
    </header>

    <div class="wrapper">


    <div class="modal" id="logoutModal">
        <div class="modal-content">
            <h4>Are you sure you want to <br>log out?</h4>
            <div class="modal-buttons">
                <br>
                <button class="btn-cancel" onclick="hideLogoutModal()">Cancel</button>
                <button class="btn-confirm" onclick="confirmLogout()">Log Out</button>
            </div>
        </div>
    </div>

        <div id="notePopup" class="note-popup">
        <div class="note-popup-content">
            <span class="close-popup" onclick="closePopup()">&times;</span>
            
            <div class="leftPop">
                 <img id="popupImage" src="" alt="Note Image">
            </div>
            <div class="rightPop">
                <h3 id="popupTitle"></h3>
                <h5>Folder Name: <span style="color: blueviolet;" id="folderName"></span></h5><br>
                <p id="popupNote"></p><br>
                <p >Remaining Time: <span style="color: red;" id="popupCountdown"></span></p>

            </div>
            
        </div>
    </div>
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
            <div class="left-dash-list" onclick="showPopup('<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['folder_name']); ?>', '<?php echo addslashes($note['note']); ?>', '<?php echo $note['deadline']; ?>', '<?php echo htmlspecialchars($note['image'], ENT_QUOTES); ?>')">


                    <div class="imageDisplay">
                        <?php if (!empty($note['image'])): ?>
                            <img src="<?php echo htmlspecialchars($note['image']); ?>" alt="<?php echo htmlspecialchars($note['title']); ?>" >
                        <?php endif; ?>
                    </div>

                    <h3><?php echo htmlspecialchars($note['title']); ?></h3>

                    <?php if (!empty($note['folder_name'])) { ?>
                        <h5>Folder Name: <span style="color: blueviolet;"><?php echo htmlspecialchars($note['folder_name']); ?></span></h5><br>
                    <?php } ?>

                    <p>
                        <?php   
                            $noteContent = preg_replace('/\s+/', ' ', trim($note['note']));
                    
                            $words = explode(' ', $noteContent);
                            echo htmlspecialchars(implode(' ', array_slice($words, 0, 30)), ENT_QUOTES);
                            
                            if (count($words) > 30) {
                                echo '...';
                            }
                        ?>
                    </p>
                </div>

                <div class="right-dash-list">
    <p>Deadline: <span style="color: red;" class="countdown" data-deadline="<?php echo $note['deadline']; ?>"></span></p><br>

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


    <form method="POST" action="#" onsubmit="event.preventDefault(); openModal('<?php echo addslashes($note['note_id']); ?>', '<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['note']); ?>', '<?php echo $note['deadline']; ?>', '<?php echo addslashes($note['image']); ?>')">
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

        <!-- EDIIITT NOTEEE -->
        <div id="popupOverlay" class="popup-overlay" style="display:none;">
    <div id="editNoteModal" class="edit-note-container">
        <span class="close-modal" onclick="closeModal()">×</span>
        <h1>Edit Note</h1>

     
        <form method="POST" action="" enctype="multipart/form-data">
    <div class="inputEdit">
        <input type="hidden" name="note_id" id="note_id" value="">

        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>

        <label for="note">Note</label>
        <textarea id="note" name="note" rows="5" required></textarea>

        <label for="deadline">Deadline</label>
        <input type="time" id="modalDeadline" name="deadline" required>
        <br>
        <button class="saveChanges" type="submit" name="editNote">Save Changes</button>
        <a href="javascript:void(0);" class="cancel-button" onclick="closeModal()">Cancel</a>
    </div>

        <div class="imageNote">
            <div class="image-content">
                <label for="task-image">Change Image</label><br>
                <img id="image-preview" src="<?php echo htmlspecialchars($note['image']); ?>" alt="Note Image">
                <input type="file" id="task-image" name="task-image" accept="image/*" onchange="previewImage(event)"><br>
            </div>
        </div>

</form>


    </div>
</div>




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

            <div class="upper-tab"> 

            <div class="title-tab">
                <label for="title">Title</label><br>
                <input type="text" name="title" placeholder="Title" required><br>
            </div>
                
            <div class="deadline-tab">
                <label for="deadline">Set Deadline</label><br>
                <input type="time" name="deadline" required><br>
            </div>
            
            </div>
               
                
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
                <a class="viewTrashbtn" onclick="showTrashPopup()">View Trash</a>
            </div>

            <div class="dash-list-container">
    <?php if (!empty($notes)) { ?>
        <?php foreach ($notes as $note) { ?>
            <div class="dash-list">
            <div class="left-dash-list" onclick="showPopup('<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['folder_name']); ?>', '<?php echo addslashes($note['note']); ?>', '<?php echo $note['deadline']; ?>', '<?php echo htmlspecialchars($note['image'], ENT_QUOTES); ?>')">
                    <div class="imageDisplay">
                        <?php if (!empty($note['image'])): ?>
                            <img src="<?php echo htmlspecialchars($note['image']); ?>" alt="<?php echo htmlspecialchars($note['title']); ?>" >
                        <?php endif; ?>
                    </div>

                    <h3><?php echo htmlspecialchars($note['title']); ?></h3><br>
                    
                    <?php if (!empty($note['folder_name'])) { ?>
                        <h5>Folder Name: <span style="color: blueviolet;"><?php echo htmlspecialchars($note['folder_name']); ?></span></h5><br>
                    <?php } ?>
                    
                    <p>
                        <?php   
                            $noteContent = preg_replace('/\s+/', ' ', trim($note['note']));
                    
                            $words = explode(' ', $noteContent);
                            echo htmlspecialchars(implode(' ', array_slice($words, 0, 30)), ENT_QUOTES);
                            
                            if (count($words) > 30) {
                                echo '...';
                            }
                        ?>
                    </p>
                </div>

                <div class="right-dash-list">
    <p>Deadline: <span class="countdown" data-deadline="<?php echo $note['deadline']; ?>"></span></p><br>


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


    <form method="POST" action="#" onsubmit="event.preventDefault(); openModal('<?php echo addslashes($note['note_id']); ?>', '<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['note']); ?>', '<?php echo $note['deadline']; ?>', '<?php echo addslashes($note['image']); ?>')">
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

<!-- TRASHH CONTAINER -->
<div id="trashContainerOverlay" onclick="closeTrashPopup()"></div>

<div id="trashContainer">
    <h3>Trash - Deleted Notes</h3>
    <?php if (!empty($deletedNotes)) { ?>
        <?php foreach ($deletedNotes as $note) { ?>
            <div class="trash-item">
                <h4><?php echo $note['title']; ?></h4>
                <p>
                        <?php 
                            $words = explode(' ', $note['note']); 
                            $limitedWords = array_slice($words, 0, 30);
                            echo implode(' ', $limitedWords);
                            if (count($words) > 30) echo '...'; 
                        ?>
                    </p>
                
                    <div class="btnTrash">
                        <form method="POST" action="">
                            <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                            <button type="submit" class="restoreNote" name="restoreNote">Restore</button>
                        </form>

                        <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this note?');">
                            <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                            <button type="submit" name="deletePermanent">Delete</button>
                        </form>
                    </div>

              
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No deleted notes available.</p>
    <?php } ?>
</div>


<!-- COMPLETEEED TASSKK -->
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
            <div class="dash-list" >
            <div class="left-dash-list" onclick="showPopup('<?php echo addslashes($task['title']); ?>', '<?php echo addslashes($task['note']); ?>', '<?php echo $task['deadline']; ?>', '<?php echo htmlspecialchars($task['image'], ENT_QUOTES); ?>')">
                    <h3><?php echo($task['title']); ?></h3>
                    <p>
                        <?php 
                            $words = explode(' ', $task['note']); 
                            $limitedWords = array_slice($words, 0, 30);
                            echo implode(' ', $limitedWords);
                            if (count($words) > 30) echo '...'; 
                        ?>
                    </p>
                    <div class="imageDisplay">
        <?php if (!empty($note['image'])): ?>
            <img src="<?php echo htmlspecialchars($note['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($note['title'], ENT_QUOTES); ?>" >
        <?php endif; ?>
    </div>
                </div>
                <div class="right-dash-list">
                   
                <form method="POST" action="">
                    <input type="hidden" name="note_id" value="<?php echo $task['note_id']; ?>">
                    <button type="submit" class="restore-task-btn" name="restoreNote">Restore</button>
                </form>

                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No completed tasks available.</p>
    <?php } ?>
</div>


            </section>



            <!-- VIEWW ALL TASKK  LINK-->
            <a href="#" id="allTask-link">View All Task</a>
        </div>
    </div>

    <!-- tab 2 -->
    <div class="tab-pane" id="tab2-content">
    <section id="folder-section">
    <h4>Organize your list!</h4><br>

    <?php if ($plan == '1001'): ?>
    <p>You need a Premium plan to create a folder.</p>
  <?php else: ?>
    <h5>Create New Folder</h5>
    <form id="add-folder-form" class="add-folder" method="POST">
            <input type="text" id="folder-name" name="folder_name" placeholder="Add Folder" required />
            <button type="submit" name="createFolder">Add</button>
        </form>

    <hr />

<div class="folders-container" id="folders-container">
<?php
if (!empty($folders)) {
    foreach ($folders as $folder) {
        echo '<div class="folder-icon" data-folder-id="' . $folder['folder_id'] . '" onclick="getFolderId(this)">';
        echo '<img src="img/icons8-folder-64.png" alt="">';
        echo '<p>' . ($folder['folder_name']) . '</p>';
        echo '</div>';
    }
}
?>

</div>
  <?php endif; ?>

</section>



           <!-- FOLDER CONTENT -->
          
        <section id="folder-content" style="display:none;">
    <div class="folder-header">
        <button id="back-btn">&larr; Back to Folders</button>

<div id="deleteContent" class="delete-content" style="display: none;">
    <div class="delete-box">
        <p>Are you sure you want to delete this folder?</p>
        <button id="deleteConfirm" class="deleteConfirm">Yes</button>
        <button id="deleteCancel" class="delete-button">No</button>
    </div>
</div>

<form method="POST" action="dashboard.php" id="delete-folder-form">
  <input type="hidden" name="folder_id" value="<?php echo $folder['folder_id']; ?>">
  <button type="button" id="delete-folder-btn" class="delete-button" onclick="showDeleteAlert()">Delete Folder</button>
</form>

    </div>
        
    <hr />
    <form method="POST" action="" enctype="multipart/form-data">
    <div id="add-task-form">
        <div class="right-task-form">
         
            <input type="text" id="task-title" placeholder="Note Title" name="title" required />
 
            <textarea id="task-description" placeholder="Note Description" name="note" required></textarea>
        </div>

        <div class="due-date-task-form">
        <div class="deadline-tab">
                <label for="deadline">Set Deadline</label><br>
                <input type="time" name="deadline" required><br>
            </div>
            <label for="image-note">Upload Image</label><br>
            <input type="file" id="task-image" name="task-image" accept="image/*"><br>
        </div>

       
        <input type="hidden" id="folder-id" name="folder-id" value="">

        <button type="submit" id="add-task-btn" name="addNotePrem">Add Task</button>
    </div>
</form>




    <hr />




    <!--FOLDER NOTE ONLY DISPLAAYYY -->
    <div class="all-task-container">
          <!-- DISPLAY SA MGA BOXES NAA SA JS KAY DI MO GANA ANG PHP SAG UNSAON YATAAAAA KAPOYAAAA -->
    </div>

</section>

    </div>
</div>

        </section>


        <!-- VIEW ALL TASK CONTAINER KATONG BOX BOX-->
        <section id="viewtaskContainer" style="display: none;">

            <div class="head-viewtastContainer">
                  <h3>All Task</h3>
                <p><b>Organize your task!</b></p>
            </div>
          
            <div class="all-task-container">

            <div class="task-list">
            
                        <?php foreach ($notes as $note) { ?>
                <div class="task-box" onclick="showPopup('<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['note']); ?>', '<?php echo $note['deadline']; ?>', '<?php echo htmlspecialchars($note['image'], ENT_QUOTES); ?>')">
                    
                    <div class="check-task" onclick="showPopup('<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['note']); ?>', '<?php echo $note['deadline']; ?>')">      
                    <form method="POST" action="dashboard.php">
                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                        <button type="submit" name="complete_note" class="complete-note-btn mark-complete-btn">
                            <i class='bx bx-check-circle'></i>
                        </button>
                    </form>

                    </div>
                    <div class="task-box-top">
                        <h3><?php echo ($note['title']); ?></h3>
                        <p>
                        <?php 
                            $words = explode(' ', $note['note']); 
                            $limitedWords = array_slice($words, 0, 30);
                            echo implode(' ', $limitedWords);
                            if (count($words) > 30) echo '...'; 
                        ?>
                    </p>

                    
                    </div>
                    <div class="task-box-bottom">
                        <div class="task-due">
                        <p>Deadline: <span class="countdown" data-deadline="<?php echo $note['deadline']; ?>"></span></p><br>
                        </div>
                        <div class="task-actions">
            
                  
                <form method="POST" action="dashboard.php" onsubmit="return confirm('Are you sure you want to delete this note?');">
                    <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                    <button type="submit" class="delete-note-btn" name="deleteNote">
                        <i class='bx bxs-trash'></i>
                    </button>
                </form>


                <form method="POST" action="#" onsubmit="event.preventDefault(); openModal('<?php echo addslashes($note['note_id']); ?>', '<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['note']); ?>', '<?php echo $note['deadline']; ?>')">
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
            <h3>Update Your Profile</h3>
            <p>Keep your account information up-to-date to enhance your experience on the platform.</p><br>
                <form action="" method="POST">
                    <label for="name">Name</label><br>
                    <input type="text" name="name" value="<?php echo $user['u_name']; ?>"><br>

                    <label for="email">Email</label><br>
                    <input type="text" name="email" value="<?php echo $user['email']; ?>"><br>

                    <label for="username">Username</label><br>
                    <input type="text" name="username" value="<?php echo $user['username']; ?>"><br>

                
                    <button type="submit" name="updateProfile">Save Changes</button>
                </form>
            </div>

                            <br>
            <div class="settings-right">
                <p>Account Status: <span><b><?php echo $user['plan_name']; ?></b></span></p>
            
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
    <script>
           function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

  
    function updateCountdown() {
        const countdownElements = document.querySelectorAll('.countdown');

        countdownElements.forEach(function(element) {
            
            const deadlineTime = element.getAttribute('data-deadline');  
            const currentTime = new Date();
            const deadlineParts = deadlineTime.split(':');
            const deadlineDate = new Date(currentTime.getFullYear(), currentTime.getMonth(), currentTime.getDate(), deadlineParts[0], deadlineParts[1], deadlineParts[2]);

         
            const timeDifference = Math.floor((deadlineDate - currentTime) / 1000);

            if (timeDifference > 0) {
                
                element.textContent = formatTime(timeDifference);
            } else {
                element.textContent = "Time's up!";
            }
        });
    }

    
    setInterval(updateCountdown, 1000);

    
    updateCountdown();
    </script>
</body>
</html>
