<html>
<head lang="en">

    <meta http-equiv="Content-Type" content="text/html;">
    <meta charset="UTF-8">
    <title>神马直播|jrs - 一个免费观看篮球、足球比赛的网站</title>
    <meta name="viewport" content="width=device-width, user-scalable=no,  maximum-scale=1.0 ">
    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?7114d232ad751b1873858f718fc411fb";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head><body style="margin:0 auto;">　<meta http-equiv="Cache-Control" content="no-transform">
　　<meta http-equiv="Cache-Control" content="no-siteapp">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">

<meta http-equiv="refresh" content="1800;url=">
<meta name="description" content="jrs直播-这是一个在线免费观看篮球和足球比赛的直播网站、酷玩直播、英超直播、意甲西甲亚冠直播、NBA直播、斯诺克等比赛都可以流畅观看，还可以了解球员信息、比赛赛程、明日比赛预告等等-JRS">
<meta name="keywords" content="jrs、jrs直播">

<link rel="shortcut icon" href="http://mat1.gtimg.com/sports/kbsweb/favicon.ico" type="image/x-icon">

<style type="text/css">
    .raceblockdiv{ width:520px;margin:0 auto;}
    .raceblock{ float:left;margin-left:0px;margin-top:10px;width:520px;height:60px;border-width:1px;border-style: solid;border-color:#dddddd;background-color:#e3eaff;padding-top: 30px;font-size : 1.5em;text-align:center;color:#000000}

    .race{ list-style-type:none;text-align:left;margin-left:0px;margin-top:-1px;padding:left:1px;width:480px;height:60px;border-width:1px;border-style: solid;border-color:#dddddd;background-color:#ffffff;padding-bottom: 10px;padding-top: 30px;font-size : 1.3em; color:#555555 }
    .raceRed{ list-style-type:none;text-align:left;margin-left:0px;margin-top:-1px;padding:left:1px;width:480px;height:60px;border-width:1px;border-style: solid;border-color:#eecccc;background-color:#ffeeee;padding-bottom: 10px;padding-top: 30px;font-size : 1.3em; color:#555555 }
    .raceRed1{ list-style-type:none;text-align:left;margin-left:0px;margin-top:-1px;padding:left:1px;width:480px;height:140px;border-width:1px;border-style: solid;border-color:#dddddd;background-color:#ffffff;padding-bottom: 10px;padding-top: 30px;font-size : 1.3em; color:#555555 }

    .playbtn{float:left;height:35px;width:60px;top:-10px;padding-top: 2px;text-align:center;color:#ffffff;background-color:#3377ee }
    .racename{float:left;width:180px;top:-3px;font-size:1.2em;color:#222222;}
    .racetime{float:left;margin-left:1px;padding-top:-5px;width:70px;font-weight:bold}
    .racecat{float:left;width:130px;color:#888888;}
    .racediv{float:left;margin-left:0px;padding-left:0px;}
    .races{float:left;margin-left:0px;padding-left:0px;width:480px;height:60px;border-width:1px;border-style: solid;border-color:#dddddd;background-color:#e3eaff;padding-top: 25px;font-size : 1.4em;text-align:center;color:#0033ff}
    .nav{list-style-type:none;height:60px;margin-left:0px;padding-left:0px;font-size:1.5em;float:left;}
    a{text-decoration:none;mairgn-left:0px;}
</style>



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

        }
        else{
            window.location.href= '/index.html';
        }
    }
    browserRedirect();
</script>

<!--[if lt IE 8]>
<script charset="utf-8" type="text/javascript">

</script>
<![endif]-->
<div style="margin:0 auto;width:500px;padding-left:5px;">
    <ul style="width:500px;">
        <li class="nav">
            <a href="" style="margin-left:0px;"> <img style="margin-left:0px;" alt="jrs直播" src="/img/jrstv.png">
            </a>
        </li>
    </ul>
</div>
<br>
<div style="width:520px;margin:0 auto;margin-top:20px;padding-left:0px">
    @foreach($matches as $time=>$match_array)
        <?php
        $week = date('w', strtotime($time));
        ?>
        <div class="raceblockdiv">
            <a name="zq" ref="nofollow" href=""><div class="raceblock">{{date_format(date_create($time),'Y年m月d日')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$week_array[$week]}}</div></a>
        </div>
        @foreach($match_array as $match)
            @component('pc.cell.home_match_cell',['match'=>$match])
            @endcomponent
        @endforeach
    @endforeach

    <div class="raceblock" style="margin:0 auto;background-color:#ffffff;color:#999999;border:none">
        <h3>jrs直播：特点、由来与简介</h3>jrs直播是国内最大的体育社区的jr发起的体育赛事直播网址分享平台，通过收集网络上可以观看比赛的地址，酷玩直播 等，整理、组织好分享给jr们。为什么我们叫jrs，因为我们都是家人，简称jrs。如果你是勇士、骑士、火箭、雷霆、马刺、巴塞罗那、皇马、曼联、切尔西、阿森纳、利物浦等球队以及詹姆斯、库里、威少、哈登、杜兰特、C罗、梅西、保利尼奥、内马尔等球星的球迷，那么你找对地方了。</div>
</div>

</body>


</html>