<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Redis;
use Illuminate\Contracts\Queue\ShouldQueue;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeJob extends Job implements ShouldQueue {

    public $jobData;

    public function __construct( $jobData ) {
        $this->jobData = $jobData;
    }

    public function handle() {
        $client = new Client();

        // Fetch all URLs concurrently
        $promises = array_map(
            fn($url) => $client->getAsync($url),
            $this->jobData['urls']
        );

        $responses = Promise\settle($promises)->wait(); // Resolve all promises

        $results = [];

        foreach ($responses as $index => $response) {
            if ($response['state'] === 'fulfilled') {
                $html = $response['value']->getBody()->getContents();
                $crawler = new Crawler($html);

                $scrapedData = array_map(
                    fn($selector) => $crawler->filter($selector)->each(fn($node) => $node->text()),
                    $this->jobData['selectors']
                );

                $results[$this->jobData['urls'][$index]] = $scrapedData;
            } else {
                $results[$this->jobData['urls'][$index]] = [
                    'error' => $response['reason']->getMessage(),
                ];
            }
        }

        $this->jobData['status'] = 'completed';
        $this->jobData['scraped_data'] = $results;

        Redis::set("job:{$this->jobData['id']}", json_encode($this->jobData));
    }

    public function failed(\Exception $exception)
    {
        \Log::error("ScrapeJob failed for job ID: {$this->jobData['id']}", [
            'error' => $exception->getMessage(),
        ]);

        $this->jobData['status'] = 'failed';
        Redis::set("job:{$this->jobData['id']}", json_encode($this->jobData));
    }

}
