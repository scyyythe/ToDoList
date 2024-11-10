<?php

class FolderManager {
    private $connection;
    private $userId;

    public function __construct($conn, $u_id) {
        $this->connection = $conn;
        $this->userId = $u_id;
    }

    public function createFolder($folderName) {
        if (!empty($folderName)) {
            $statement = $this->connection->prepare("INSERT INTO folder_tbl (u_id, folder_name) VALUES (:u_id, :folder_name)");
            $statement->bindValue(':u_id', $this->userId);
            $statement->bindValue(':folder_name', $folderName);
           

            return $statement->execute();
        }
        return false;
    }
    public function deleteFolder($folderId) {
   
        if (!empty($folderId)) {
           
            $statement = $this->connection->prepare("DELETE FROM folder_tbl WHERE folder_id = :folder_id AND u_id = :u_id");
            $statement->bindValue(':folder_id', $folderId);
            $statement->bindValue(':u_id', $this->userId); 
            return $statement->execute();
        }
        return false;
    }
    
    public function getUserFolders() {
        $statement = $this->connection->prepare("SELECT * FROM folder_tbl WHERE u_id = :u_id");
        $statement->bindValue(':u_id', $this->userId);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>