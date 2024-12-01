# Reactive and Asynchronous Rate Limiter in PHP
A reactive and asynchronous rate limiter in PHP, designed to handle high-frequency requests efficiently, this implementation leverages pcntl signals to provide non-blocking and lock-free request handling. It is a lightweight solution ideal for applications requiring precise and dynamic control over incoming request rates.

## Features

- **Reactive and Asynchronous**:
  - Utilizes PHP's `pcntl` library to process incoming requests reactively.
  - Ensures non-blocking behavior, making it suitable for high-concurrency environments.
- **No Locking Required**:
  - Eliminates the need for semaphores, file locks, or databases.
  - Tracks requests entirely in memory for lightweight operation.
- **Dynamic Rate Management**:
  - Configurable maximum requests per time window.
  - Handles blocked requests reactively with customizable timeout durations.

## Code Overview

### Core Class

The implementation includes the `ReactiveRateLimiter` class, which:
1. Dynamically tracks requests in memory.
2. Reacts to incoming signals using `pcntl_signal` for efficient concurrency.
3. Implements rate-limiting logic to allow or block requests based on defined thresholds.

### Example Usage

```php
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
```
