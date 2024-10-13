<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "todo_db";


    try{
        $conn=new PDO("mysql:host=$servername;dbname=todo_db", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "Connection Failed ". $e->getMessage();
    }

?>
