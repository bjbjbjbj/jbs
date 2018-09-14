@extends('pc.layout.base')
@section('css')
    <link rel="stylesheet" href="/css/pc/article.css">
    @endsection
@section('content')
    <div class="content" style="margin-bottom:20px">
        <!--左侧导航-->
        <div id="Right" style="float: left;width: 25%">
            <div class="article">
                <p class="title">资讯列表</p>
                @foreach($articles as $item)
                    <a target="_blank" href="{{$item->url_path}}">{{$item['title']}}</a>
                @endforeach
            </div>
        </div>

        <div style="float:right;width:75%;">
            <div style="width:970px;margin:0 auto;">
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
            </div>
        </div>
    </div>
    @endsection