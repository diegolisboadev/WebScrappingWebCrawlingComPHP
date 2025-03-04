<?php

namespace App\Http\Controllers;

use Exception;
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
        $bbNotice = $this->bancoDoBrasilNotice();
        $pcNotice = $this->policiaCivilNotice();
        $cbmNotice = $this->cbmMaNotice();
        $pmNotice = $this->pmMaNotice();
        $sspNotice = $this->sspMaNotice();
        $stcNotice = $this->stcMaNotice();
        $cguNotice = $this->cguNotice();
        $periMaNotice = $this->periciaOficialMa();
        $twitterFlavioDino = $this->twitterFlavioDino();

        /*$arrayUnion = [
            'uniao' =>[
                'cgu' => $twitterFlavioDino]
        ];*/

        $arrayUnion = [
            'uniao' =>[
                'seplan' => $seplanNotice,
                'minJus' => $minJusticaNotice,
                'tcema' => $tcemaNotice,
                'tcu' => $tcuNotice,
                'bb' => $bbNotice,
                'pc' => $pcNotice,
                'cbm' => $cbmNotice,
                'pm' => $pmNotice,
                'ssp' => $sspNotice,
                'stc' => $stcNotice,
                'cgu' => $cguNotice,
                'periMa' => $periMaNotice]
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

    // TODO fazer
    private function twitterFlavioDino() {
        try {
            $crawler = $this->cliente->request('GET', 'https://twitter.com/FlavioDino');

            $dados_tw_dino = [];
            $time_twitter = $crawler->filterXPath('//div[@class="css-1dbjc4n"]//');

            return response()->json($time_twitter);
        } catch(\Exception $e) {

        }
    }

    //TODO fazer
    private function twitterBolsonaro() {

    }

    // Retornar periciaMa
    private function periciaOficialMa() {
        try {
            $crawler = $this->cliente->request('GET', 'https://www.ssp.ma.gov.br/category/pericia-oficial/');


            $dados_peri_ma = [];
            $link_title = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
                //article //div[@class="featured clearfix"]//a[@class="img-holder"]')->extract(['href', 'title', 'style']);
            $data = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
            //article //div[@class="post-meta"] //span')->extract(['_text']);
            $texto = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
            //article //div[@class="post-summary"]')->extract(['_text']);


            foreach($link_title as $i => $lt) {
                $dados_peri_ma += [
                    $i => ['data' => $data[$i], 'link_texto' => $lt, /*'texto' => $texto[$i]*/]
                ];
            }

            return response()->json($dados_peri_ma);

        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

    // Retornar CGU
    private function cguNotice() {
        try {
            $crawler = $this->cliente->request('GET', 'https://www.gov.br/cgu/pt-br/assuntos/noticias/ultimas-noticias');

            $dados_cgu = [];
            $data = $crawler->filter('#content-core article
            span[class="documentByLine"] span:nth-of-type(3)')->extract(['_text']);
            $titulo_link = $crawler->filter('#content-core div[class="tileContent"] h2 a')
                ->extract(['_text', 'href']);
            $imagem = $crawler->filter('#content-core div[class="tileImage"] a img')->extract(['src']);

            foreach($titulo_link as $i => $tl) {
                $dados_cgu += [
                    $i => ['data' => $data[$i], 'link_texto' => $tl, /*'texto' => $texto[$i]*/]
                ];
            }

            return response()->json($dados_cgu);

        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

    // Retornar Stc
    private function stcMaNotice() {
        try {
            $crawler = $this->cliente->request('GET', 'http://stc.ma.gov.br/secao/noticias/');

            $dados_stc = [];

            $imagem = $crawler->filter('div[class="col-md-12 noticia-principal"] div
            img')->extract(['src']);

            $data = $crawler->filter('div[class="col-md-12 noticia-principal"]
            div span')->extract(['_text']);

            $titulo_link = $crawler->filter('div[class="col-md-12 noticia-principal"] div span a')
                ->extract(['_text', 'href']);

            $arr_data = [];
            foreach($data as $dt) {
                $arr_data = array_filter($arr_data);
                array_push($arr_data, $this->explode_by_array(['pm', 'am'], trim($dt)));
            }

            foreach($titulo_link as $i => $tl) {
                $dados_stc += [
                    $i => ['data' => $arr_data[$i], 'link_texto' => $tl, /*'texto' => $texto[$i]*/]
                ];
            }

            return response()->json($dados_stc);
        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

    // Retornar SSP MA
    private function sspMaNotice() {
        try {
            $crawler = $this->cliente->request('GET', 'https://www.ssp.ma.gov.br/noticias/');

            $dados_ssp = [];
            $link_title = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
                //article //div[@class="featured clearfix"]//a[@class="img-holder"]')->extract(['href', 'title', 'style']);
            $data = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
            //article //div[@class="post-meta"] //span')->extract(['_text']);
            $texto = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
            //article //div[@class="post-summary"]')->extract(['_text']);


            foreach($link_title as $i => $lt) {
                $dados_ssp += [
                    $i => ['data' => $data[$i], 'link_texto' => $lt, /*'texto' => $texto[$i]*/]
                ];
            }

            return response()->json($dados_ssp);

        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'mgs' => $e->getMessage()], 501);
        }
    }

    // Retornar pmMa
    private function pmMaNotice() {

        try {

            $crawler = $this->cliente->request('GET', 'https://pm.ssp.ma.gov.br/noticias-2/');

            $dados_pm = [];
            $link_title = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-5 clearfix "]
                //article //div[@class="featured clearfix"]//a[@class="img-holder"]')->extract(['href', 'title', 'style']);
            $data = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-5 clearfix "]
            //article //div[@class="post-meta"] //span')->extract(['_text']);
            $texto = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-5 clearfix "]
            //article //div[@class="post-summary"]')->extract(['_text']);


            foreach($link_title as $i => $lt) {
                $dados_pm += [
                    $i => ['data' => $data[$i], 'link_texto' => $lt, /*'texto' => $texto[$i]*/]
                ];
            }

            return response()->json($dados_pm);

        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }

    }

    // Retornar cbmMa
    private function cbmMaNotice() {
        try {
            $crawler = $this->cliente->request('GET', 'https://cbm.ssp.ma.gov.br/index.php/category/noticias/');

            $dados_cbm = [];
            $link_title = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
                //article //div[@class="featured clearfix"]//a[@class="img-holder"]')->extract(['href', 'title', 'style']);
            $data = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
            //article //div[@class="post-meta"] //span')->extract(['_text']);
            $texto = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
            //article //div[@class="post-summary"]')->extract(['_text']);


            foreach($link_title as $i => $lt) {
                $dados_cbm += [
                    $i => ['data' => $data[$i], 'link_texto' => $link_title, /*'texto' => $texto[$i]*/]
                ];
            }

            return response()->json($dados_cbm);
        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

    // Retornar PoliciaCivil
    private function policiaCivilNotice() {
        try {
            $crawler = $this->cliente->request('GET', 'https://www.policiacivil.ma.gov.br/category/noticias/');

            $dados_pc = [];
            $link_title = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
                //article //div[@class="featured clearfix"]//a[@class="img-holder"]')->extract(['href', 'title', 'style']);
            $data = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
            //article //div[@class="post-meta"] //span')->extract(['_text']);
            $texto = $crawler->filterXPath('//div[@class="listing listing-blog listing-blog-1 clearfix  columns-1"]
            //article //div[@class="post-summary"]')->extract(['_text']);

            foreach($link_title as $i => $lt) {
                $dados_pc += [
                    $i => ['data' => $data[$i], 'link_texto' => $link_title, /*'texto' => $texto[$i]*/]
                ];
            }

            return response()->json($dados_pc);
        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

    private function bancoDoBrasilNotice() {
        try {
            $crawler = $this->cliente->request('GET', 'https://www.bb.com.br/portalbb/page120,3366,3367,1,0,1,0.bb?codigoNoticia=0&pk_vid=d352c6d6a75a9d691604755659a4c6ae');

            $data_array = [];
            $dados_bb = [];
            $div_data_dia = $crawler->filterXPath('//div[@class="grade_54"] //span[@class="titNot1"]')->extract(['_text']);
            $div_data_mes = $crawler->filterXPath('//div[@class="grade_54"] //span[@class="titNot2"]')->extract(['_text']);
            $link_texto = $crawler->filter('div[class="grade_54"] a[class="linkChamada_5"]')->extract(['_text', 'href']);

            // Unificar os dados de dia e mes
            foreach($div_data_dia as $i => $dd) { array_push($data_array, $dd.' '.$div_data_mes[$i]); }

            foreach($data_array as $i => $da) {
                $dados_bb += [
                    $i => ['data' => $da, 'link_texto' => $link_texto[$i]]
                ];
            }

            return response()->json($dados_bb);
        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

    private function tcuArrayNotice() {
        try {
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
        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

    private function minJusArrayNotice() {
        try {
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
        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

    private function seplanArrayNotice() {
        try {
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
        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
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
        try {
            $crawler = $this->cliente->request("GET", "https://site.tce.ma.gov.br/index.php/noticias");

            $dados_array_tce = [];

            $tr_link_texto = $crawler->filter('div[class="blog"] div[class^="items"] h2 a')
                ->extract(['_text', 'href']);
            $tr_date = $crawler->filter('div[class="blog"] div[class^="items"] dl dd')
                ->extract(['_text']);
            $image = $crawler->filter('div[class="blog"] div[class^="items"]
                div[class="container-fluid"] div[class="entry-image intro-image"]
                img')->extract(['src']);

            foreach($tr_date as $i => $trd) {
                $dados_array_tce += [
                    $i => ['data' => trim($trd), 'link_texto' => $tr_link_texto[$i]]
                ];
            }

            return $dados_array_tce;
        } catch(\Exception $e) {
            return response()->json(['erro' => true, 'msg' => $e->getMessage()], 501);
        }
    }

     /***
     * Explode passando array como delimitador e removendo array
     * Extraído: https://stackoverflow.com/questions/2860238/exploding-by-array-of-delimiters
     *
     * @param array $delim
     * @param string $input
     */
    private function explode_by_array($delim, $input) {
        $unidelim = $delim[0];
        $step = str_replace($delim, $unidelim, $input);
        $step = explode($unidelim, $step);
        // $step = substr($step, 0, strpos($step, " "));
        array_pop($step);
        if(array_key_exists(0, $step)) { return array_filter($step); }
    }
}
