


<html><head lang="zh-CN">

    <meta http-equiv="Content-Type" content="text/html;">
    <meta charset="UTF-8">
    <title>jrs直播|jrs - 一个免费观看篮球、足球比赛的网站</title>

    　<meta http-equiv="Cache-Control" content="no-transform" />
    　　<meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="description" content="jrs直播-这是一个在线免费观看篮球和足球比赛的直播网站、酷玩直播、英超直播、意甲西甲亚冠直播、NBA直播、斯诺克等比赛都可以流畅观看，还可以了解球员信息、比赛赛程、明日比赛预告等等-JRS">
    <meta name="keywords" content="jrs、jrs直播">

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

</head>

<body class="watchgame game-list" data-pageid="0" style="overflow:auto;">
<div id="contet"></div>
</body>
<script>
    function GetQueryString(str,href) {
        var Href;
        if (href != undefined && href != '') {
            Href = href;
        }else{
            Href = location.href;
        };
        var rs = new RegExp("([\?&])(" + str + ")=([^&#]*)(&|$|#)", "gi").exec(Href);
        if (rs) {
            return decodeURI(rs[3]);
        } else {
            return '';
        }
    }

    window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
    ]); ?>
</script>
{{--线上看看怎么配--}}
{{--<script src="//{{ \Illuminate\Support\Facades\Request::getHost() }}:6001/socket.io/socket.io.js"></script>--}}
<script src="https://cdn.bootcss.com/socket.io/2.1.1/socket.io.js"></script>
<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript">
    Echo.channel('notice-' + GetQueryString('user',window.location))
            .listen('PushNotification', function (e) {
                console.log(e);
                $('#contet').append('<br>'+e.user + ':' + e.message);
            });
</script>
</html>