<?php
include 'ReactiveRateLimiter.php';

// Allow 5 requests every 5 seconds
$rateLimiter = new ReactiveRateLimiter(5, 5.0); 	

// Simulate periodic requests
while (true) {
    $rateLimiter->sendRequest();
    usleep(200000); // 0.2 seconds between requests
}
?>
