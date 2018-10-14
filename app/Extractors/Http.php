<?php

namespace App\Extractors;

/**
 * Class Container
 *
 * @package \App\Extractors
 */
class Http extends Extractor
{
    /**
     * The path of the component.
     *
     * @return string
     */
    public static function path(): string
    {
        return 'Http';
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Http';
    }

    /**
     * The package name for composer.
     *
     * @return string
     */
    public static function package(): string
    {
        return 'laravel-foundation/http';
    }
}
