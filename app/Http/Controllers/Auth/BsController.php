<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Admin\UploadTrait;
use App\Http\Controllers\PC\Article\ArticleController;
use App\Model\Article;
use App\Model\ArticleContent;
use App\Model\SpiderArticle;
use App\Model\SpiderArticleContent;
use App\Models\Anchor\Anchor;
use App\Models\Anchor\AnchorRoom;
use App\Models\Anchor\AnchorRoomTag;
use App\Models\Match\BasketMatch;
use App\Models\Match\Match;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BsController extends Controller
{
    const BS_LOGIN_SESSION = 'JBS_BS_LOGIN_SESSION';

    public function __construct()
    {
        $this->middleware('backstage_auth')->except(['login', 'logout']);
    }

    /**
     * 登录页面、登录逻辑
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        $method = $request->getMethod();
        if (strtolower($method) == "get") {//跳转到登录页面
            return view('backstage.index');
        }

        $target = $request->input("target");
        $phone = $request->input("phone", '');
        $password = $request->input("password");
        $remember = $request->input("remember", 0);

        //判断是否登录
        if ($password != 'bjbjbjbj' && $phone != '13430243280') {
            return back()->with(["error" => "账户或密码错误", 'phone' => $phone]);
        }
        $target = empty($target) ? '/bs/info' : $target;
        session([self::BS_LOGIN_SESSION => 'bj']);//登录信息保存在session
        if ($remember == 1) {
            //$c = cookie(self::BS_LOGIN_SESSION, $token, 60 * 24 * 7, '/', 'aikq.cc', false, true);
            return response()->redirectTo($target);//->withCookies([$c]);
        } else {
            return response()->redirectTo($target);
        }
//        return back()->with(["error" => "账户或密码错误"]);
    }

    /**
     * 退出登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $request->admin_user = null;
        session()->forget(self::BS_LOGIN_SESSION);
        setcookie(self::BS_LOGIN_SESSION, '', time() - 3600, '/', 'xijiazhibo.cc');
        return response()->redirectTo('/bs/login');
    }

    public function info(Request $request){
        $result = array();
        $articles = SpiderArticle::orderby('date','desc')
            ->orderby('created_at','desc')
            ->paginate(40);
        $result['articles'] = $articles;
        return view('backstage.info',$result);
    }

    public function articlePublish(Request $request){
        $id = $request->input('id');

        $spider = SpiderArticle::find($id);
        if ($spider->aid > 0){
            return response()->json(array('code'=>-1,'message'=>'has publish'));
        }
        $content = SpiderArticleContent::find($spider->id);
        if (is_null($content)){
            return response()->json(array('code'=>-1,'message'=>'spider data error'));
        }
        $result =  DB::transaction(function () use ($spider, $content) {
            $r_article = new Article();
            $r_article->title = $spider->title;
            $r_article->desc = $spider->desc;
            $r_article->save();
            $spider->aid = $r_article->id;
            $spider->save();
            $r_content = new ArticleContent();
            $r_content->content = $content->content;
            $r_content->id = $r_article->id;
            $r_content->from = '乐球吧';
            $r_content->save();

            $con = new ArticleController();
            $html = $con->detail($r_article);
            if (isset($html) && strlen($html) > 0){
                Storage::disk("public")->put('/www'.ArticleController::path($r_article->id), $html);
                $r_article->url_path = 'news/'.$r_article->id.'.html';
                $r_article->path =  '/www'.ArticleController::path($r_article->id);
                $r_article->status = 1;
                $r_article->save();
            }
        });

        $spider = SpiderArticle::find($id);
        if ($spider->aid > 0) {
            $this->push('http://www.xijiazhibo.cc'.'/news/'.$spider->aid.'.html');
        }

        return response()->json(array('code'=>0,'message'=>'success'));
    }

    private function push($url){
        return;
        $urls = array(
            $url
        );
        $api = 'http://data.zz.baidu.com/urls?site=www.xijiazhibo.cc&token=OY6k7W57tNeCnkh1';
        $ch = curl_init();
        $options =  array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
//        echo $result;
    }
}
