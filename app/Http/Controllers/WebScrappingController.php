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

    public function web2()

    // Retornar a Newsletter do Site da SEPLAN
    public function webAJax() {

        $crawler = $this->cliente->request('GET', 'https://seplan.ma.gov.br/');

        $dados_array_seplan = [];
        $tarja_data = $crawler->filterXPath('//div[@class="item-noticia"]//span[@class="tarja-data"]')->extract(['_text']);
        $titulo_and_link = $crawler->filterXPath('//div[@class="item-noticia"] //a[@rel="bookmark"]')->extract(['_text', 'href']);
        $texto = $crawler->filterXPath('//div[@class="item-noticia"]//p[@class="texto"]')->extract(['_text']);

        // Adicionando o 3 Arrays Juntos em 1 Só (Pegando como Item inicial a tarja data)
        foreach($tarja_data as $i => $td) {
            $dados_array_seplan += [
                $i => [
                    ['data' => $td, 'titulo' => $titulo_and_link[$i], 'texto' => $texto[$i]]
                ]
            ];
        }

        return response()->json($dados_array_seplan);
    }

    // Retornar a Newsletter do Site da TJ-MA
    public function webAjax2() {

        $crawler = $this->cliente->request("GET", "https://www.tjma.jus.br/midia/portal/noticias");

        $dados_array_tjma = [];
        $li_link = $crawler->filterXPath('//ul[@class="general-list search-result"] //li //a')->extract(['href']);
        $li_image = $crawler->filterXPath('//ul[@class="general-list search-result"] //li //img')->extract(['src']);
        $li_data = $crawler->filterXPath('//ul[@class="general-list search-result"] //li //span[@class="field date"]')->extract(['_text']);
        $li_text = $crawler->filterXPath('//ul[@class="general-list search-result"] //li //div[@class="col-9 col-md-9"] //span[@class="field featured"]')->extract(['_text']);

        foreach($li_link as $i => $lk) {
           $dados_array_tjma += [
               $i => ['link' => $lk, 'data' =>$li_data[$i], 'image' => $li_image[$i], 'texto' => $li_text[$i]]
           ];
        }

        return response()->json($dados_array_tjma);
    }

    // Retornar a Newsletter do Site da TCE-MA
    public function webAjax3() {
        $crawler = $this->cliente->request("GET", "http://site.tce.ma.gov.br/index.php/noticias-internet");

        $dados_array_tce = [];

        $tr_link = $crawler->filterXPath('//div[@class="category-list"] //div[@class="cat-items"] //table[@class="category"] //tr //td[@class="list-title"] //a')->extract(['href']);
        $tr_texto = $crawler->filterXPath('//div[@class="category-list"] //div[@class="cat-items"] //table[@class="category"] //tr //td[@class="list-title"] //a')->extract(['_text']);
        $tr_date = $crawler->filterXPath('//div[@class="category-list"] //div[@class="cat-items"] //table[@class="category"] //tr //td[@class="list-date"]')->extract(['_text']);

        foreach($tr_date as $i => $trd) {
            $dados_array_tce += [
                $i => ['data' => trim($trd), 'link' => $tr_link[$i], 'texto' => trim($tr_texto[$i])]
            ];
        }

        return response()->json($dados_array_tce);
    }
}
