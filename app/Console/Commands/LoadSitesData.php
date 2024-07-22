<?php

namespace App\Console\Commands;

use App\Services\WebsiteDataService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class LoadSitesData extends Command
{
    protected $signature = 'app:load-sites-data';
    protected $description = 'Load and parse website data';

    public function __construct(private readonly WebsiteDataService $websiteDataService)
    {
        parent::__construct();
    }


    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $this->websiteDataService->loadData($this, 1);
    }
}
