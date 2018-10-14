<?php

namespace App\Extractors;

use Storage;

/**
 * Class Foundation
 *
 * @package \App\Extractors
 */
class Foundation extends Extractor
{
    protected $components = [
        Auth::class, Bootstrap::class, Bus::class,
        Console::class, Events::class, Exceptions::class, Http::class,
        Providers::class, Support::class, Testing::class, Validation::class,
    ];

    /**
     * The path of the component.
     *
     * @return string
     */
    public static function path(): string
    {
        return 'Foundation';
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Foundation';
    }

    /**
     * The package name for composer.
     *
     * @return string
     */
    public static function package(): string
    {
        return 'laravel-foundation/foundation';
    }

    public function extract($version)
    {
        $destination = storage_path(sprintf('versions/%s/%s', $version, $this->path()));

        if (is_dir($destination)) {
            exec(sprintf('rm -rf %s', $destination));
        }

        exec(sprintf('mkdir -p %s', $destination));

        $this->command->info(sprintf('%s: Extracted', $this->name()));
    }

    protected function composerData()
    {
        $data = parent::composerData();

        unset($data['autoload']);

        $data['require'] = [
            'laravel-foundation/auth' => 'self.version',
            'laravel-foundation/bootstrap' => 'self.version',
            'laravel-foundation/bus' => 'self.version',
            'laravel-foundation/console' => 'self.version',
            'laravel-foundation/core' => 'self.version',
            'laravel-foundation/events' => 'self.version',
            'laravel-foundation/exceptions' => 'self.version',
            'laravel-foundation/http' => 'self.version',
            'laravel-foundation/providers' => 'self.version',
            'laravel-foundation/support' => 'self.version',
            'laravel-foundation/testing' => 'self.version',
            'laravel-foundation/validation' => 'self.version',
        ];

        return $data;
    }

    protected function addReadme($version)
    {
        $destination = sprintf('versions/%s/%s/README.md', $version, $this->path());

        $template = Storage::get('stubs/Foundation.md');
        $template = str_replace("LARAVEL_VERSION", $version, $template);

        Storage::put($destination, $template);

        /** @var \App\Extractors\Extractor $component */
        foreach ($this->components as $component) {
            Storage::append($destination, sprintf('* [`%s`](https://github.com/%s) - The %s Component.', $component::package(), $component::package(), $component::name()));
        }

        $this->command->info(sprintf('%s: README.md added', $this->name()));
    }
}
