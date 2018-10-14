<?php

namespace App\Extractors;

/**
 * Class Contracts
 *
 * @package \App\Extractors
 */
class Providers extends Extractor
{
    /**
     * The path of the component.
     *
     * @return string
     */
    public static function path(): string
    {
        return 'Providers';
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Providers';
    }

    /**
     * The package name for composer.
     *
     * @return string
     */
    public static function package(): string
    {
        return 'laravel-foundation/providers';
    }
}
