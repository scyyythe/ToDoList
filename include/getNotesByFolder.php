<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("connection.php");
include("classNote.php");
include("classFolder.php");

$username = $_SESSION['username'];
$email = $_SESSION['email'];
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User'; 
$u_id = $_SESSION['u_id'];  
$plan = $_SESSION['accPlan'];

if (!isset($u_id)) {
    echo json_encode(['message' => 'User ID not set.']);
    exit;
}

if (isset($_POST['folder_id'])) {
    $folderId = $_POST['folder_id'];

    if (!isset($u_id)) {
        echo json_encode(['message' => 'User ID not set.']);
        exit;
    }

    $noteManager = new NoteManager($conn, $u_id);
    $notes = $noteManager->getNotesByFolder($folderId);
    $response = [];

    if (is_array($notes) && count($notes) > 0) {
        $response['tasks'] = [];  // Initialize tasks array

        foreach ($notes as $note) {
            $response['tasks'][] = [
                'task_id' => $note['note_id'],
                'task_name' => $note['title'],
                'task_folder' => $note['folder_name'],
                'task_description' => $note['note'],
                'task_deadline' => $note['deadline'],
                'task_image' => $note['image']
            ];
        }
    } else {
        $response['tasks'] = [];  
        $response['message'] = 'No notes found in this folder.'; 
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;

} else {
    echo json_encode(['message' => 'No folder selected.']);
}
?>
