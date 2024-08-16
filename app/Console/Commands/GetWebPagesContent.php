<?php

namespace App\Console\Commands;

use App\Services\WebsitePagesService;
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
    public function handle(WebsitePagesService $websitePagesService): void
    {
        $pageId = $this->argument('pageId');
        $websitePagesService->getPagesContent($this, $pageId);
    }
}
