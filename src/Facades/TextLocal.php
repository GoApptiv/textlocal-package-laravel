<?php

namespace GoApptiv\TextLocal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * // TODO:: Update the static methods
 * @method static GoApptiv\PinePerks\Service\PinePerksService getAccountId(string $userName)
 */
class TextLocal extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'goapptiv-textlocal';
    }
}
