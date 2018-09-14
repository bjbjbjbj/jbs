<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/7
 * Time: 11:40
 */

namespace App\Http\Controllers\Mobile\Live;

use App\Console\LiveDetailCommand;
use App\Console\NoStartPlayerJsonCommand;
use App\Models\Match\MatchLiveChannel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Cookie;

class LiveController extends Controller
{
    const BET_MATCH = 1;
    const LIVE_HD_CODE_KEY = 'LIVE_HD_CODE_KEY';

    /**
     * 首页（首页、竞彩、足球、篮球）缓存
     * @param Request $request
     */
    public function staticIndex(Request $request){
//        $this->basketballLivesStatic($request);
        $this->footballLivesStatic($request);
    }

    /**
     * 竞彩缓存
     * @param Request $request
     */
    public function betLivesStatic(Request $request){
        $html = $this->betLives(new Request());
        try {
            Storage::disk("public")->put("/static/betting.html",$html);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * 静态化站长合作
     * @param Request $request
     */
    public function businessStatic(Request $request) {
        $businessHtml = $this->business($request);
        try {
            Storage::disk("public")->put("/live/business.html", $businessHtml);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * 首页缓存
     * @param Request $request
     */
    public function livesStatic(Request $request){
        $html = $this->lives(new Request());
        try {
            Storage::disk("public")->put("/static/index.html",$html);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * 篮球缓存
     * @param Request $request
     */
    public function basketballLivesStatic(Request $request){
        $html = $this->basketballLives(new Request());
        try {
            Storage::disk("public")->put("/static/basketball.html",$html);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * 足球缓存
     * 足球缓存
     * @param Request $request
     */
    public function footballLivesStatic(Request $request){
        $html = $this->footballLives(new Request());
        try {
            Storage::disk("public")->put("/static/football.html",$html);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * PC直播赛事的json
     * @param Request $request
     */
    public function allLiveJsonStatic(Request $request) {
        $this->liveJson();//首页赛事缓存
        //usleep(300);
        //$this->footballLiveJson();//首页足球赛事缓存
        //usleep(300);
        //$this->basketballLiveJson();//首页篮球赛事缓存
        //usleep(300);
        //$this->liveJson(self::BET_MATCH);//首页竞彩赛事缓存
    }

    /**
     * 首页、竞彩赛事
     * @param $bet
     * @return mixed
     */
    protected function liveJson($bet = '') {
        try {
            $ch = curl_init();
            $url = 'http://api.aikq.cc/json/lives.json';
//            echo $url;
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            $server_output = curl_exec ($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ($ch);
            if ($code >= 400 || empty($server_output)) {
                return;
            }
            if ($bet == self::BET_MATCH) {
                Storage::disk("public")->put("/static/json/bet-lives.json", $server_output);
            } else{
                Storage::disk("public")->put("/static/json/lives.json", $server_output);
            }

        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    /**
     * 篮球赛事json缓存
     */
    protected function basketballLiveJson() {
        try {
            $ch = curl_init();
            $url = env('LIAOGOU_URL')."aik/basketballLivesJson";
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $server_output = curl_exec ($ch);
            curl_close ($ch);
            Storage::disk("public")->put("/static/json/basketball-lives.json", $server_output);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    /**
     * 足球赛事json缓存
     */
    protected function footballLiveJson() {
        try {
            $ch = curl_init();
            $url = env('LIAOGOU_URL')."aik/footballLivesJson";
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $server_output = curl_exec ($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ($ch);
            if ($code >= 400 || empty($server_output)) {
                return;
            }
            Storage::disk("public")->put("/static/json/football-lives.json", $server_output);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }
    //===============================================================================//

    /**
     * 播放失败
     * @param Request $request
     * @return json
     */
    public function liveError(Request $request){
        $cid = $request->input('cid',0);
        if ($cid <= 0){
            return;
        }
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."/livesError?cid=" . $cid;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        return $json;
    }

    /**
     * 首页直播列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function lives(Request $request) {
        //$json = $this->getLives();
        $cache = Storage::get('/public/static/json/lives.json');
        $json = json_decode($cache, true);
        if (is_null($json)){
            //return abort(404);
        }
        //$json['subjects'] = SubjectController::getSubjects();
        $json['week_array'] = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
        $json['check'] = 'all';
        return view('mobile.home', $json);
    }

    /**
     * 商务合作
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function business(Request $request) {
        $cache = Storage::get('/public/static/json/lives.json');
        $json = json_decode($cache, true);
        if (is_null($json)){
            //return abort(404);
        }
        $json['week_array'] = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
        $json['title'] = "爱看球-视频调用";
        $json['check'] = 'business';
        return view('pc.business', $json);
    }

    /**
     * 竞彩直播比赛列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function betLives(Request $request) {
        //$json = $this->getLives(self::BET_MATCH);
        $cache = Storage::get('/public/static/json/bet-lives.json');
        $json = json_decode($cache, true);
        if (is_null($json)){
            return abort(404);
        }
        $json['check'] = 'bet';
        $json['week_array'] = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
        return view('mobile.home', $json);
    }

    /**
     * 获取有直播的比赛。
     * @param string $bet
     * @return mixed
     */
    protected function getLives($bet = '') {
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."aik/livesJson?bet=" . $bet;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        //$code = curl_getinfo($ch, CURLE_RECV_ERROR);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        return $json;//$this->toNewMatchArray($json);
    }

    /**
     * 拼凑新的返回对象
     * @param $json
     * @return mixed
     */
    protected function toNewMatchArray($json) {
        if (is_null($json)) return null;

        $start_matches = [];//比赛中的赛事
        $wait_matches = [];//稍后的比赛

        $matches = $json['matches'];
        foreach ($matches as $time=>$match_array) {
            $w_match = [];
            foreach ($match_array as $index=>$match) {
                if ($match['isMatching']) {
                    $start_matches[] = $match;
                } else {
                    $w_match[] = $match;
                }
            }
            if (count($w_match) > 0) {
                $wait_matches[$time] = $w_match;
            }
        }

        $result['play_matches'] = $start_matches;
        $result['matches'] = $wait_matches;
        return $result;
    }

    /**
     * 首页直播列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function basketballLives(Request $request) {
//        $ch = curl_init();
//        $url = env('LIAOGOU_URL')."/basketballLivesJson";
//        curl_setopt($ch, CURLOPT_URL,$url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $server_output = curl_exec ($ch);
//        curl_close ($ch);
//        $json = json_decode($server_output,true);
        $cache = Storage::get('/public/static/json/basketball-lives.json');
        $json = json_decode($cache, true);
        if (is_null($json)){
            return abort(404);
        }
        $json['check'] = 'basket';
        $json['week_array'] = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
        return view('mobile.home', $json);
    }

    /**
     * 首页直播列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function footballLives(Request $request) {
//        $ch = curl_init();
//        $url = env('LIAOGOU_URL')."/footballLivesJson";
//        curl_setopt($ch, CURLOPT_URL,$url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $server_output = curl_exec ($ch);
//        curl_close ($ch);
//        $json = json_decode($server_output,true);
        $cache = Storage::get('/public/static/json/football-lives.json');
        $json = json_decode($cache, true);
        if (is_null($json)){
            return abort(404);
        }
        $json['check'] = 'foot';
        $json['week_array'] = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
        return view('mobile.home', $json);
    }

    /**
     * 直播终端
     * @param Request $request
     * @param $id
     * @param bool $immediate  是否即时获取数据
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request, $id, $immediate = false) {
        $ch = curl_init();
        if ($immediate) {
            $url = env('LIAOGOU_URL')."aik/lives/detailJson/$id";
        } else {
            $url = env('LIAOGOU_URL')."aik/lives/detailJson/$id" . '.json';
        }
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT,8);
        $server_output = curl_exec ($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        if (isset($json['match'])) {
            $match = $json['match'];
            $json['title'] = '爱看球-' . date('m月d H:i', strtotime($match['time'])) . ' ' . $match['lname'] . ' ' . $match['hname'] . ' VS ' . $match['aname'];
        }
        return view('pc.live.video', $json);
    }

    /**
     * 篮球直播终端
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function basketDetail(Request $request, $id, $immediate = false) {
        $ch = curl_init();
        if ($immediate) {
            $url = env('LIAOGOU_URL')."aik/lives/basketDetailJson/$id";
        } else {
            $url = env('LIAOGOU_URL')."aik/lives/basketDetailJson/$id" . '.json';
        }
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT,8);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        if (isset($json['match'])) {
            $match = $json['match'];
            $json['title'] =  '爱看球-' . date('m月d H:i', strtotime($match['time'])) . ' ' . $match['lname'] . ' ' . $match['hname'] . ' VS ' . $match['aname'];
        }
        return view('pc.live.video', $json);
    }

    /**
     * 自建直播终端
     * @param Request $request
     * @param $id
     * @param $immediate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function otherDetail(Request $request, $id, $immediate = false) {
        $ch = curl_init();
        if ($immediate) {
            $url = env('LIAOGOU_URL')."aik/lives/otherDetailJson/$id";
        } else {
            $url = env('LIAOGOU_URL')."aik/lives/otherDetailJson/$id" . '.json';
        }
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT,8);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        if (isset($json['match'])) {
            $match = $json['match'];
            $json['title'] = '爱看球-' . date('m月d H:i', strtotime($match['time'])) . ' ' . $match['lname'] . ' ' . $match['hname'] . ' VS ' . $match['aname'];
        }
        return view('pc.live.video', $json);
    }

    /**
     * 播放器channel
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function matchPlayerChannel(Request $request){
        return view('pc.live.match_channel',array('cdn'=>env('CDN_URL'),'host'=>'www.aikq.cc'));
    }

    /**
     * 播放器
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function matchPlayer(Request $request){
        return view('pc.live.match_player');
    }

    /**
     * 播放器
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function player(Request $request){
        return view('pc.live.player',array('cdn'=>env('CDN_URL'),'host'=>'www.aikq.cc'));
    }

    public function share(Request $request){
        return view('pc.live.player');
    }

    /**
     * 获取天天直播源js
     * @param Request $request
     * @param $mid
     * @return mixed
     */
    public function getTTZBLiveUrl(Request $request,$mid){
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."match/live/url/channel/$mid".'?sport='.$request->input('sport',1);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    /**
     * 获取无插件playurl
     * @param Request $request
     * @param $mid
     * @return mixed
     */
    public function getWCJLiveUrl(Request $request,$mid){
        $ch = curl_init();
        $isMobile = \App\Http\Controllers\Controller::isMobile($request)?1:0;
        $url = env('LIAOGOU_URL')."match/live/url/channel/$mid".'?isMobile='.$isMobile.'&sport='.$request->input('sport',1);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    /**
     * 获取playurl根据比赛id
     * @param Request $request
     * @param $mid
     * @return mixed
     */
    public function getLiveUrlMatch(Request $request,$mid){
        $ch = curl_init();
        $isMobile = \App\Http\Controllers\Controller::isMobile($request)?1:0;
        $url = env('LIAOGOU_URL')."match/live/url/match/$mid".'?isMobile='.$isMobile.'&sport='.$request->input('sport',1);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    public function getLiveUrlMatch2(Request $request,$mid,$sport,$isMobile){
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."match/live/url/match/$mid".'?isMobile='.($isMobile?1:0).'&sport='.$sport;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    public function getLiveUrlMatchM(Request $request,$mid,$sport){
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."match/live/url/match/$mid".'?isMobile=1&sport='.$sport;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    public function getLiveUrlMatchPC(Request $request,$mid,$sport){
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."match/live/url/match/$mid".'?isMobile=0&sport='.$sport;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    /**
     * 获取无插件playurl
     * @param Request $request
     * @param $mid
     * @return mixed
     */
    public function getLiveUrl(Request $request,$mid){
        $code = isset($_COOKIE[self::LIVE_HD_CODE_KEY]) ? $_COOKIE[self::LIVE_HD_CODE_KEY] : '';//$request->cookie(self::LIVE_HD_CODE_KEY);//cookie的验证码//$code = $request->cookie(self::LIVE_HD_CODE_KEY);
        $sport = $request->input('sport',1);
        $ch = curl_init();
        $isMobile = \App\Http\Controllers\Controller::isMobile($request)?1:0;
        $url = env('LIAOGOU_URL')."match/live/url/channel/$mid".'?breakTTZB=break&isMobile='.$isMobile.'&sport='.$sport . '&code=' . $code;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        if ($server_output == false) {
            return "";
        }
        return $server_output;
    }

    /**
     * 正在直播列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLiveMatches(Request $request) {
        $mid = $request->input('mid');//当前页面直播中的id 格式应该是 id1-id2...-idn
        $ch = curl_init();
        $sport = $request->input('sport',0);
        $url = env('LIAOGOU_URL')."aik/lives/liveMatchesJson?sport=".$sport."&mid=".$mid;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
//        dump($json);
        $json['showRadio'] = $request->input('showRadio',1);
        return view('pc.live.living_list', $json);
    }

    /**
     * 多视频页面
     * @param Request $request
     * @param $param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function multiLive(Request $request, $param) {
        if (!preg_match('/^\d+(-\d+){0,3}$/', $param)) {
//            return abort(404);
        }
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."lives/multiLiveJson/".$param;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
//        dump($json);
        return view("pc.live.multiScreen",$json);
    }

    /**
     * 多视频页面Div
     * @param Request $request
     * @param $param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function multiLiveDiv(Request $request, $param) {
        if (!preg_match('/^\d+(-\d+){0,3}$/', $param)) {
//            return abort(404);
        }
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."lives/multiLiveDivJson/f".$param;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        return view("pc.live.multiScreenItem",$json);
    }
    /**
     * 多视频页面Div
     * @param Request $request
     * @param $param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function multiBasketLiveDiv(Request $request, $param) {
        if (!preg_match('/^\d+(-\d+){0,3}$/', $param)) {
//            return abort(404);
        }
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."lives/multiLiveDivJson/b".$param;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
//        dump($json);
//        dump($url);
        return view("pc.live.multiScreenItem",$json);
    }

    /**
     * 多视频页面
     * @param Request $request
     * @param $param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function multiBasketLive(Request $request, $param) {
        if (!preg_match('/^\d+(-\d+){0,3}$/', $param)) {
            return abort(404);
        }
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."aik/lives/multiBasketLiveJson/".$param;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
//        dump($json);
//        dump($url);
        return view("pc.live.multiScreen",$json);
    }

    /**
     * 足球赛事对应推荐
     * @param Request $request
     * @param $mid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getArticleOfFMid(Request $request,$mid){
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."lives/football/recommend/$mid";
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        return view('pc.live.detail_recommend', $json);
    }

    /**
     * 篮球赛事对应推荐
     * @param Request $request
     * @param $mid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getArticleOfBMid(Request $request,$mid){
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."lives/basketball/recommend/$mid";
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        return view('pc.live.detail_recommend', $json);
    }

    /**
     * 清除底部推荐缓存
     * @param Request $request
     * @param $mid
     */
    public function deleteStaticHtml(Request $request,$mid){
        $sport = $request->input('sport',1);
        try {
            Storage::disk("public")->delete("/player/recommends/live/".($sport == 1 ? 'football':'basketball')."/recommend/$mid.html");
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @param Request $request
     */
    public function staticHtml(Request $request){
        $ch = curl_init();
        $url = env('LIAOGOU_URL')."aik/livesJson?bet=" . 0;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $json = json_decode($server_output,true);
        $json = $json['matches'];
        foreach ($json as $index=>$datas){
            foreach ($datas as $match){
                $mid = $match['mid'];
                if ($match['sport'] == 1)
                    $html = $this->getArticleOfFMid(new Request(),$mid);
                else
                    $html = $this->getArticleOfBMid(new Request(),$mid);
                try {
                    Storage::disk("public")->put("/live/".($match['sport'] == 1 ? 'football':'basketball')."/recommend/$mid.html", $html);
                    //Storage::disk("public")->put("/player/recommends/live/".($match['sport'] == 1 ? 'football':'basketball')."/recommend/$mid.html", $html);
                } catch (\Exception $exception) {
                    echo $exception->getMessage();
                }
            }
        }
    }

    /**
     * 静态化直播终端页
     * @param Request $request
     */
    public function staticLiveDetail(Request $request) {
        $cache = Storage::get('/public/static/json/lives.json');
        $json = json_decode($cache, true);
        if (is_null($json) || !isset($json['matches'])) {
            echo '获取数据失败';
            return;
        }
        $json = $json['matches'];
        foreach ($json as $index=>$datas){
            foreach ($datas as $match){
                $mid = $match['mid'];
                $time = isset($match['time']) ? $match['time'] : 0;
                $now = time();
                if ($time == 0 ) {//只静态化赛前4小时内 的比赛终端。
                    continue;
                }
                $start_time = strtotime($time);//比赛时间
                $flg_1 = $start_time >= $now && $now + 5 * 60 * 60 >= $start_time;//开赛前1小时
                $flg_2 = false;//$start_time <= $now && $start_time + 3 * 60 * 60  >= $now;//开赛后3小时 开赛后编辑修改，会即时更新。
                if ( $flg_1 || $flg_2 ) {
                    //$match['hname'] . ' VS ' . $match['aname'] . ' ' . $match['time'];
                    try {
                        LiveDetailCommand::flushLiveDetailHtml($mid, $match['sport']);
                    } catch (\Exception $exception) {
                        dump($exception);
                    }
                }
            }
        }
    }

    /**
     * 更新视频终端。
     * @param Request $request
     * @param $mid
     * @param $sport
     */
    public function staticLiveDetailById(Request $request, $mid, $sport) {
        $ch_id = $request->input('ch_id');
        try {
            $mCon = new \App\Http\Controllers\Mobile\Live\LiveController();
            if ($sport == 1) {
                $html = $this->detail($request, $mid, true);
                if (!empty($html)) {
                    Storage::disk("public")->put("/live/football/". $mid. ".html", $html);
                }

                $mCon->liveDetailStatic($request, $mid, $sport);//wap 页面静态化

                //每一个比赛的player页面生成
                $phtml = $this->matchPlayerChannel($request);
                if (!empty($phtml)) {
                    Storage::disk("public")->put("/live/spPlayer/player-" . $mid . '-' . $sport . ".html", $phtml);
                    //暂时兼容旧链接
                    Storage::disk("public")->put("/live/spPlayer/match_channel-" . $mid . '-' . $sport . ".html", $phtml);
                }
                //match.json
                $mjson = $this->getLiveUrlMatch2(new Request(),$mid,$sport,true);
                if (!empty($mjson)) {
                    Storage::disk("public")->put("/match/live/url/match/m/" . $mid . "_" . $sport .".json", $mjson);
                }
                $pjson = $this->getLiveUrlMatch2(new Request(),$mid,$sport,false);
                if (!empty($mjson)) {
                    Storage::disk("public")->put("/match/live/url/match/pc/" . $mid . "_" . $sport .".json", $pjson);
                }
            } else if($sport == 2){
                $html = $this->basketDetail($request, $mid, true);
                if (!empty($html)) {
                    Storage::disk("public")->put("/live/basketball/". $mid. ".html", $html);
                }

                $mCon->liveDetailStatic($request, $mid, $sport);//wap 页面静态化

                //每一个比赛的player页面生成
                $controller = new LiveController(new Request());
                $phtml = $controller->matchPlayerChannel($request);
                if (!empty($phtml)) {
                    Storage::disk("public")->put("/live/spPlayer/player-" . $mid . '-' . $sport . ".html", $phtml);
                    //暂时兼容旧链接
                    Storage::disk("public")->put("/live/spPlayer/match_channel-" . $mid . '-' . $sport . ".html", $phtml);
                }
                //match.json
                $mjson = $controller->getLiveUrlMatch2(new Request(),$mid, $sport,true);
                if (!empty($mjson)) {
                    Storage::disk("public")->put("/match/live/url/match/m/" . $mid . "_" . $sport .".json", $mjson);
                }
                $pjson = $controller->getLiveUrlMatch2(new Request(),$mid, $sport,false);
                if (!empty($mjson)) {
                    Storage::disk("public")->put("/match/live/url/match/pc/" . $mid . "_" . $sport .".json", $pjson);
                }
            } else if ($sport == 3) {
                $html = $this->otherDetail($request, $mid, true);
                if (!empty($html)) {
                    Storage::disk("public")->put("/live/other/". $mid. ".html", $html);
                }

                $mCon->liveDetailStatic($request, $mid, $sport);//wap 页面静态化

                //每一个比赛的player页面生成
                $phtml = $this->matchPlayerChannel($request);
                if (!empty($phtml)) {
                    Storage::disk("public")->put("/live/spPlayer/player-" . $mid . '-' . $sport . ".html", $phtml);
                }
                //match.json
                $mjson = $this->getLiveUrlMatch2(new Request(),$mid, $sport,true);
                if (!empty($mjson)) {
                    Storage::disk("public")->put("/match/live/url/match/m/" . $mid . "_" . $sport .".json", $mjson);
                }
                $pjson = $this->getLiveUrlMatch2(new Request(),$mid, $sport,false);
                if (!empty($mjson)) {
                    Storage::disk("public")->put("/match/live/url/match/pc/" . $mid . "_" . $sport .".json", $pjson);
                }
            }
            if (is_numeric($ch_id)) {
                $this->staticLiveUrl($request, $ch_id, true);
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * 静态化播放页面异步请求
     * @param Request $request
     */
    public function staticPlayerJson(Request $request) {
        //$json = $this->getLives();
        $cache = Storage::get('/public/static/json/lives.json');
        $json = json_decode($cache, true);

        if (!isset($json['matches'])) return;
        $matches = $json['matches'];
        foreach ($matches as $time=>$match_array) {
            foreach ($match_array as $match) {
                if (!isset($match) || !isset($match['time'])) {
                    continue;
                }
                $m_time = strtotime($match['time']);
                $status = $match['status'];
                $now = time();

                $flg_1 = $m_time >= $now && $now + 60 * 60 >= $m_time;//开赛前1小时
                $flg_2 = false;//$m_time <= $now && $m_time + 3 * 60 * 60  >= $now;//开赛后3小时
                if ($status > 0 || $flg_1 || $flg_2 ) {//1小时内的比赛静态化接口、天天源不做静态化。
                    if (isset($match['channels'])) {
                        //echo $match['hname'] . ' VS ' . $match['aname'] . ' ' . $match['time'];
                        $channels = $match['channels'];
                        foreach ($channels as $channel) {
                            $ch_id = $channel['id'];
                            if ($channel['type'] != MatchLiveChannel::kTypeTTZB) {
                                //$has_mobile = MatchLiveChannel::hasMobile($channel['type']);
                                //$this->staticLiveUrl($request, $ch_id, true);
                                NoStartPlayerJsonCommand::flushPlayerJson($ch_id, true);
                                usleep(100);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 静态化直播线路的json
     * @param Request $request
     * @param $id
     * @param $has_mobile
     */
    public function staticLiveUrl(Request $request, $id, $has_mobile = false) {
        try {
            $controller = new LiveController(new Request());
            $player = $controller->player($request);
            $has_mobile = $has_mobile || $request->input('has_mobile') == 1;
            //$json = $this->getLiveUrl($request, $id);
            //天天的源有效时间为100秒左右。超过时间则失效无法播放，需要重新请求。
            $ch = curl_init();
            $url = env('LIAOGOU_URL')."match/live/url/channel/$id".'?breakTTZB=break&isMobile=0&sport='.$request->input('sport',1);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);//5秒超时
            $pc_json = curl_exec ($ch);
            curl_close ($ch);
            if (!empty($pc_json)) {
                Storage::disk("public")->put("/match/live/url/channel/". $id . '.json', $pc_json);
                //每一个channel的player页面生成
                $json = json_decode($pc_json,true);
                if (strlen($player) > 0 && $json && array_key_exists('code',$json) && $json['code'] == 0) {
                    Storage::disk("public")->put("/live/player/player-" . $id . '-' . $json['type'] . ".html", $player);
                }
            }
            if ($has_mobile) {
                $ch = curl_init();
                $url = env('LIAOGOU_URL')."match/live/url/channel/$id".'?breakTTZB=break&isMobile=1&sport='.$request->input('sport',1);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);//5秒超时
                $mobile_json = curl_exec ($ch);
                curl_close ($ch);
                if (!empty($mobile_json)) {
                    Storage::disk("public")->put("/match/live/url/channel/mobile/". $id . '.json', $mobile_json);
                }
            } else {
                if (!empty($pc_json)) {
                    Storage::disk("public")->put("/match/live/url/channel/mobile/". $id . '.json', $pc_json);
                }
            }
        } catch (\Exception $e) {
            dump($e);
        }
    }

    /**
     * 刷新缓存
     * @param Request $request
     */
    public function flushVideoCache(Request $request) {
        $mid = $request->input('mid');//比赛ID
        $sport = $request->input('sport');//竞技类型
        $ch_id = $request->input('ch_id');//线路id
        try {
            if(!empty($sport) && in_array($sport, [1, 2]) && is_numeric($mid)) {
                if ($sport == 1) {
                    $html = $this->detail($request, $mid);
                    Storage::disk("public")->put("/live/football/". $mid. ".html", $html);
                } else {
                    $html = $this->basketDetail($request, $mid);
                    Storage::disk("public")->put("/live/basketball/". $mid. ".html", $html);
                }
                $mController = new \App\Http\Controllers\Mobile\Live\LiveController();
                $mController->liveDetailStatic($request, $mid, $sport);//刷新移动直播终端。
            }
            if (is_numeric($ch_id)) {
                $this->staticLiveUrl($request, $ch_id);
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public static function links() {
        $links = [];
//        $key = 'base_link_cache';
//        $server_output = Redis::get($key);
//
//        if (empty($server_output)) {
//            try {
//                $ch = curl_init();
//                $url = env('LIAOGOU_URL')."/json/link.json";
//                curl_setopt($ch, CURLOPT_URL,$url);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                $server_output = curl_exec($ch);
//                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//                curl_close ($ch);
//                if ($http_code >= 400) {
//                    $server_output = "";
//                }
//            } catch (\Exception $e) {
//                Log::error($e);
//            }
//            if (empty($server_output)) {
//                return $links;
//            }
//            Redis::setEx($key, 60 * 10, $server_output);
//        }
//
//        if (empty($server_output)) {
//            return $links;
//        }
//        $json = json_decode($server_output);
//        if (is_array($json)) {
//            foreach ($json as $j) {
//                $links[] = ['name'=>$j->name, 'url'=>$j->url];
//            }
//        }
        return $links;
    }

    /**
     * 输入验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validCode(Request $request) {
        $code = $request->input('code');
        if (empty($code)) {
            return response()->json(['code'=>401, 'msg'=>'请输入验证码']);
        }
        $r_code = Redis::get(self::LIVE_HD_CODE_KEY);
        $code = strtoupper($code);
        if ($code != $r_code) {
            return response()->json(['code'=>403, 'msg'=>'验证码错误']);
        }
        //$c = cookie(self::LIVE_HD_CODE_KEY, $code, strtotime('+10 years'), '/');
        setcookie(self::LIVE_HD_CODE_KEY, $code, strtotime('+10 years'), '/');
        return response()->json(['code'=>200, 'msg'=>'验证码正确']);//->withCookie($c);
    }

    /**
     * 接收验证码
     * @param Request $request
     * @param $code
     */
    public function recCode(Request $request, $code) {
        Redis::set(self::LIVE_HD_CODE_KEY, $code);
        try {
            $json = Storage::get('public/static/m/dd_image/images.json');
            $json = json_decode($json, true);
        } catch (\Exception $e) {
        }
        if (isset($json)) {
            $json['code'] = $code;
        } else {
            $json = ['code'=>$code];
        }
        Storage::disk('public')->put('/static/m/dd_image/images.json', json_encode($json));
    }

    /**
     * playurl
     * @param Request $request
     * @param $ch_id
     * @return mixed
     */
    public function getHLiveUrl(Request $request, $ch_id){
        $code = isset($_COOKIE[self::LIVE_HD_CODE_KEY]) ? $_COOKIE[self::LIVE_HD_CODE_KEY] : '';//$request->cookie(self::LIVE_HD_CODE_KEY);//cookie的验证码
        $r_code = Redis::get(self::LIVE_HD_CODE_KEY);//服务器的高清验证码
        try {
            //获取缓存文件 开始
            $server_output = Storage::get('/public/match/live/url/channel/' . $ch_id . '.json');//文件缓存
            //获取缓存文件 结束
        } catch (\Exception $exception) {
            $sport = $request->input('sport',1);
            $ch = curl_init();
            $isMobile = \App\Http\Controllers\Controller::isMobile($request) ? 1 : 0;
            $url = env('LIAOGOU_URL')."match/live/url/channel/$ch_id".'?breakTTZB=break&isMobile='.$isMobile.'&sport='.$sport . '&code=' . $code;
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec ($ch);
            curl_close ($ch);
        }
        $json = json_decode($server_output, true);
        if (is_null($json)) {
            return response()->json(['code'=>-1]);
        }
        if (isset($json['h_playurl'])) {
            if (!empty($code) && !empty($r_code) && strtoupper($code) == $r_code) {
                $json['playurl'] = $json['h_playurl'];
                $json['hd'] = true;
            }
            unset($json['h_playurl']);
        }
        return \response()->json($json);
    }

    /**
     * 抓取网络图片
     * @param Request $request
     */
    public function getImage(Request $request) {
        $type = $request->input('type');//广告图片类型 1.前置广告类( l ), 2 . 暂停广告类 ( d ) , 3. 缓冲广告类 (z)
        $patch = $request->input('patch');//图片路径
        $name = $request->input('name');
        $text = $request->input('text');
        if (!in_array($type, [1, 2, 3, 4, 5])) {
            echo "参数错误";
            return;
        }
        $save_patch = '/static/m/dd_image/';
        switch ($type) {
            case 1:
                $save_patch .= 'l/';
                break;
            case 2:
                $save_patch .= 'd/';
                break;
            case 3:
                $save_patch .= 'z/';
                break;
            case 4:
                $save_patch .= 'w/';
                break;
            case 5:
                $save_patch .= 'cd/';
                break;
        }
        $this->delStorageFiles('/public' . $save_patch);//删除图片
        //保存图片
        //获取远程文件所采用的方法
        //$url = "http://d.hiphotos.baidu.com/image/pic/item/8601a18b87d6277fcdb9b01d24381f30e924fc68.jpg";
        if (!empty($patch)) {
            $start = substr($patch, 0, 1);
            if($start == '/') {
                $patch = substr($patch, 1);
            }
            $url = env('LIAOGOU_URL') . $patch;//"http://img2.plures.net/0b215072-9862-4fdf-a5af-6c142c3aa95b";
            $ch = curl_init();

            $timeout = 10;
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, $timeout);
            $img = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($http_code >= 400) {
                echo "获取链接内容失败";
                return;
            }
            //header("Content-Type:image/png");
            $list = explode("/", $url);
            $ext = $list[count($list) - 1];
            $list = explode('?', $ext);
            $fileName = $list[0];
            $file_patch = $save_patch . $fileName;
            Storage::disk('public')->put($file_patch, $img);
        }
        $this->staticAdImages($type, $name, $text);
    }

    /**
     * 获取广告图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVideoAdImage(Request $request) {
        $patch = '/public/static/m/dd_image';
        $default_img = '/img/pc/demo.jpg';
        $l_file = $this->getStorageFirstFile($patch.'/l');
        $d_file = $this->getStorageFirstFile($patch.'/d');
        $z_file = $this->getStorageFirstFile($patch.'/z');
        $w_file = $this->getStorageFirstFile($patch.'/w');

        $l_file = empty($l_file) ? $default_img : str_replace('public/static', '', $l_file);
        $d_file = empty($d_file) ? $default_img : str_replace('public/static', '', $d_file);
        $z_file = empty($z_file) ? $default_img : str_replace('public/static', '', $z_file);
        $w_file = empty($w_file) ? $default_img : str_replace('public/static', '', $w_file);

        return \response()->json(['l'=>$l_file, 'd'=>$d_file, 'z'=>$z_file, 'w'=>$w_file]);
    }

    /**
     * 静态化广告文件
     * @param $type
     * @param $cd_name
     * @param $cd_text
     */
    protected function staticAdImages($type, $cd_name, $cd_text) {
        $patch = '/public/static/m/dd_image';
        $default_img = '/img/pc/demo.jpg';
        $l_file = $this->getStorageFirstFile($patch.'/l');
        $d_file = $this->getStorageFirstFile($patch.'/d');
        $z_file = $this->getStorageFirstFile($patch.'/z');
        $w_file = $this->getStorageFirstFile($patch.'/w');
        $cd_file = $this->getStorageFirstFile($patch.'/cd');

        $l_file = empty($l_file) ? $default_img : str_replace('public/static', '', $l_file);
        $d_file = empty($d_file) ? $default_img : str_replace('public/static', '', $d_file);
        $z_file = empty($z_file) ? $default_img : str_replace('public/static', '', $z_file);
        $w_file = empty($w_file) ? $default_img : str_replace('public/static', '', $w_file);
        $cd_file = empty($cd_file) ? '/img/pc/code.jpg' : str_replace('public/static', '', $cd_file);

        $r_code = Redis::get(self::LIVE_HD_CODE_KEY);
        $json = ['l'=>$l_file, 'd'=>$d_file, 'z'=>$z_file, 'w'=>$w_file, 'code'=>$r_code, 'cd'=>$cd_file];
        if ($type == 5) {
            $json['cd_name'] = $cd_name;
            $json['cd_text'] = $cd_text;
        }
        Storage::disk('public')->put('/static/m/dd_image/images.json', json_encode($json));
    }

    /**
     * @param Request $request
     * @return string|void
     */
    public function setActive(Request $request) {
        $type = $request->input('type');
        if ($type == 99) {
            //清空活动内容
            Storage::disk('public')->put('/static/m/dd_image/active.json', json_encode([]));
            return;
        }
        $active = $request->input('active');//json内容
        if (!empty($active)) {
            $active = urldecode($active);
        }
        $active = json_decode($active, true);
        //dump($active);
        if (!isset($active) || empty($active['code']) || empty($active['txt']) ) {
            return "参数错误";
        }
        $save_patch = '/static/m/dd_image/active/';
        $patch = $active['code'];

        $this->delStorageFiles('/public' . $save_patch);//删除图片

        //保存图片 开始
//        $start = substr($patch, 0, 1);
//        if($start == '/') {
//            $patch = substr($patch, 1);
//        }
        $url = $patch;
        $ch = curl_init();

        $timeout = 10;
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, $timeout);
        $img = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code >= 400) {
            echo "获取链接内容失败";
            return;
        }

        $list = explode("/", $url);
        $ext = $list[count($list) - 1];
        $list = explode('?', $ext);
        $fileName = $list[0];
        $file_patch = $save_patch . $fileName;
        Storage::disk('public')->put($file_patch, $img);
        //保存图片 结束

        $file_patch = str_replace('/static', '', $file_patch);
        $txt = $active['txt'];
        $pattern = '[\n+\r*|\r+\n*]';
        $txt = preg_replace($pattern, "\n", $txt);
        $active['txt'] = $txt;
        $active['code'] = $file_patch;
        Storage::disk('public')->put('/static/m/dd_image/active.json', json_encode($active));
    }

    /**
     * 获取文件夹下的第一个文件
     * @param $patch
     * @return mixed|string
     */
    protected function getStorageFirstFile($patch) {
        $files = Storage::files($patch);
        if (is_array($files) && count($files) > 0) {
            return $files[0];
        }
        return '';
    }

    /**
     * 删除文件夹下面的所有文件
     * @param $patch
     */
    protected function delStorageFiles($patch) {
        $files = Storage::files($patch);
        if (is_array($files)) {
            foreach ($files as $file) {
                Storage::delete($file);
            }
        }
    }

}