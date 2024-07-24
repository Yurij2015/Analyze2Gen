<?php

namespace App\Services;

use App\Models\WebPage;
use App\Models\Website;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Random\RandomException;
use Symfony\Component\DomCrawler\Crawler;

class WebsitePagesService
{
    protected Client $client;
    protected int $minTimeout = 1;
    protected int $maxTimeout = 3;

    public function __construct(private readonly Website $website)
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     * @throws RandomException
     */
    public function loadPages($command): void
    {
//        $websites = $this->website->whereNotIn('id', [1,2,3,4,5,6,7])->get();
        $websites = $this->website->whereProjectId(2)->where('id', '>', 214)->get();
        foreach ($websites as $website) {
            $pages = $this->website::whereId($website->id)->first()?->siteMap;
            $pages = array_map(static function ($page) {
                return $page['url']['loc'];
            }, $pages);

            foreach ($pages as $page) {
                $command->info($page);
                $this->processWebsitePage($page, $website->id, $command);
                $randomTimeout = random_int($this->minTimeout, $this->maxTimeout);
                sleep($randomTimeout);
            }
            $randomTimeout = random_int($this->minTimeout, $this->maxTimeout);
            sleep($randomTimeout);
        }
    }

    /**
     * @throws GuzzleException
     */
    private function processWebsitePage(string $page, int $webSiteId, Command $command): void
    {
        try {
            $response = $this->client->request('GET', $page);
            $crawler = new Crawler($response->getBody()->getContents());
        } catch (\Exception $e) {
            $command->error("Error fetching page $page: " . $e->getMessage());
            return;
        }
        $html = $crawler->html();
        $title = $crawler->filter('title')->count() ? $crawler->filter('title')->text() : 'No title available';
        $description = $crawler->filter('meta[name="description"]')->count() ? $crawler->filter('meta[name="description"]')->attr('content') : 'No description';

        WebPage::updateOrCreate(
            ['pageUrl' => $page],
            [
                'website_id' => $webSiteId,
                'title' => $title,
                'description' => $description,
                'html' => $html,
            ]
        );
        $command->info("Page $page processed");
    }
}
