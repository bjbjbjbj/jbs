<?php if (!isset($match)) return ""; ?>
<?php
    $channels = $match['channels'];
    $sport = $match['sport'];
    $mid = $match['mid'];
    $url = asset('/live/spPlayer/player-' . $mid . '-' . $sport . '.html');
    $url =  str_replace('xijiazhibo.cc','mp.dlfyb.com',$url);
    $firstChannel = isset($channels[0]) ? $channels[0] : [];
    $impt = isset($firstChannel['impt']) ? $firstChannel['impt'] : 1;
    $impt_style = '';
    if ($impt == 2) {
        if ($sport == 1) {
            $impt_style = 'class=good_ft';
        } else if ($sport == 2) {
            $impt_style = 'class=good_bk';
        }
    }
?>
<div class='racediv'><a target='_blank' ref='nofollow' href='/match?id={{$mid}}&url={{$url}}'>
        <ul class='race'>
            <li class='racetime'>{{date('m.d', strtotime($match['time']))}} {{date('H:i', strtotime($match['time']))}}</li><li class='racecat'>{{(isset($match['league_name']) && strlen($match['league_name']) > 0) ? $match['league_name'] : '其他'}}</li>
            <li  class='racename'>{{$match['hname']}} - {{$match['aname']}}</li><li  class='playbtn'>直播</li></ul></a>
</div>