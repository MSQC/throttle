<?php
namespace sideshow_bob\throttle;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Throttle implementation
 * Ban an identifier after a certain amount of requests in a given timeframe.
 * Forked from websoftwares/throttle
 * @link https://github.com/websoftwares/Throttle
 */
class Throttle
{
    private $storage;
    private $options;
    private $logger;

    /**
     * Throttle constructor.
     * @param StorageInterface $storage
     * @param array $options [optional] defaults: ["ban" => 5, "log" => 10, "timespan" => 86400]
     * @param LoggerInterface $logger [optional]
     */
    public function __construct(StorageInterface $storage, array $options = [], LoggerInterface $logger = null)
    {
        $this->storage = $storage;
        // merge the options with the default ones
        $this->options = array_merge(
            [
                "ban" => 5, // ban an identifier after 5 attempts
                "log" => 10, // log an identifier after 10 attempts
                "timespan" => 86400 // ban timespan in seconds
            ],
            $options
        );
        $this->logger = $logger instanceof LoggerInterface ? $logger : new NullLogger();
    }

    /**
     * Validate if an identifier has been banned.
     * This method saves data to a storage to track the identifier.
     * @param string $identifier
     * @return bool
     */
    public function validate($identifier)
    {
        // current attempts
        $attempts = $this->storage->increment($identifier, $this->options["timespan"]);
        if ($this->options["log"] !== false && $attempts > $this->options["log"]) {
            // log the attempt
            $this->logger->warning("{$identifier} exceeded the number of allowed requests");
        }
        if ($attempts > $this->options["ban"]) {
            // the identifier has been banned
            return false;
        }
        // valid
        return true;
    }

    /**
     * Reset the tracking of a specific identifier.
     * @param string $identifier
     */
    public function reset($identifier)
    {
        $this->storage->delete($identifier);
    }

    /**
     * Get the remaining amount of attempts for a specific identifier.
     * @param string $identifier
     * @return int
     */
    public function remaining($identifier)
    {
        return $this->options["ban"] - $this->storage->get($identifier);
    }
}
