<?php

namespace App\Console\Commands;

use App\Services\WebsitePagesService;
use Illuminate\Console\Command;

class LoadWebsitePages extends Command
{
    protected $signature = 'app:load-website-pages';
    protected $description = 'Load and parse website pages';

    public function __construct(private readonly WebsitePagesService $websitePagesService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->websitePagesService->loadPages($this);
    }
}
