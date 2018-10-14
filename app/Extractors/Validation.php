<?php

namespace App\Extractors;

/**
 * Class Auth
 *
 * @package \App\Extractors
 */
class Validation extends Extractor
{
    /**
     * The path of the component.
     *
     * @return string
     */
    public static function path(): string
    {
        return 'Validation';
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Validation';
    }

    /**
     * The package name for composer.
     *
     * @return string
     */
    public static function package(): string
    {
        return 'laravel-foundation/validation';
    }
}
