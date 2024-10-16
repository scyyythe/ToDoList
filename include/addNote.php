<?php

if (isset($_POST['addNote'])) {
    $result = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $title = $_POST['title'];
        $note = $_POST['note'];
        $u_id = $_SESSION['u_id'];
        $status = 'Pending';

        $statement = $conn->prepare("INSERT INTO note (u_id, title, note, status) VALUES (:u_id, :title, :note, :status)");
        $statement->bindValue(':u_id', $u_id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':note', $note);
        $statement->bindValue(':status', $status);
        
        $result = $statement->execute();

        if ($result) {
        
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
          
            echo json_encode(['success' => false, 'message' => 'Failed to add note.']);
            exit;
        }
    }
}


?>