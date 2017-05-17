<?php

namespace Algorithm;

/**
 * Class Profiler
 * @package Algorithm
 */
class Profiler
{
    /**
     * @var array
     */
    public static $time = [];

    /**
     * @param string $key
     */
    public static function start(string $key)
    {
        self::$time[$key]['start'] = microtime(true);

        if (!isset(self::$time[$key]['total'])) {
            self::$time[$key]['total'] = 0;
        }
    }

    /**
     * @param string $key
     */
    public static function end(string $key)
    {
        if (!isset(self::$time[$key]['start'])) {
            return;
        }


        $diff = microtime(true) - self::$time[$key]['start'];

        self::$time[$key]['total'] += $diff;
        unset(self::$time[$key]['start']);
    }

    /**
     * @param string $key
     * @return string
     */
    public static function getStat(string $key): string
    {
        if (!isset(self::$time[$key]['total'])) {
            return '';
        }

        return self::$time[$key]['total'] . ' seconds';
    }

    /**
     *
     */
    public static function reset()
    {
        self::$time = [];
    }
}