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
    <link rel="shortcut icon" href="img/icons8-stripe-24.png" type="image/x-icon">
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
        <button id="stripe-button">Pay with Stripe</button>
    </div>


    <div id="card-element">
       
    </div>
    <div id="card-errors" role="alert"></div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('pk_test_51QRX5jAzOyrQR19uDKK0RwteRqdrBVv8wN90cagqdd6RBQYNbSTaLXVZQYvk07ZR4XzPeyFXrOpmGBf7QRVyzwkL00kakshDvB');
    const elements = stripe.elements();

    // Create an instance of the card element
    const card = elements.create('card', {
        hidePostalCode: true 
    });

    card.mount('#card-element');  
    document.getElementById('stripe-button').addEventListener('click', async function() {
    // Create a token with the card details
    const {token, error} = await stripe.createToken(card);

    if (error) {
        document.getElementById('card-errors').textContent = error.message;
    } else {
        fetch('create-payment-intent.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        amount: 10000, 
        token: token.id  
    }),
})
.then(response => response.text())  
.then(text => {
    console.log(text);  
    try {
        const paymentIntent = JSON.parse(text);  // Manually parse it if it's valid JSON
        if (paymentIntent.error) {
            // Show error message if something goes wrong
            alert('Error: ' + paymentIntent.error);
        } else {
            const clientSecret = paymentIntent.clientSecret;  // Correctly extract clientSecret

            stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,  // Use the card object here
                    billing_details: {
                        name: '<?php echo $name; ?>', 
                        email: '<?php echo $email; ?>',
                    }
                }
            }).then(result => {
                if (result.error) {
                  
                    alert('Payment failed: ' + result.error.message);
                } else if (result.paymentIntent.status === 'succeeded') {
                
                    window.location.href = 'success.php?paymentId=' + result.paymentIntent.id;
                }
            });
        }
    } catch (e) {
        console.error('Invalid JSON:', e);
        alert('Error: Invalid response from server');
    }
});

    }
});

</script>

</body>
</html>
