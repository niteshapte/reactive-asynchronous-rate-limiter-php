<?php
declare(ticks=1); // Enable asynchronous signal handling

class ReactiveRateLimiter {
	private int $limitForPeriod;
	private float $limitRefreshPeriod; 	// in seconds
	private array $requests = [];     			// Stores timestamps of requests
	private int $signal;              				// Custom signal for rate limiter
	private int $requestCount = 0;    		// Counter for total requests

    /**
     * Constructor for RateLimiter.
     * 
     * @param int $limitForPeriod Maximum number of requests allowed in the period.
     * @param float $limitRefreshPeriod Time period in seconds to refresh the limit.
     */
    public function __construct(int $limitForPeriod, float $limitRefreshPeriod) {
        $this->limitForPeriod = $limitForPeriod;
        $this->limitRefreshPeriod = $limitRefreshPeriod;

        // Define a custom signal for reactive rate limiting
        $this->signal = SIGUSR1;

        // Register signal handler
        pcntl_signal($this->signal, [$this, 'handleSignal']);
    }

    /**
     * Handle the custom signal.
     */
    public function handleSignal() {
        $this->requestCount++;
        if ($this->allowRequest()) {
            echo "Request #{$this->requestCount} allowed at " . date('H:i:s') . PHP_EOL;
        } else {
            echo "Request #{$this->requestCount} denied at " . date('H:i:s') . PHP_EOL;
        }
    }

    /**
     * Checks if a request can proceed within the rate limit.
     *
     * @return bool True if allowed, False otherwise.
     */
    private function allowRequest(): bool {
        $now = microtime(true);

        // Remove requests outside the current refresh window
        $this->requests = array_filter($this->requests, function ($timestamp) use ($now) {
            return ($now - $timestamp) <= $this->limitRefreshPeriod;
        });

        // Check if request can proceed
        if (count($this->requests) < $this->limitForPeriod) {
            $this->requests[] = $now; // Add the new request timestamp
            return true;
        }

        return false;
    }

    /**
     * Simulates an incoming request by sending a signal.
     */
    public function sendRequest() {
        posix_kill(posix_getpid(), $this->signal);
    }
}
?>

