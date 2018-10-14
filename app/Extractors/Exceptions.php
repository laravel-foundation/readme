<?php

namespace App\Extractors;

/**
 * Class Config
 *
 * @package \App\Extractors
 */
class Exceptions extends Extractor
{
    /**
     * The path of the component.
     *
     * @return string
     */
    public static function path(): string
    {
        return 'Exceptions';
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Exceptions';
    }

    /**
     * The package name for composer.
     *
     * @return string
     */
    public static function package(): string
    {
        return 'laravel-foundation/exceptions';
    }
}
