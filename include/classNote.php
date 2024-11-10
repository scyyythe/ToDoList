<?php
class NoteManager {
    private $conn;
    private $u_id;

    public function __construct($dbConnection, $userId) {
        $this->conn = $dbConnection;
        $this->u_id = $userId;
    }

    public function addNote($title, $note) {
        $status = 'Pending';
        $statement = $this->conn->prepare("INSERT INTO note (u_id, title, note, status) VALUES (:u_id, :title, :note, :status)");
        $statement->bindValue(':u_id', $this->u_id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':note', $note);
        $statement->bindValue(':status', $status);

        return $statement->execute();
    }
    
    public function updateNote($noteId, $title, $note) {
        $statement = $this->conn->prepare("UPDATE note SET title = :title, note = :note WHERE note_id = :note_id AND u_id = :u_id");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':note', $note);
        $statement->bindValue(':note_id', $noteId);
        $statement->bindValue(':u_id', $this->u_id);
        return $statement->execute();
    }
    

    public function getPendingNotes() {
        $statement = $this->conn->prepare("SELECT note_id, title, note FROM note WHERE u_id = :u_id AND status = 'Pending'");
        $statement->bindValue(':u_id', $this->u_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    

    
    public function getCompletedNotes() {
        $statement = $this->conn->prepare("SELECT title, note FROM note WHERE u_id = :u_id AND status = 'Completed'");
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

 
    public function uploadNote($file, $title, $note, $folder_id = null, $due_date = null) {
        // Create uploads folder if it doesn't exist
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Handle file upload if a file is provided
        $imagePath = '';
        if ($file && $file['tmp_name']) {
            $imagePath = 'uploads/' . $this->randomString(8) . '/' . $file['name'];
            if (!is_dir(dirname($imagePath))) {
                mkdir(dirname($imagePath), 0777, true);
            }
            move_uploaded_file($file['tmp_name'], $imagePath);
        }

        // Default note status and user ID
        $status = 'Pending';
        $u_id = $_SESSION['u_id'];

        // Prepare the SQL query to insert the note into the database
        $statement = $this->conn->prepare("INSERT INTO note (u_id, title, note, folder_id, image, due_date, status) 
                                          VALUES (:u_id, :title, :note, :folder_id, :image, :due_date, :status)");

        // Bind the parameters to the query
        $statement->bindValue(':u_id', $u_id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':note', $note);
        $statement->bindValue(':folder_id', $folder_id);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':due_date', $due_date);
        $statement->bindValue(':status', $status);

        // Execute the query and return the result
        if ($statement->execute()) {
            return 'Note uploaded successfully!';
        } else {
            return 'Failed to upload note. ' . implode(' ', $statement->errorInfo());
        }
    }


    
}




?>