<?php

require 'connection.php'; 

$response = [];


if (!isset($_SESSION['u_id'])) {
    $response['success'] = false;
    $response['message'] = 'User is not logged in.';
    echo json_encode($response);
    exit();
}

$u_id = $_SESSION['u_id']; 


$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
     if (isset($data['action']) && $data['action'] === 'delete' && isset($data['note_id'])) {
        $noteId = $data['note_id'];

        try {
            $stmt = $conn->prepare("DELETE FROM note WHERE note_id = :note_id AND u_id = :u_id");
            $stmt->bindValue(':note_id', $noteId);
            $stmt->bindValue(':u_id', $u_id);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Note deleted successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to delete note.';
            }
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['message'] = 'Error: ' . $e->getMessage();
        }
    }

    // delete all completed task
    else if (isset($data['action']) && $data['action'] === 'delete') {
       
        try {
            $stmt = $conn->prepare("DELETE FROM note WHERE u_id = :u_id AND status = 'Completed'");
            $stmt->bindValue(':u_id', $u_id);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'All completed notes deleted successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to delete completed notes.';
            }
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['message'] = 'Error: ' . $e->getMessage();
        }
    } 
    
    else if (isset($data['note_id'])) {
        

        // update note status as complete
        $noteId = $data['note_id'];

        try {
            $stmt = $conn->prepare("UPDATE note SET status = 'Completed' WHERE note_id = :note_id AND u_id = :u_id");
            $stmt->bindValue(':note_id', $noteId);
            $stmt->bindValue(':u_id', $u_id);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Note marked as completed successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to mark the note as completed.';
            }
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['message'] = 'Error: ' . $e->getMessage();
        }

    }else {
        $response['success'] = false;
        $response['message'] = 'Invalid action.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
