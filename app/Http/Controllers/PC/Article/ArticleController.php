<?php

namespace App\Http\Controllers\PC\Article;

use App\Http\Controllers\Controller;
use App\Model\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public static function path($id){
        return '/news/'.substr($id,0,2).'/'.substr($id,2,2).'/'.$id.'.html';
    }

    public static function urlPath($id){
        return '/news/'.$id.'.html';
    }

    public function staticIndex(Request $request){
        $html = $this->index($request);
        if (isset($html) && strlen($html) > 0){
            Storage::disk("public")->put("/www/news/index.html", $html);
        }
    }

    public function index(Request $request){
        $articles = Article::where('status','>',0)
            ->orderby('created_at','asc')
            ->take(10)
            ->get();
        $json['articles'] = $articles;
        $json['title'] = '体坛资讯-神马直播';
        $json['webIndex'] = 'news';
        return view('pc.article_list',$json);
    }

    public function detail($article){
        $json['article'] = $article;
        $articles = Article::where('status','>',0)
            ->orderby('created_at','asc')
            ->take(10)
            ->get();
        $json['articles'] = $articles;
        $json['title'] = $article['title'];
        $json['webIndex'] = 'news';
        return view('pc.article',$json);
    }

    public function detailHTML(Request $request,$id){
        $article = Article::find($id);
        return $this->detail($article);
    }
}
