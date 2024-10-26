<?php

session_start();
require_once 'include/connection.php';

if (!isset($_SESSION['u_id'])) {
    header("Location: login.php"); 
    exit();
}

$u_id = $_SESSION['u_id'];


if (isset($_GET['paymentId'])) {
    $paymentId = $_GET['paymentId'];

    $statement = $conn->prepare("UPDATE accounts SET plan = 'Premium' WHERE u_id = :u_id");
    $statement->execute(['u_id' => $u_id]);


    echo "<h3>Thank you! Your payment was successful.</h3>";
    echo "<p>Your account has been upgraded to Premium.</p>";
} else {
    echo "<h3>Payment Failed</h3>";
    echo "<p>There was an error processing your payment. Please try again.</p>";
}
?>
