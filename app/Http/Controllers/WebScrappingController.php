<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\DomCrawler\Crawler;

class WebScrappingController extends Controller
{
    // Retornar a Newsletter do Site da SEPLAN
    public function web() {
        $cliente = new Client();

        // crawler da Newsleter da SEPLAN
        $crawler = $cliente->request('GET', 'https://seplan.ma.gov.br/');

        $crawler->filterXPath('//div[@class="col-md-8 noticias-lista hidden-xs hidden-sm"]')->each(function (Crawler $nodeCrawler, $i) {
            $tarja_data = $nodeCrawler->filterXPath('//div[@class="item-noticia"]//span[@class="tarja-data"]')->extract(['_text']);
            $titulo_and_link = $nodeCrawler->filter('a[rel="bookmark"]')->extract(['_text', 'href']);
            $texto = $nodeCrawler->filterXPath('//div[@class="item-noticia"]//p[@class="texto"]')->extract(['_text']);
            // $leia_mais_link = $nodeCrawler->filterXPath('//div[@class="item-noticia"]//span[@class="leia-mais"]//a')->extract(['href']);
        });

        return view('web')
            ->with(['scrap' => 'teste']);
    }

    //
    public function web2() {}
}
