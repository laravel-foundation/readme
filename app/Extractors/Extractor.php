<?php

namespace App\Extractors;

use Bit3\GitPhp\GitRepository;
use LaravelZero\Framework\Commands\Command;
use Storage;

/**
 * Class Extractor
 *
 * @package \App\Extractors
 */
abstract class Extractor
{
    /**
     * @var \LaravelZero\Framework\Commands\Command
     */
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * The path of the component.
     *
     * @return string
     */
    abstract public static function path(): string;

    /**
     * The name of the component.
     *
     * @return string
     */
    abstract public static function name(): string;

    /**
     * The package name for composer.
     *
     * @return string
     */
    abstract public static function package(): string;

    /**
     * The PHP Namespace for the package.
     *
     * @return string
     */
    public function namespace(): string
    {
        return sprintf('Illuminate\\Foundation\\%s\\', $this->name());
    }

    /**
     * Extract the code
     *
     * @param $version
     */
    public function extract($version)
    {
        $destination = storage_path(sprintf('versions/%s/%s', $version, $this->path()));

        if (is_dir($destination)) {
            exec(sprintf('rm -rf %s', $destination));
        }

        exec(sprintf('mkdir -p %s/src', $destination));

        $extraction = storage_path(sprintf(
            'extractions/%s/framework-%s/src/Illuminate/Foundation/%s/',
            $version,
            $version,
            $this->path()
        ));

        exec(sprintf('cp -r %s %s/src', $extraction, $destination));

        $this->command->info(sprintf('%s: Extracted', $this->name()));
    }

    public function addFiles($version)
    {
        $this->addComposer($version);
        $this->addReadme($version);
    }

    public function release($version)
    {
        $destination = storage_path(sprintf('release/%s/%s', $version, $this->path()));

        if (is_dir($destination)) {
            exec(sprintf('rm -rf %s', $destination));
        }

        exec(sprintf('mkdir -p %s', $destination));

        // Git
        $git = new GitRepository($destination);
        $git->cloneRepository()
            ->execute(sprintf('git@github.com:%s.git', $this->package()));
        $git->rm()->recursive()->force()->ignoreUnmatch()->execute('.');

        $this->command->info(sprintf('%s: Cloned Repo (%s)', $this->name(), $this->package()));

        // Copy Files
        exec(sprintf('cp -r %s %s',
                storage_path(sprintf('versions/%s/%s/', $version, $this->path())), $destination)
        );

        $version = 'v' . $version;
        $tags = explode("\n", $git->tag()->execute());

        if (in_array($version, $tags)) {
            $this->command->error(sprintf('%s is already tagged.', $version));
            exit(1);
        }

        // Git
        $git->add()->all()->execute();
        $git->commit()->message(sprintf('Release: %s', $version))->allowEmpty()->execute();
        $git->tag()->execute($version);
        $this->command->info(sprintf('%s: Tagged %s', $this->name(), $version));
        $git->push()->tags()->execute('origin', 'master');
        $this->command->info(sprintf('%s: Pushed to GitHub', $this->name()));
    }

    protected function composerData()
    {
        return [
            'name' => $this->package(),
            'description' => sprintf('The %s component from Laravel Foundation.', $this->name()),
            'keywords' => array_unique([
                'laravel', 'framework', 'illuminate', 'foundation', strtolower($this->name())
            ]),
            'type' => 'library',
            'license' => 'MIT',
            'authors' => [
                [
                    'name' => 'Ashley Clarke',
                    'email' => 'me@ashleyclarke.me'
                ],
                [
                    'name' => 'Taylor Otwell',
                    'email' => 'taylor@laravel.com'
                ]
            ],
            'autoload' => [
                'psr-4' => [
                    $this->namespace() => 'src/'
                ]
            ]
        ];
    }

    protected function addComposer($version)
    {
        $destination = sprintf('versions/%s/%s/composer.json', $version, $this->path());

        $data = $this->composerData();

        Storage::put($destination, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->command->info(sprintf('%s: composer.json added', $this->name()));
    }

    protected function addReadme($version)
    {
        $destination = sprintf('versions/%s/%s/README.md', $version, $this->path());

        $template = Storage::get('stubs/README.md');
        $template = str_replace("COMPONENT_NAME", $this->name(), $template);
        $template = str_replace("PACKAGE_NAME", $this->package(), $template);
        $template = str_replace("NAMESPACE", str_replace('\\', '/', $this->namespace()), $template);
        $template = str_replace("LARAVEL_VERSION", $version, $template);

        Storage::put($destination, $template);

        $this->command->info(sprintf('%s: README.md added', $this->name()));
    }
}
