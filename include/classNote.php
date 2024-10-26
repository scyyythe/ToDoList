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

   
    
}




?>