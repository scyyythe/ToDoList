<?php

class accountManage {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

  
    public function updateUser($user_id, $name, $email, $username, $password = null) {
        $sql = "UPDATE accounts SET u_name = :name, email = :email, username = :username";
        
      
        if ($password) {
            $password = password_hash($password, PASSWORD_DEFAULT); 
            $sql .= ", password = :password";
        }

        $sql .= " WHERE u_id = :user_id";

        $statement = $this->conn->prepare($sql);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':user_id', $user_id);

        
        if ($password) {
            $statement->bindValue(':password', $password);
        }

        return $statement->execute(); 
    }
    
    public function updateUserStatus($user_id, $status) {
        $statement = $this->conn->prepare("UPDATE accounts SET u_status = :status WHERE u_id = :user_id");
        $statement->bindValue(':status', $status);
        $statement->bindValue(':user_id', $user_id);
        
        return $statement->execute(); 
    }
    
    public function deleteUser($user_id) {
        $statement = $this->conn->prepare("DELETE FROM accounts WHERE u_id = :user_id");
        $statement->bindValue(':user_id', $user_id);
        
        return $statement->execute(); 
    }

    public function getPendingAccounts() {
        $statement = $this->conn->prepare("SELECT * FROM accounts WHERE u_status = 'pending'");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getActiveAccounts() {
        $statement = $this->conn->prepare("SELECT * FROM accounts WHERE u_status = 'active'");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserInfo($user_id) {
        $statement = $this->conn->prepare("SELECT * FROM accounts WHERE u_id = :user_id");
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Assuming $this->conn is your database connection

public function getUserPlan($username) {
    $statement = $this->conn->prepare("SELECT plan FROM accounts WHERE username = :username LIMIT 1");
    $statement->bindValue(':username', $username);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['plan'] : 'Basic'; // Return 'Basic' if not found
}

  
    
}

?>