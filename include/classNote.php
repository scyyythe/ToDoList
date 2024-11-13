<?php
class NoteManager {
    private $conn;
    private $u_id;

    public function __construct($dbConnection, $userId) {
        $this->conn = $dbConnection;
        $this->u_id = $userId;
    }

    public function addNote($title, $note, $deadline) {
        $status = 'Pending';
 
        $statement = $this->conn->prepare("INSERT INTO note (u_id, title, note, status, deadline) VALUES (:u_id, :title, :note, :status, :deadline)");
    
        $statement->bindValue(':u_id', $this->u_id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':note', $note);
        $statement->bindValue(':status', $status);
        $statement->bindValue(':deadline', $deadline);  
    
        return $statement->execute();
    }
    
    
    public function updateNote($noteId, $title, $note, $deadline) {
        $statement = $this->conn->prepare("UPDATE note SET title = :title, note = :note, deadline=:deadline WHERE note_id = :note_id AND u_id = :u_id");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':note', $note);
        $statement->bindValue(':deadline', $deadline);
        $statement->bindValue(':note_id', $noteId);
        $statement->bindValue(':u_id', $this->u_id);
        return $statement->execute();
    }
    public function archivedNote($note_id) {
        $status = 'deleted';  
        $statement = $this->conn->prepare("UPDATE note SET status = :status WHERE note_id = :note_id");
        $statement->bindValue(':status', $status);
        $statement->bindValue(':note_id', $note_id);
        
        return $statement->execute();
    }
    
    public function restoreNote($note_id) {
        $status = 'Pending';
        $statement = $this->conn->prepare("UPDATE note SET status = :status WHERE note_id = :note_id AND u_id = :u_id");
        $statement->bindValue(':status', $status);
        $statement->bindValue(':note_id', $note_id);
        $statement->bindValue(':u_id', $this->u_id);
    
        return $statement->execute();
    }
    
    public function getDeletedNotes() {
        $statement = $this->conn->prepare("SELECT * FROM note WHERE status = 'deleted' AND u_id = :u_id");
        $statement->bindValue(':u_id', $this->u_id);
        $statement->execute();
    
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPendingNotes() {
        $statement = $this->conn->prepare("SELECT note_id, title,deadline,image,note,folder_id FROM note WHERE u_id = :u_id AND status = 'Pending'");
        $statement->bindValue(':u_id', $this->u_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
   
    
    public function getCompletedNotes() {
        $statement = $this->conn->prepare("SELECT * FROM note WHERE u_id = :u_id AND status = 'Completed'");
        $statement->bindValue(':u_id', $this->u_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markNoteAsCompleted($noteId) {
        $statement = $this->conn->prepare("UPDATE note SET status = 'Completed' WHERE note_id = :note_id AND u_id = :u_id");
        $statement->bindValue(':note_id', $noteId);
        $statement->bindValue(':u_id', $this->u_id);
        return $statement->execute();
       
    }

    public function deleteNote($noteId) {
        $stmt = $this->conn->prepare("DELETE FROM note WHERE note_id = :note_id AND u_id = :u_id");
        $stmt->bindValue(':note_id', $noteId);
        $stmt->bindValue(':u_id', $this->u_id);
        return $stmt->execute();
    }

    public function deleteAllCompletedNotes() {
        $stmt = $this->conn->prepare("DELETE FROM note WHERE u_id = :u_id AND status = 'Completed'");
        $stmt->bindValue(':u_id', $this->u_id);
        return $stmt->execute();
    }
    
    
    public function getUserFolders() {
        $statement = $this->conn->prepare("SELECT folder_name FROM folder_tbl WHERE u_id = :u_id");
        $statement->bindValue(':u_id', $this->u_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingCount() {
        $statement = $this->conn->prepare("SELECT COUNT(*) FROM note WHERE u_id = :u_id AND status = 'Pending'");
        $statement->bindValue(':u_id', $this->u_id);
        $statement->execute();
        return $statement->fetchColumn();
    }
    
    public function getCompletedCount() {
        $statement = $this->conn->prepare("SELECT COUNT(*) FROM note WHERE u_id = :u_id AND status = 'Completed'");
        $statement->bindValue(':u_id', $this->u_id);
        $statement->execute();
        return $statement->fetchColumn();
    }
    


    private function randomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

 
    public function uploadNote($file, $title, $note, $folder_id = null, $deadline = null) {
        
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

      
        $imagePath = '';
        if ($file && $file['tmp_name']) {
            $imagePath = 'uploads/' . $this->randomString(8) . '/' . $file['name'];
            if (!is_dir(dirname($imagePath))) {
                mkdir(dirname($imagePath), 0777, true);
            }
            move_uploaded_file($file['tmp_name'], $imagePath);
        }

       
        $status = 'Pending';
        $u_id = $_SESSION['u_id'];

       
        $statement = $this->conn->prepare("INSERT INTO note (u_id, title, note, folder_id, image, deadline, status) 
                                          VALUES (:u_id, :title, :note, :folder_id, :image, :deadline, :status)");

       
        $statement->bindValue(':u_id', $u_id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':note', $note);
        $statement->bindValue(':folder_id', $folder_id);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':deadline', $deadline);
        $statement->bindValue(':status', $status);

        
        if ($statement->execute()) {
            return 'Note uploaded successfully!';
        } else {
            return 'Failed to upload note. ' . implode(' ', $statement->errorInfo());
        }
    }


    
}




?>