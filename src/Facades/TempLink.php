<?php

namespace RezafDev\LaravelTempLink\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string generateTempLink(string $target, string $seconds_to_live)
 * @method static void deleteExpiredLinks()
 */

class TempLink extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \RezafDev\LaravelTempLink\TempLink::class;
    }
}