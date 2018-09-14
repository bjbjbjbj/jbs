@extends('pc.layout.base')
@section('content')
    <div class="content" style="margin-bottom:20px">
        <div id="Right">
            <div class="article">
                <p class="title">资讯列表</p>
                @foreach($articles as $item)
                    <a target="_blank" href="{{$item->url_path}}">{{$item['title']}}</a>
                @endforeach
            </div>
        </div>
        <div id="Left" style="">
            <div class="con">
                <h1>{{$article['title']}}</h1>
                <p class="info">来源：{{$article['from']?:'网络'}}<span>发表于：{{date_format($article['created_at'],'Y-m-d H:i')}}</span></p>
                <div class="detail">
                    {!! $article->content()!!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="/css/pc/article.css">
@endsection
@section('js')
@endsection