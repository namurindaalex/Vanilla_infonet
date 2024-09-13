<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=VANILLA_INFONET', 'root', '');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = isset($_POST['message']) ? $_POST['message'] : null;

    // Ensure a message is provided
    if (empty($message)) {
        echo "Please provide a message.";
        exit;
    }

    // Retrieve phone numbers from the Details table
    $phoneQuery = "SELECT PHONE_NUMBER FROM Details";
    $stmt = $pdo->prepare($phoneQuery);
    $stmt->execute();
    $phoneNumbers = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Africa's Talking API details
    $username = 'agritech_info'; // Replace with your Africa's Talking username
    $apiKey = 'atsk_d2e940f8f9681b4aac64d62916824a19eb75d3c4fd07778e1d4fa7737fa5d25e12464903'; // Replace with your actual API key
    $apiUrl = 'https://api.africastalking.com/version1/messaging';

    // Iterate over the phone numbers and send the message
    foreach ($phoneNumbers as $phoneNumber) {
        // Create the data for the POST request
        $postData = [
            'username' => $username,
            'to' => $phoneNumber,
            'message' => $message
        ];

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apiKey: ' . $apiKey,
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        // Execute the request and capture the response
        $response = curl_exec($ch);
        curl_close($ch);

        // Handle the response
        if ($response === false) {
            echo "Message Sent to $phoneNumber.<br>";
        } else {
            $responseDecoded = json_decode($response, true);
            if (isset($responseDecoded['SMSMessageData']['Recipients']) && count($responseDecoded['SMSMessageData']['Recipients']) > 0) {
                echo "Message sent successfully to $phoneNumber.<br>";
            } else {
                $errorMessage = isset($responseDecoded['SMSMessageData']['Message']) ? $responseDecoded['SMSMessageData']['Message'] : "Unknown error";
                echo "Message Sent to $phoneNumber: $errorMessage<br>";
            }
        }
    }
    exit;
}
?>
