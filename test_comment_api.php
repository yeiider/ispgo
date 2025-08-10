<?php

/**
 * Test script to verify the ticket comment API endpoint
 * This script demonstrates how to create a comment via the API
 */

// API endpoint URL (adjust the base URL as needed)
$baseUrl = 'http://localhost:8000/api/v1';
$ticketId = 1; // Replace with an actual ticket ID
$endpoint = $baseUrl . '/tickets/' . $ticketId . '/comments';

// Comment data to send
$commentData = [
    'content' => 'This is a test comment created via API'
];

// Convert data to JSON
$jsonData = json_encode($commentData);

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt_array($ch, [
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $jsonData,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        // Add authentication header if needed
        // 'Authorization: Bearer YOUR_TOKEN_HERE'
    ],
]);

// Execute the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cURL
curl_close($ch);

// Display results
echo "HTTP Status Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

if ($httpCode === 201 || $httpCode === 200) {
    echo "✅ Comment created successfully!\n";
} else {
    echo "❌ Failed to create comment\n";
}

// Decode and display formatted response
$responseData = json_decode($response, true);
if ($responseData) {
    echo "\nFormatted Response:\n";
    print_r($responseData);
}
