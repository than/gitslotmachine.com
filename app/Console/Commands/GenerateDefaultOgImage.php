<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;

class GenerateDefaultOgImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'og:generate-default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the default OG image for social media sharing';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $outputPath = public_path('og-image.png');

        $this->info('Generating default OG image...');

        // Generate HTML
        $html = view('og-default')->render();

        try {
            // Generate image using browsershot
            Browsershot::html($html)
                ->windowSize(1200, 630)
                ->setScreenshotType('png')
                ->save($outputPath);

            $fileSize = filesize($outputPath);
            $fileSizeKb = round($fileSize / 1024, 2);

            $this->info("OG image generated successfully!");
            $this->info("Location: {$outputPath}");
            $this->info("File size: {$fileSizeKb} KB");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to generate OG image: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }
}
