<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

final class ParserService
{
    public static function getContent($parser, $link)
    {
        // Get html remote text.
        $html = file_get_contents($link);

        // Create new instance for parser.
        $crawler = new Crawler(null, $link);
        $crawler->addHtmlContent($html, 'UTF-8');

        // Get title text.
        $title = $crawler->filter($parser->settings->title)->text();

        // If exist settings for teaser.
        if (!empty(trim($parser->settings->teaser))) {
            $teaser = $crawler->filter($parser->settings->teaser)->text();
        }

        // Get images from page.
        $images = $crawler->filter($parser->settings->image)->each(function (Crawler $node, $i) {
            return $node->image()->getUri();
        });

        // Get body text.
        $bodies = $crawler->filter($parser->settings->body)->each(function (Crawler $node, $i) {
            return $node->html();
        });

        $content = [
            'link' => $link,
            'title' => $title,
            'images' => $images,
            'teaser' => strip_tags($teaser),
            'body' => $bodies
        ];

        return response()->json($content);
    }
}