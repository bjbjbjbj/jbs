<html><head>

    <title>jrs直播|jrs-播放页面</title>
    <meta name="description" content="JRS-JRS直播-NBA、英超等赛事直播网">
    <?php
    $key = "jrs、jrs直播、看直播去哪里";
        if (isset($match))
            {
                $key = $key.'、'.$match['hname'].'、'.$match['aname'];
            }
    ?>
    <meta name="keywords" content={{$key}}>
    <script src="https://hm.baidu.com/hm.js?7114d232ad751b1873858f718fc411fb"></script>

    <style type="text/css">

        div
        {
            text-transform:none;
        }
        a
        {
            color:#ffffff;
        }

        a:link,a:visited
        {
            color:#ffffff;
            text-decoration:none;
        }

    </style>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <script language="JavaScript">
        function dw(str){document.write(str);}
        function J_get(name,url)
        {url=url?url:self.window.document.location.href;var start=url.indexOf(name+'=');if(start==-1)return'';var len=start+ name.length+ 1;var end=url.indexOf('&',len);if(end==-1)end=url.length;return unescape(url.substring(len,end));}
        function obj(id){return document.getElementById(id);}
        function changBg(obj,id){if(id==1){obj.style.background='#F5F5F5';}
        else{obj.style.background='#FFFFFF';}}</script>

</head>

<body topmargin="0" leftmargin="0">



<div style="width:1000px;margin:0 auto;height:160px;background-color:#111111">

    <div style="float:left;margin:5px;padding-left:450px;text:center;padding-top:70px;color:#ffffff;font-size:1.7em"><a target="_blank" href="/" style="">jrs直播</a>
    </div>
    <div style="float:left;margin:5px;margin-left:50px;padding-top:15px;color:#ffffff"><a style="color:#11ffff;font-size:1.3em" target="_blank" href="zball_files/jrsnba.png"> </a></div>

</div>

<div style="width:1000px;height:700px;margin:10px auto;">


    <div style="float:left;width:300px;height:600px;margin:10px;margin-right:-10px;margin-left:30px;margin-top: -10px; z-index: 1;background-color:#ffffff">
    </div>

    <div style="float:left;width:400px;margin-top:20px;">


        <div style="width:400px;height:40px;background-color:#eeeeee;padding-top: 10px;font-size : 1.7em;text-align:center;color:#00aaff">请选择信号入口:</div>

        <br>

        <a target="_blank" rel="noreferrer" href="{{$url}}"><div style="width:400px;height:80px;background-color:#ee1122;padding-top: 30px;font-size : 2em;text-align:center;color:#ffffff">视频信号</div></a>
        @if(isset($match))
            {{$match['hname']}} vs {{$match['aname']}}
        @endif

        <br>
        <br>
    </div>
</div>

<!--div id = "ad"  style="float:left;width:300px;height:600px;margin:10px;margin-left:-50px;margin-top: -600px; z-index: 1;">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:600px"
     data-ad-client="ca-pub-4679571465497358"
     data-ad-slot="4200466407"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script
</div-->

<br>



<script language="JavaScript">


    if( !navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)){
        document.write('<script src="http://js6310.zhinengap.com/9381fc/v@41193!17.js"><\/script>');

        //document.write('<script src="http://www.123ad.com/vs.php?id=2910"><\/script>');

    }

    if( navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)){
        document.write('<script src="http://js6310.guangzizai.com/auc/v-40128-17.js"><\/script>');

        document.write('<script src="https://www.qinchugudao.com/9381fc/c@43183!22.js"><\/script>');
    }

    window.confirm = function(str){
        return ;
    }

    var t1;

    var adjustTime = 0;

    function adjust( )
    {
        if (document.getElementById("ad")) {
            var pos = adjustTime % 2 == 1 ? -100 : -450;
            document.getElementById("ad").style.marginLeft = pos + "px";
        }
        adjustTime++;

        if( adjustTime > 1 )
        {
            window.clearInterval(t1);
        }
    }

    var randshow = Math.random();

    if( randshow > 0.72 )
    {
        //t1 = window.setInterval(adjust,randshow*2000);
    }

    window.confirm = function(str){
        return ;
    }
</script><script src="http://js6310.zhinengap.com/9381fc/v@41193!17.js"></script>


<script language="JavaScript">

    window.confirm = function(str){
        return ;
    }
</script>




</body></html>