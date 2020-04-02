<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    /** @var int 15 minutos */
    const TTL_QUICK = 900;

    /** @var int 1 hora */
    const TTL_DEFAULT = 3600;

    /** @var int 24 horas */
    const TTL_PERSISTENT = 86400;

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        if (!$data = Redis::get($key)) {
            return null;
        }
        return \GuzzleHttp\json_decode($data, true);
    }

    /**
     * @param string $key
     * @param array $data
     * @param int $ttl
     * @return void
     */
    public function save(string $key, array $data, int $ttl = self::TTL_DEFAULT): void
    {
        Redis::set($key, \GuzzleHttp\json_encode($data));
        Redis::expire($key, $ttl);
    }
}
