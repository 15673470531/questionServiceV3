<?php

namespace Utils;

use Config\RedisConfig;

class RedisClient {
    static $redis;

    public static function getClient(): \Redis {
        if (self::$redis){
            return self::$redis;
        }
        $redis = new \Redis();
        $redis->connect(RedisConfig::HOST,RedisConfig::PORT);
        self::$redis = $redis;
        return $redis;
    }
}
