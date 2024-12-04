<?php
require 'vendor/autoload.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

\Stripe\Stripe::setApiKey('sk_test_51QRX5jAzOyrQR19uJbokwWf64pWC9Hq5ZqyqXSMPumyVGK4OA1MK0TcKmK9Kkym8XHS2nCtfuEiIUSGyoEEHEPHm00EpR2znDG'); 


$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['amount'])) {
    $amount = $data['amount'];
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Missing required param: amount.']);
    exit;
}

try {
    // Create a PaymentIntent with the specified amount
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'php',  // Use PHP currency
    ]);

    // Send the client secret back to the frontend
    header('Content-Type: application/json');
    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Return error if payment intent creation fails
    header('Content-Type: application/json');
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
exit();
?>