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

    public function web2() {
        return view('web2');
    }

    public function web3() {
        return view('web3');
    }

    public function webUni() {
        return view('webUni');
    }

    // Unificação do Sites
    public function webUniAjax() {
        $seplanNotice = $this->seplanArrayNotice();
        // $tjmaNotice = $this->tjmaArrayNotice();
        $tcemaNotice = $this->tcemaArrayNotice();
        $minJusticaNotice = $this->minJusArrayNotice();
        $tcuNotice = $this->tcuArrayNotice();

        //
        $arrayUnion = [
            'uniao' => ['seplan' => $seplanNotice, 'minJus' => $minJusticaNotice,
            'tcema' => $tcemaNotice, 'tcu' => $tcuNotice]
        ];

        return response()->json($arrayUnion);
    }

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
                $i => ['data' => $td, 'titulo' => $titulo_and_link[$i], 'texto' => $texto[$i]]
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
                $i => ['data' => trim((new \DateTime($trd))->format('y/m/d')), 'link' => $tr_link[$i], 'texto' => trim($tr_texto[$i])]
            ];
        }

        return response()->json($dados_array_tce);
    }

    private function tcuArrayNotice() {
        $crawler = $this->cliente->request('GET', 'https://portal.tcu.gov.br/imprensa/');

        // Click no Button para Habilitar // 10 Noticias

        $dados_tcu = [];
        // $ul = $crawler->filterXPath('//div//ul[@class="noticias__lista-rapida"]//li');
        $li_image = $crawler->filterXPath('//ul[@class="noticias__lista-rapida"]
        //li[@class="noticia__lista_item"]//div[@class="noticia__imagem"]//img')->extract(['src']);
        $link_notice = $crawler->filterXPath('//ul[@class="noticias__lista-rapida"]
        //li[@class="noticia__lista_item"]//div[@class="noticia__imagem"]//a')->extract(['href']);
        $data_notice = $crawler->filterXPath('//ul[@class="noticias__lista-rapida"]
        //li[@class="noticia__lista_item"]//div[@class="noticia__texto"]//div[@class="noticia__lista_header"]
        //div[@class="noticia__lista_data_hora"]')->extract(['_text']);
        $titulo = $crawler->filterXPath('//ul[@class="noticias__lista-rapida"]
        //li[@class="noticia__lista_item"]//div[@class="noticia__texto"]
        //h3//a[@class="noticia__lista_manchete"]')->extract(['_text']);
        $descricao = $crawler->filterXPath('//ul[@class="noticias__lista-rapida"]
        //li[@class="noticia__lista_item"]//div[@class="noticia__texto"]//p')->extract(['_text']);

        foreach($data_notice as $i => $dn) {
            $dados_tcu += [
                $i => ['data' => $dn, 'link' => $link_notice[$i], 'titulo' => $titulo[$i]],
            ];
        }

        return response()->json($dados_tcu);
    }

    private function minJusArrayNotice() {
        $crawler = $this->cliente->request('GET', 'https://www.gov.br/mj/pt-br/assuntos/noticias');

        $dados_min_justica = [];
        // $li = $crawler->filterXPath('//ul[@class="noticias listagem-noticias-com-foto"]//li');
        $titulo_link = $crawler->filterXPath('//div[@class="conteudo"]//h2[@class="titulo"]//a')->extract(['_text', 'href']);
        $data = $crawler->filterXPath('//ul[@class="noticias listagem-noticias-com-foto"]//li//div[@class="conteudo"]//span[@class="descricao"]//span[@class="data"]')->extract(['_text']);
        $descricao = $crawler->filter('ul[class="noticias listagem-noticias-com-foto"] li div[class="conteudo"] span[class="descricao"]')->extract(['_text']);
        $imagem = $crawler->filter('ul[class="noticias listagem-noticias-com-foto"] li div[class="imagem"] img')->extract(['src']);

        foreach($data as $i => $dt) {
            $dados_min_justica += [
                $i => ['titulo_link' => $titulo_link[$i], 'data' => $dt,
                'descricao' => $descricao[$i]]
            ];
        }

        return response()->json($dados_min_justica);

    }

    private function seplanArrayNotice() {
        $crawler = $this->cliente->request('GET', 'https://seplan.ma.gov.br/');

        $dados_array_seplan = [];
        $tarja_data = $crawler->filterXPath('//div[@class="item-noticia"]//span[@class="tarja-data"]')->extract(['_text']);
        $titulo_and_link = $crawler->filterXPath('//div[@class="item-noticia"] //a[@rel="bookmark"]')->extract(['_text', 'href']);
        $texto = $crawler->filterXPath('//div[@class="item-noticia"]//p[@class="texto"]')->extract(['_text']);

        // Adicionando o 3 Arrays Juntos em 1 Só (Pegando como Item inicial a tarja data)
        foreach($tarja_data as $i => $td) {
            $dados_array_seplan += [
                $i => ['data' => $td, 'titulo' => $titulo_and_link[$i], 'texto' => $texto[$i]]
            ];
        }

        return $dados_array_seplan;
    }

    private function tjmaArrayNotice() {

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

        return $dados_array_tjma;
    }

    private function tcemaArrayNotice() {
        $crawler = $this->cliente->request("GET", "http://site.tce.ma.gov.br/index.php/noticias-internet");

        $dados_array_tce = [];

        $tr_link = $crawler->filterXPath('//div[@class="category-list"] //div[@class="cat-items"] //table[@class="category"] //tr //td[@class="list-title"] //a')->extract(['href']);
        $tr_texto = $crawler->filterXPath('//div[@class="category-list"] //div[@class="cat-items"] //table[@class="category"] //tr //td[@class="list-title"] //a')->extract(['_text']);
        $tr_date = $crawler->filterXPath('//div[@class="category-list"] //div[@class="cat-items"] //table[@class="category"] //tr //td[@class="list-date"]')->extract(['_text']);

        foreach($tr_date as $i => $trd) {
            $dados_array_tce += [
                $i => ['data' => trim((new \DateTime($trd))->format('y/m/d')), 'link' => $tr_link[$i], 'texto' => trim($tr_texto[$i])]
            ];
        }

        return $dados_array_tce;
    }
}
