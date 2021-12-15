<?php

namespace GoApptiv\TextLocal\Services;

class Constants
{
    // Status
    public static $PENDING = 'pending';
    public static $DISPATCHED = 'dispatched';
    public static $SUCCESS = 'success';
    public static $FAILED = 'failed';

    public static $statuses = [];


    /**
     * Initialize all the Constants
     */
    public static function init()
    {
        self::$statuses = [
            self::$PENDING,
            self::$DISPATCHED,
            self::$SUCCESS,
            self::$FAILED,
        ];
    }
}

Constants::init();
