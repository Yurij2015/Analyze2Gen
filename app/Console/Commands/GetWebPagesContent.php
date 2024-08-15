<?php

namespace App\Console\Commands;

use App\Services\WebsiteDataService;
use Illuminate\Console\Command;

class GetWebPagesContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-web-pages-content {pageId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(WebsiteDataService $websiteDataService): void
    {
        $pageId = $this->argument('pageId');
        $websiteDataService->getPagesContent($pageId);
    }
}
