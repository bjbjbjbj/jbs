@extends('pc.layout.base')
@section('content')
    <div class="content" style="">
        <div id="Right" style="width: 100%;float: left">
            <div class="article">
                <p class="title">资讯列表</p>
                @foreach($articles as $item)
                    <a target="_blank" href="{{$item->url_path}}">
                        {{$item['title']}}
                        <span style="float: right;">{{date_format($item['created_at'],'Y-m-d')}}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="/css/pc/article.css">
@endsection
@section('js')
@endsection