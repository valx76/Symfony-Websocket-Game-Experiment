<?php

namespace App\Shared\Service;

use App\Shared\Contracts\GameStorageInterface;
use App\Game\Exception\GameStorageConnectException;
use App\Game\Exception\GameStorageDataException;
use App\Game\Exception\GameStorageNotConnectedException;
use Redis;
use RedisException;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

class RedisGameStorage implements GameStorageInterface
{
    private ?Redis $redis = null;

    public function connect(string $host, int $port): void
    {
        try {
            $this->redis = RedisAdapter::createConnection(
                sprintf('redis://%s:%s', $host, $port)
            );
        } catch (InvalidArgumentException $e) {
            throw new GameStorageConnectException($e->getMessage());
        }
    }

    public function ping(): bool
    {
        $this->checkRedisConnection();

        try {
            return $this->redis->ping();
        } catch (RedisException) {
            return false;
        }
    }

    public function set(string $key, string $value): void
    {
        $this->checkRedisConnection();

        try {
            $this->redis->set($key, $value);
        } catch (RedisException $e) {
            throw new GameStorageDataException($e->getMessage());
        }
    }

    public function get(string $key): string
    {
        $this->checkRedisConnection();

        try {
            return $this->redis->get($key);
        } catch (RedisException $e) {
            throw new GameStorageDataException($e->getMessage());
        }
    }

    /**
     * @throws GameStorageNotConnectedException
     */
    private function checkRedisConnection(): void
    {
        if ($this->redis === null) {
            // INFO - Should not happen since 'connect()' is called in a compiler pass
            throw new GameStorageNotConnectedException('You need to call "RedisGameStorage::connect()" before calling this method');
        }
    }
}