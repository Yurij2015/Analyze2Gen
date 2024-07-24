<?php

namespace App\Services;

use App\Models\Website;
use App\Models\WebsiteList;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class WebsiteDataService
{
    protected Client $client;

    public function __construct(private readonly WebsiteList $websiteList)
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function loadData(Command $command, $projectId): void
    {
        $websites = $this->websiteList::whereProjectId($projectId)->pluck('websites')->toArray();
        $websites = reset($websites);

        foreach ($websites as $website) {
            $this->processWebsite($website, $projectId);
            $command->info($website);
//            $client = Client::createChromeClient();
//            $crawler = $client->request('GET', $website);
//            $title = $crawler->filter('title')->text();
//            $this->info("Title: $title");
//            $client = Client::createChromeClient();
//            $client->request('GET', $website);
//            $this->info($client->getTitle());
//            $client->clickLink('Getting started');
//            $crawler = $client->waitFor('#installing-the-framework');
//            $crawler = $client->waitForVisibility('#installing-the-framework');
//            echo $crawler->filter('#installing-the-framework')->text();
//            $client->takeScreenshot('screen.png');

        }
    }

    /**
     * @throws GuzzleException
     */
    private function processWebsite(string $website, int $projectId): void
    {
        $parsedUrl = parse_url($website);
        $baseDomain = rtrim($parsedUrl['scheme'] . '://' . $parsedUrl['host'], '/');
        $siteMap = $this->loadsiteMap($baseDomain);
        try {
            $metaData = $this->loadMetaData($baseDomain);
            $siteLinks = $this->loadPageLinks($website, $baseDomain, $parsedUrl);
        } catch (\Exception $e) {
            echo "Error fetching or parsing website data: " . $e->getMessage();
            return;
        }

        $websiteData = [
            'project_id' => $projectId,
            'baseDomain' => $baseDomain,
            'title' => $metaData['title'] ?? 'No title available',
            'description' => $metaData['description'] ?? 'No description available',
            'keywords' => $metaData['keywords'] ?? 'No keywords available',
            'robots' => $metaData['robots'] ?? 'No robots meta tag available',
            'canonical' => $metaData['canonical'] ?? 'No canonical URL available',
            'general' => $metaData['generator'] ?? 'No CMS generator meta tag available',
            'googleTag' => false,
            'facebookPixel' => false,
            'siteLinks' => $siteLinks,
            'siteMap' => $siteMap,
        ];

        Website::updateOrCreate(
            ['baseDomain' => $baseDomain],
            $websiteData
        );
    }

    /**
     * @throws GuzzleException
     */
    private function loadSiteMap($baseDomain): array
    {
        $sitemapUrl = rtrim($baseDomain, '/') . '/sitemap.xml';
        try {
            $response = $this->client->request('GET', $sitemapUrl);
            $content = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $response->getBody()->getContents());
            $siteMapCrawler = new Crawler($content);
            return $siteMapCrawler->filter('urlset > url')->each(function (Crawler $node) {
                return [
                    'url' => [
                        'loc' => $node->filter('loc')->text(),
                        'lastmod' => $node->filter('lastmod')->count() ? $node->filter('lastmod')->text() : null,
                        'priority' => $node->filter('priority')->count() ? $node->filter('priority')->text() : null,
                        'changefreq' => $node->filter('changefreq')->count() ? $node->filter('changefreq')->text() : null,
                    ]
                ];
            });
        } catch (\Exception $e) {
            echo "Error fetching or parsing sitemap: " . $e->getMessage();
            return [];
        }
    }

    private function loadMetaData(string $website): array
    {
        $response = $this->client->request('GET', $website);
        $crawler = new Crawler((string)$response->getBody());

        $metaTags = ['description', 'keywords', 'robots'];
        $metaData = [];

        foreach ($metaTags as $tag) {
            $metaData[$tag] = $crawler->filter("meta[name=\"$tag\"]")->count() ? $crawler->filter("meta[name=\"$tag\"]")->first()->attr('content') : "No $tag available";
        }

        $metaData['title'] = $crawler->filter('title')->text();
        $metaData['canonical'] = $crawler->filter('link[rel="canonical"]')->count() ? $crawler->filter('link[rel="canonical"]')->first()->attr('href') : 'No canonical URL available';
        $metaData['generator'] = $crawler->filter('meta[name="generator"]')->count() ? $crawler->filter('meta[name="generator"]')->first()->attr('content') : 'No CMS generator meta tag available';

        return $metaData;
    }

    /**
     * @throws GuzzleException
     */
    private function loadPageLinks(string $webPage, string $baseDomain, array $parsedUrl): array
    {
        $response = $this->client->request('GET', $webPage);
        $htmlContent = $response->getBody();
        $crawler = new Crawler($htmlContent);
        $links = $crawler->filter('a')->each(function (Crawler $node) {
            return $node->attr('href');
        });

        $internalLinks = array_filter($links, static function ($link) use ($baseDomain, $parsedUrl) {
            if (str_starts_with($link, '#') || str_starts_with($link, 'tel:') || str_starts_with($link, 'mailto:')) {
                return false;
            }

            $cleanedLink = strtok($link, '#?');

            if (str_starts_with($cleanedLink, '/')) {
                return true;
            }

            $linkHost = parse_url($cleanedLink, PHP_URL_HOST);
            if ($linkHost === null || $linkHost === $parsedUrl['host']) {
                return $cleanedLink !== $baseDomain && $cleanedLink !== $baseDomain . '/';
            }

            return false;
        });

        $internalLinks = array_unique(array_map(static function ($link) {
            return $link;
        }, $internalLinks));

        $siteLinks = [];

        foreach ($internalLinks as $link) {
            if (str_starts_with($link, '/')) {
                $link = rtrim($parsedUrl['scheme'] . '://' . $parsedUrl['host'], '/') . '/' . ltrim($link, '/');
            }
            $siteLinks[] = $link;
        }
        return $siteLinks;
    }
}
