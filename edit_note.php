<?php
    session_start();
    include("include/connection.php"); 
    if (!isset($_SESSION['u_id'])) {
        echo "User is not logged in.";
        exit;
    }

    $u_id = $_SESSION['u_id'];

    if (isset($_GET['note_id'])) {
        $note_id = $_GET['note_id'];


        $stmt = $conn->prepare("SELECT * FROM note WHERE note_id = :note_id AND u_id = :u_id");
        $stmt->bindValue(':note_id', $note_id);
        $stmt->bindValue(':u_id', $u_id);
        $stmt->execute();
        
        $note = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$note) {
            echo "Note not found or you do not have permission to edit this note.";
            exit;
        }
    } else {
        echo "Invalid request.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editNote'])) {
        if (isset($_POST['note_id'], $_POST['title'], $_POST['note'])) {
            $note_id = $_POST['note_id'];
            $title = $_POST['title'];
            $noteContent = $_POST['note'];

            
            $stmt = $conn->prepare("UPDATE note SET title = :title, note = :note WHERE note_id = :note_id AND u_id = :u_id");
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':note', $noteContent);
            $stmt->bindValue(':note_id', $note_id);
            $stmt->bindValue(':u_id', $u_id);

           
            if ($stmt->execute()) {
                echo "Note updated successfully.";
                header("Location: dashboard.php");
                exit;
            } else {
          
                echo "Error updating the note: ";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/icons8-to-do-50.png" type="image/x-icon">
    <title>Edit Note</title>
    <link rel="stylesheet" href="css/style-user.css"> 
</head>
<body>

    <div class="edit-note-container">

    <h1>Edit Note</h1>
    <form method="POST" action="">
        <input type="hidden" name="note_id" value="<?php echo ($note['note_id']); ?>">

        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo ($note['title']); ?>" required>

        <label for="note">Note</label>
        <textarea id="note" name="note" rows="5" required><?php echo ($note['note']); ?></textarea>

        <button type="submit" name="editNote">Save Changes</button>
        <a href="dashboard.php" class="cancel-button">Cancel</a>
    </form>
</div>

</body>
</html>
