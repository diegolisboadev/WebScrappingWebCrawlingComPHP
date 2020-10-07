<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\DomCrawler\Crawler;

class WebScrappingController extends Controller
{
    private $cliente;

    public function __construct() {
        $this->cliente = new Client;
    }

    public function web() {
        return view('web');
    }

    // Retornar a Newsletter do Site da SEPLAN
    public function webAJax() {
        //$cliente = new Client();

        // crawler da Newsleter da SEPLAN
        $crawler = $this->cliente->request('GET', 'https://seplan.ma.gov.br/');
        // $crawler = $this->cliente->request('GET', 'https://seplan.ma.gov.br/secao/noticias/');

        /*$scrap = $crawler->filterXPath('//div[@class="col-md-8 noticias-lista hidden-xs hidden-sm"]')->each(function (Crawler $nodeCrawler) {
            $tarja_data = $nodeCrawler->filterXPath('//div[@class="item-noticia"]//span[@class="tarja-data"]')->extract(['_text']);
            $titulo_and_link = $nodeCrawler->filter('a[rel="bookmark"]')->extract(['_text', 'href']);
            $texto = $nodeCrawler->filterXPath('//div[@class="item-noticia"]//p[@class="texto"]')->extract(['_text']);
            // $leia_mais_link = $nodeCrawler->filterXPath('//div[@class="item-noticia"]//span[@class="leia-mais"]//a')->extract(['href']);
        });*/

        $dados_array_seplan = [];
        // $imagem_seplan = $crawler->filter('div[id="noticias"] div[class="row"] div[class="col-md-3"] img')->extract(['src']);
        // $tarja_data = $crawler->filter('div[id="noticias"] div[class="row"] div[class="col-md-9"]')->extract(['_text']);
        // $titulo_and_link = $crawler->filter('div[id="noticias"] div[class="row"] div[class="col-md-9"] h2 a')->extract(['_text', 'href']);

        $tarja_data = $crawler->filterXPath('//div[@class="item-noticia"]//span[@class="tarja-data"]')->extract(['_text']);
        $titulo_and_link = $crawler->filterXPath('//div[@class="item-noticia"] //a[@rel="bookmark"]')->extract(['_text', 'href']);
        $texto = $crawler->filterXPath('//div[@class="item-noticia"]//p[@class="texto"]')->extract(['_text']);
        array_push($dados_array_seplan, ['datas' => $tarja_data, 'titulos' => $titulo_and_link, 'textos' => $texto]);

        return response()->json($dados_array_seplan);
    }

    //
    public function web2() {}
}
