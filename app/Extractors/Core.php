<?php

namespace App\Extractors;

/**
 * Class Core
 *
 * @package \App\Extractors
 */
class Core extends Extractor
{
    /**
     * The path of the component.
     *
     * @return string
     */
    public static function path(): string
    {
        return 'Core';
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Core';
    }

    /**
     * The package name for composer.
     *
     * @return string
     */
    public static function package(): string
    {
        return 'laravel-foundation/core';
    }

    public function namespace(): string
    {
        return 'Illuminate\\Foundation\\';
    }

    public function extract($version)
    {
        $destination = storage_path(sprintf('versions/%s/%s', $version, $this->path()));

        if (is_dir($destination)) {
            exec(sprintf('rm -rf %s', $destination));
        }

        exec(sprintf('mkdir -p %s/src', $destination));

        $extraction = storage_path(sprintf(
            'extractions/%s/framework-%s/src/Illuminate/Foundation/*.php',
            $version,
            $version
        ));

        exec(sprintf('cp %s %s/src', $extraction, $destination));

        $this->command->info(sprintf('%s: Extracted', $this->name()));
    }

    protected function composerData()
    {
        $data = parent::composerData();

        $data['autoload']['files'] = [
            'src/helpers.php'
        ];

        return $data;
    }
}
