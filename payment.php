<?php

include 'include/connection.php'; 


session_start();


$name = $_SESSION['name']; 
$email = $_SESSION['email']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="img/paypal-logo-24 (1).png" type="image/x-icon">
    <link rel="stylesheet" href="css/style-user.css">

</head>
<body>
<div class="containerPayment">
        <h2>Review Your Details</h2>
        
        <div class="review-section">
            <p><strong>Name:</strong> <?php echo $name; ?></p> 
            <p><strong>Email:</strong> <?php echo $email ?></p>
        </div>

        <h3>Premium Plan Features</h3>
        <ul class="feature-list">
            <li>Set Deadlines (Date & Time)</li>
            <li>Upload Images to your list</li>
            <li>Create Folders to organize lists</li>
        </ul>
        
        <div class="button-container">
            <div id="paypal-button-container"></div>
        </div>
    </div>

    <script src="https://www.paypal.com/sdk/js?client-id=AZpzkDX9qioqwzHDm1aGEwXRo3D5nbs_6FTef6ztWlJ7cJV2CfeXZ41UM1Zr6uXupefigZosIG_wWSKh&currency=PHP"></script>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '100' 
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    
                    window.location.href = 'success.php?paymentId=' + data.orderID;
                });
            },
            onError: function(err) {
                console.error('PayPal Checkout error:', err);
                alert('An error occurred during the transaction. Please try again.');
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
