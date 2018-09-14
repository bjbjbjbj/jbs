<?php

namespace App\Http\Controllers\Spider;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Spider\SpiderLQB;
use App\Models\PcArticle;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    use SpiderLQB;
    public function index($action, Request $request)
    {
        if (method_exists($this, $action)) {
            $this->$action($request);
        } else {
            echo "Error: Not Found action 'IndexController->$action()'";
        }
    }

    public function lqbArticles(Request $request){
        SpiderLQB::spiderArticleList();
    }

    public function lqgArticle(Request $request){
        SpiderLQB::spiderArticle(null);
    }
}
