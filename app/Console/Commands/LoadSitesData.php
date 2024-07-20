<?php

namespace App\Console\Commands;

use App\Models\WebsiteList;
use Illuminate\Console\Command;
use Symfony\Component\Panther\Client;

class LoadSitesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-sites-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(WebsiteList $websiteList)
    {
        $websites = $websiteList->where('project_id', '=', 1)->first()->pluck('websites')->toArray();
        $websites = reset($websites);

        foreach ($websites as $website) {
            $this->info($website);

            $client = Client::createChromeClient();

            $client->request('GET', $website);
            $this->info($client->getTitle());
//            $client->clickLink('Getting started');

//            $crawler = $client->waitFor('#installing-the-framework');
//            $crawler = $client->waitForVisibility('#installing-the-framework');

//            echo $crawler->filter('#installing-the-framework')->text();

            $client->takeScreenshot('screen.png');


            dd('stop');
        }
    }
}
