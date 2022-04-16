<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ParserService;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ParserController extends Controller
{
    public function index() {
        $link = 'http://tagtekstil.com.ua/';
        $client = new Client();
        $content = $client->request('GET',$link);
        return response()->json($content);
    }
}
