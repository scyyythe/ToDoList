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
            $statement = $this->connection->prepare("SELECT COUNT(*) AS active_notes FROM note WHERE folder_id = :folder_id AND status != 'deleted'");
            $statement->bindValue(':folder_id', $folderId);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
    
            if ($result['active_notes'] == 0) {
                // Archive the folder
                $updateStatement = $this->connection->prepare("UPDATE folder_tbl SET folder_status = 'Deleted' WHERE folder_id = :folder_id AND u_id = :u_id");
                $updateStatement->bindValue(':folder_id', $folderId);
                $updateStatement->bindValue(':u_id', $this->userId);
                return $updateStatement->execute() ? "archived" : false;
            } else {
                // Folder cannot be archived because it contains active notes
                return "cannot_archive";
            }
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