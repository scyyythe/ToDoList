<?php

if (isset($_POST['createFolder'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $folderName = $_POST['folder_name'];
        $u_id = $_SESSION['u_id'];

        if (!empty($folderName)) {
            
            $stmt = $conn->prepare("INSERT INTO folder_tbl (u_id, folder_name) VALUES (:u_id, :folder_name)");

          
            $stmt->bindValue(':u_id', $u_id);
            $stmt->bindValue(':folder_name', $folderName);

          
            if ($stmt->execute()) {
                header('Location: ' . $_SERVER['PHP_SELF']);
            } else {
               
            }
        } else {
           
        }
    }
}
?>
