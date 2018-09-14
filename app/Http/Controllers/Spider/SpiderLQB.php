<?php
/**
 * Created by PhpStorm.
 * User: BJ
 * Date: 16/12/22
 * Time: 下午12:43
 */

namespace App\Http\Controllers\Spider;

use App\Http\Controllers\LotteryTool;
use App\Model\SpiderArticle;
use App\Model\SpiderArticleContent;
use App\Models\LiaoGouModels\LiaogouAlias;
use App\Models\LiaoGouModels\Match;
use App\Models\LiaoGouModels\MatchesAfter;
use App\Models\LiaoGouModels\MatchEuropePrediction;
use App\Models\WinModels\SportBetting;
use App\Models\WinModels\SportBettingSpiderLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

include_once('lib/simple_html_dom.php');

trait SpiderLQB
{
    public static function spiderArticleList(){
        $result = array();
        for ($i = 0 ; $i < 10 ; $i++) {
            $url = 'http://www.leqiuba.cc/zixun/list_6_'.($i+1).'.html';
            $articles = SpiderLQB::spiderArticleListByUrl($url);
            if (count($articles) == 0){
                break;
            }
            $result = array_merge($result,$articles);
        }
        //保存
        foreach ($result as $item){
            $article = SpiderArticle::where('url',$item['url'])->first();
            if (is_null($article)){
                $article = new SpiderArticle();
                $article->url = $item['url'];
                $article->title = $item['title'];
                $article->date = $item['date'];
                $article->desc = $item['desc'];
                $article->from = 'lqb';
                $article->save();
            }
        }
    }

    public static function spiderArticle($article){
        $url = $article->url;
        echo $url.'<br>';
        $curl = curl_init();
        //設置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);
        //設置首標
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //設置cURL參數，要求結果保存到字符串中還是輸出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //運行cURL，請求網頁
        $data = curl_exec($curl);
        //關閉URL請求
        curl_close($curl);
        //顯示獲得的數據
//        var_dump($data);
        $html = new \simple_html_dom();
        $html->load($data);
        $content = $html->find('div.articles-cont');
        $ps = $content[0]->find('p');
        $article_content = '';
        foreach ($ps as $p){
            $imgs = $p->find('img');
            if (count($imgs) > 0){
                $article_content = $article_content.'<img src="'.$imgs[0]->src.'"></img>';
                $spans = $p->find('span');
                if (count($spans)) {
                    $article_content = $article_content.'<span>' . $spans[0]->plaintext . '</span>';
                }
            }
            else{
                $article_content = $article_content.'<p>'.$p->plaintext.'</p>';
            }
        }
        DB::transaction(function () use ($article,$article_content) {
            $article->had_spider = 1;
            if ($article->save()){
                $content = new SpiderArticleContent();
                $content->content = $article_content;
                $content->id = $article->id;
                $content->save();
            }
        });
    }

    private static function spiderArticleListByUrl($url)
    {
        echo $url.'<br>';
        if (is_null($url)) {
            echo 'spiderSportBettingOdd no date' . '</br>';
            return;
        }
        $curl = curl_init();
        //設置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);
        //設置首標
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //設置cURL參數，要求結果保存到字符串中還是輸出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //運行cURL，請求網頁
        $data = curl_exec($curl);
        //關閉URL請求
        curl_close($curl);
        //顯示獲得的數據
//        var_dump($data);
        $html = new \simple_html_dom();
        $html->load($data);

        //数据
        $resultDivs = $html->find('div.list-item');
        $articles = array();
        if (isset($resultDivs) && !empty($resultDivs)) {
            foreach ($resultDivs as $resultDiv){
                $result = array();
                $a = $resultDiv->find('a')[0];
                $result['url'] = $a->getAttribute('href');
                $content = $resultDiv->find('div.article-title')[0]->plaintext;
                $content = explode('                         ',$content)[1];
                $content = str_replace(' ','',$content);
                $result['title'] = $content;
                $content = $resultDiv->find('div.article-desc')[0]->plaintext;
                $content = str_replace(' ','',$content);
                $result['desc'] = $content;
                $date = $resultDiv->find('div.article-info span')[1]->plaintext;
                $date = str_replace(' ','',$date);
                $result['date'] = $date;
                $now = date('y-m-d');
                if (strtotime($date) == strtotime($now)){
                    $articles[] = $result;
                }
            }
        } else {
            echo 'spider spiderArticleList error </br>';
        }
        return $articles;
    }
}