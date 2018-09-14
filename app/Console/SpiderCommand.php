<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 17:14
 */

namespace App\Console;


use App\Http\Controllers\PC\Live\LiveController;
use App\Http\Controllers\PC\RecommendsController;
use App\Http\Controllers\PC\TaskController;
use App\Http\Controllers\PC\TopicController;
use App\Http\Controllers\Spider\SpiderLQB;
use App\Model\Article;
use App\Model\ArticleContent;
use App\Model\SpiderArticle;
use App\Model\SpiderArticleContent;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpiderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:run {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '爬虫';

    /**
     * Create a new command instance.
     * HotMatchCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $type = $this->argument('type');
        if ($type == 'lqb_article_list')
            SpiderLQB::spiderArticleList();
        if ($type == 'lqb_articles') {
            $articles = SpiderArticle::where('from','lqb')
                ->where('had_spider',0)
                ->take(10)->get();
            foreach ($articles as $article){
                SpiderLQB::spiderArticle($article);
            }

        }
    }
}