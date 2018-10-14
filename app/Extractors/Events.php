<?php

namespace App\Extractors;

/**
 * Class Cache
 *
 * @package \App\Extractors
 */
class Events extends Extractor
{
    /**
     * The path of the component.
     *
     * @return string
     */
    public static function path(): string
    {
        return 'Events';
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Events';
    }

    /**
     * The package name for composer.
     *
     * @return string
     */
    public static function package(): string
    {
        return 'laravel-foundation/events';
    }
}
