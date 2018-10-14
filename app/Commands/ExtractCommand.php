<?php

namespace App\Commands;

use App\Extractors\Auth;
use App\Extractors\Bootstrap;
use App\Extractors\Bus;
use App\Extractors\Console;
use App\Extractors\Core;
use App\Extractors\Events;
use App\Extractors\Exceptions;
use App\Extractors\Foundation;
use App\Extractors\Http;
use App\Extractors\Providers;
use App\Extractors\Support;
use App\Extractors\Testing;
use App\Extractors\Validation;
use LaravelZero\Framework\Commands\Command;
use Storage;
use ZipArchive;

class ExtractCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'extract {version : The version of laravel to use}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Extract Laravel Foundation';

    protected $extractors = [
        Auth::class, Bootstrap::class, Bus::class,
        Console::class, Events::class, Exceptions::class, Http::class,
        Providers::class, Support::class, Testing::class, Validation::class,

        Core::class,
        Foundation::class,
    ];
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $version = $this->argument('version');

        $this->downloadZip($version);
        $this->runExtractors($version);
    }

    protected function downloadZip($version)
    {
        Storage::put(
            sprintf('archives/%s.zip', $version),
            file_get_contents(sprintf('https://github.com/laravel/framework/archive/v%s.zip', $version))
        );

        $this->info('Downloaded: ' . $version);

        $this->extractZip($version);
    }

    protected function extractZip($version)
    {
        $destination = storage_path(sprintf('extractions/%s', $version));

        if (is_dir($destination)) {
            exec(sprintf('rm -rf %s', $destination));
        }

        $zip = new ZipArchive;
        if ($zip->open(storage_path(sprintf('archives/%s.zip', $version))) === true) {
            $zip->extractTo($destination);
            $zip->close();
            $this->info('Extracted Zip');
        } else {
            $this->error('Failed Extracting Zip');
        }
    }

    protected function runExtractors($version)
    {
        $this->line('');
        $this->line('');

        foreach ($this->extractors as $class) {
            /** @var \App\Extractors\Extractor $extractor */
            $extractor = new $class($this);

            $extractor->extract($version);
            $extractor->addFiles($version);
            $extractor->release($version);

            $this->line('');
            $this->line('');
        }
    }
}
