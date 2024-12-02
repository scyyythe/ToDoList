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

    //update the plan_id 
    $statement = $conn->prepare("UPDATE accounts SET plan_id = 1002 WHERE u_id = :u_id");
    $statement->execute(['u_id' => $u_id]);
    $_SESSION['accPlan'] = 1002;

    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime($start_date . ' +30 days'));

    // subscriptoin table insert
    $stmt = $conn->prepare("INSERT INTO subscription_tbl (start_date, end_date, plan_id, u_id, subscription_status) 
                           VALUES (:start_date, :end_date, 1002, :u_id, 'Active')");
    $stmt->execute([
        'start_date' => $start_date,
        'end_date' => $end_date,
        'u_id' => $u_id
    ]);
    $subscription_id = $conn->lastInsertId();  
    $payment_date = date('Y-m-d');  
    $payment_status = 'Successful';


    //insert to payments table
    $paymentStmt = $conn->prepare("INSERT INTO payment_tbl (subscription_id, date_payment, status) 
                                  VALUES (:subscription_id, :date_payment, :status)");
    $paymentStmt->execute([
        'subscription_id' => $subscription_id,
        'date_payment' => $payment_date,
        'status' => $payment_status
    ]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-user.css">
    <link rel="shortcut icon" href="img/icons8-to-do-50.png" type="image/x-icon">
    <title>Payment Success</title>
</head>
<body>

<div class="container">
    <div class="success-message">
        <h3>Thank you! Your payment was successful.</h3>
        <p>Your account has been upgraded to Premium.</p>
        <a href="dashboard.php" class="button">Go to Dashboard</a>
    </div>
</div>

</body>
</html>

<?php
} else {
    echo "<h3>Payment Failed</h3>";
    echo "<p>There was an error processing your payment. Please try again.</p>";
}
?>
