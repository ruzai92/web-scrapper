<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScraperController extends Controller
{
    public function index()
    {
        return view('scapper.index');
    }

    public function scrape(Request $request)
    {
        

        try {
            // URL to scrape
            $url = $request->url;

            // Create a new Guzzle client
            $client = new Client();

            // Send a GET request to the URL
            $response = $client->request('GET', $url);

            // Get the HTML content
            $html = $response->getBody()->getContents();

            // Create a new Crawler instance
            $crawler = new Crawler($html);

            // Example: Extract all the titles from the page
            $titles = $crawler->filter('h1')->each(function (Crawler $node) {
                return $node->text();
            });

            // Example: Extract all the links from the page
            $links = $crawler->filter('a')->each(function (Crawler $node) {
                return $node->attr('href');
            });

            $contents = $crawler->filter('p')->each(function (Crawler $node) {
                return $node->text();
            });


            // Return the scraped data as a response
            return response()->json([
                'titles' => $titles,
                'links' => $links,
                'contents' => $contents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
