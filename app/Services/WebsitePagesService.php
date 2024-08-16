<?php

namespace App\Services;

use App\Models\WebPage;
use App\Models\WebPageCleared;
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

    public function __construct(private readonly Website $website, private readonly WebPage $webPage)
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

    public function getPagesContent(Command $command, ?int $pageId)
    {
        if ($pageId !== null) {
            $this->getPageContent($pageId, $command);
        } else {
            $webPagesIds = $this->webPage->pluck('id');
            foreach ($webPagesIds as $webPageId) {
                $this->getPageContent($webPageId, $command);
            }
        }
    }

    public function getPageContent(int $pageId, Command $command)
    {
        $page = $this->webPage::find($pageId);
        $pageHtml = $page->html;
        $page = new Crawler($pageHtml);

        $pageTitleText = $page->filter('title')->count() ? $page->filter('title')->text() : 'No title available';

        try {
            $this->prepareAndSaveClearedWebPageData($page, $command, null, $pageId, $pageTitleText);
        } catch (\Exception $e) {
            $command->error("Error processing page $pageId: " . $e->getMessage());
        }
    }

    private function prepareAndSaveClearedWebPageData(
        Crawler $page,
        Command $command,
        ?int    $level = 0,
        ?int    $pageId = null,
        ?string $pageTitleText = null
    ): void
    {
        $result = [];
        $nodeCounters = [];

        foreach ($page as $element) {

            if ($element->childNodes->length === 0) {
                continue;
            }

            $nodeName = $element->nodeName;
            if (!isset($nodeCounters[$nodeName])) {
                $nodeCounters[$nodeName] = 0;
            }
            $nodeCounters[$nodeName]++;
            $nodeCount = $nodeCounters[$nodeName];

            $contentLength = strlen($element->textContent);
            if ($contentLength < 50 ||
                in_array($element->nodeName, ['head', 'script', 'link', 'footer', 'header', 'nav', 'comment', 'form', 'blockquote']) ||
                str_starts_with($element->nodeName, '#')
            ) {
                continue;
            }

            $elementData = [];

            if (in_array($element->nodeName, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
                $elementData['web_page_id'] = $pageId;
                $elementData['page_title'] = $pageTitleText;
                $elementData['level'] = $level;
                $elementData['node_name'] = $element->nodeName;
                $elementData['content_length'] = $contentLength;
                $elementData['parent_node'] = $element->parentNode->nodeName;
                $elementData['content_title'] = $element->textContent;
                $elementData['node_count'] = $nodeCount;

                WebPageCleared::updateOrCreate(
                    [
                        'web_page_id' => $pageId,
                        'level' => $level,
                        'node_name' => $element->nodeName,
                        'content_length' => $contentLength,
                        'parent_node' => $element->parentNode->nodeName,
                        'node_count' => $nodeCount,
                    ],
                    $elementData
                );
                $command->info("Element $element->nodeName processed");
            }

            if (in_array($element->nodeName, ['p', 'a', 'span', 'li', 'ul', 'strong', 'ol', 'em', 'b', 'i', 'u'])) {
                $childNodeNames = [];
                foreach ($element->childNodes as $childNode) {
                    $childNodeNames = [$childNode->nodeName => $childNode->textContent];
                }
                $elementData['web_page_id'] = $pageId;
                $elementData['page_title'] = $pageTitleText;
                $elementData['level'] = $level;
                $elementData['node_name'] = $element->nodeName;
                $elementData['content_length'] = $contentLength;
                $elementData['base_url'] = $element->baseURI;
                $elementData['child_nodes'] = $childNodeNames;
                $elementData['parent_node'] = $element->parentNode->nodeName;
                $elementData['content'] = trim(preg_replace('/\s+/', ' ', str_replace(["\n"], ' ', $element->textContent)));
                $elementData['node_count'] = $nodeCount;

                WebPageCleared::updateOrCreate(
                    [
                        'web_page_id' => $pageId,
                        'level' => $level,
                        'node_name' => $element->nodeName,
                        'content_length' => $contentLength,
                        'parent_node' => $element->parentNode->nodeName,
                        'node_count' => $nodeCount,
                    ],
                    $elementData
                );
                $command->info("Element $element->nodeName processed");
            }

            $this->prepareAndSaveClearedWebPageData(
                new Crawler($element->childNodes),
                $command,
                $level + 1,
                $pageId,
                $pageTitleText
            );
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
