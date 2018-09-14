<html>
<head lang="zh-CN">
    <meta http-equiv="Content-Type" content="text/html;">
    <meta charset="UTF-8">
    <title>{{isset($title)?$title:'jrs,一个免费观看篮球、足球比赛的网站-神马直播'}}</title>
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="description" content="jrs直播-这不是一个在线免费观看篮球和足球比赛的直播网站、酷玩直播、英超直播、意甲西甲亚冠直播、NBA直播、斯诺克等比赛都可以流畅观看，还可以了解球员信息、比赛赛程、明日比赛预告等等-JRS">
    <meta name="keywords" content="jrs、jrs直播、看直播去哪里">

    <link rel="shortcut icon" href="http://mat1.gtimg.com/sports/kbsweb/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="http://mat1.gtimg.com/sports/kbsweb/styles/css_404836.css">

    <style type="text/css">
        .raceblockdiv{ width:790px;margin:0 auto;}
        .raceblock{ float:left;margin-left:-82px;margin-top:10px;width:790px;height:60px;border-width:1px;border-style: solid;border-color:#dddddd;background-color:#e3eaff;padding-top: 25px;font-size : 1.5em;text-align:center;color:#000000}
        .race{ margin:0px;margin-top:-1px;width:790px;height:60px;border-width:1px;border-style: solid;border-color:#dddddd;background-color:#ffffff;padding-top: 30px;font-size : 1.3em;text-align:center;color:#555555 }
        .raceRed{ margin:0px;margin-top:-1px;width:790px;height:60px;border-width:1px;border-style: solid;border-color:#eecccc;background-color:#ffeeee;padding-top: 30px;font-size : 1.3em;text-align:center;color:#555555 }
        .playbtn{float:left;height:30px;margin-left:100px;width:120px;top:-10px;padding-top: 5px;text-align:center;color:#ffffff;background-color:#3377ee }
        .racename{float:left;width:200px;top:-3px;font-size:1.2em;color:#222222;}
        .racetime{float:left;width:140px;font-weight:bold}
        .racecat{float:left;width:160px;color:#888888;}
        .racediv{float:left;margin-left:8px}
        .races{float:left;margin-left:0px;width:790px;height:60px;border-width:1px;border-style: solid;border-color:#dddddd;background-color:#e3eaff;padding-top: 25px;font-size : 1.4em;text-align:center;color:#0033ff}
    </style>
    @yield('css')
    {{--<script src="//{{ \Illuminate\Support\Facades\Request::getHost() }}:6001/socket.io/socket.io.js"></script>--}}
    <script type="text/javascript">
        function browserRedirect() {
            var sUserAgent= navigator.userAgent.toLowerCase();
            var bIsIpad= sUserAgent.match(/ipad/i) == "ipad";
            var bIsIphoneOs= sUserAgent.match(/iphone os/i) == "iphone os";
            var bIsMidp= sUserAgent.match(/midp/i) == "midp";
            var bIsUc7= sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
            var bIsUc= sUserAgent.match(/ucweb/i) == "ucweb";
            var bIsAndroid= sUserAgent.match(/android/i) == "android";
            var bIsCE= sUserAgent.match(/windows ce/i) == "windows ce";
            var bIsWM= sUserAgent.match(/windows mobile/i) == "windows mobile";

            if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {

                window.location.href= '/m/index.html';

            }
            else{

            }
        }
        browserRedirect();
    </script>
</head>

<body class="watchgame game-list" data-pageid="0" style="overflow:auto;">

<div class="kbs-header color-whitebg color-gray6">
    <ul class="inline t16">
        <li>
            <a href="/" class="boss" data-mod="导航栏" data-action="看比赛logo"> <img alt="jrs直播" src="/img/jrstv.png">
            </a>
        </li>
        @if(isset($webIndex) && $webIndex == 'news')
            <li class="tabList cur">
                <a href="/" style="background-color:#f7f7f7;color:#3355ee" class="boss kbs-tab-on" data-mod="导航栏" data-action="看比赛">直播</a>
            </li>
            <li class="tabList cur">
                <a href="/news/" style="background-color:#3355ee;color:#f7f7f7" class="boss kbs-tab-on" data-mod="导航栏" data-action="看比赛">资讯</a>
            </li>
        @else
            <li class="tabList cur">
                <a href="/" style="background-color:#3355ee;color:#f7f7f7" class="boss kbs-tab-on" data-mod="导航栏" data-action="看比赛">直播</a>
            </li>
            <li class="tabList cur">
                <a href="/news/" style="background-color:#f7f7f7;color:#3355ee" class="boss kbs-tab-on" data-mod="导航栏" data-action="看比赛">资讯</a>
            </li>
        @endif
        <div style="float:left;margin:5px;margin-left:30px;padding-top:15px;color:#ffffff"><a style="color:#555555;font-size:1.0em" target="_blank" href="" style=""> </a></div>
    </ul>
</div>

@yield('content')

<div style="background-color: aliceblue; bottom: 0px;position: fixed;width: 100%;text-align: center;">
    本站不产生资源，比赛视频版权归片源所有。
    <a href="http://quanqiutiyu.cc/">boss直播</a>
    <a href="http://www.qiutantiyu.cc/">球探体育</a>
</div>
</body>
<script type="text/javascript" src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
@yield('js')
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    });
    var _hmt = _hmt || [];
    (function() {
        console.log('here');
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?7114d232ad751b1873858f718fc411fb";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</html>