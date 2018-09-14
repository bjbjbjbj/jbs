<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="UTF-8">
    <title>爱看球主播后台</title>
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/fonts/glyphicons-halflings-regular.svg" rel="stylesheet">
    @yield("css")
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta http-equiv="X-UA-Compatible" content="edge" />
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    <meta name="baidu-site-verification" content="nEdUlBWvbw">
    <link rel="Shortcut Icon" data-ng-href="/img/pc/ico.ico" href="/img/pc/ico.ico">
</head>
<body>
<div id="Navigation">
    <div class="inner">
        <a href="/bs/logout">退出登录</a>
        <a href="/bs/password/edit">修改密码</a>
    </div>
</div>
    @yield("content")
</body>
<script type="text/javascript" src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
@yield("js")
</html>