<?php

namespace App\Extractors;

/**
 * Class Bootstrap
 *
 * @package \App\Extractors
 */
class Bootstrap extends Extractor
{
    /**
     * The path of the component.
     *
     * @return string
     */
    public static function path(): string
    {
        return 'Bootstrap';
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Bootstrap';
    }

    /**
     * The package name for composer.
     *
     * @return string
     */
    public static function package(): string
    {
        return 'laravel-foundation/bootstrap';
    }
}
