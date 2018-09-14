@extends("backstage.layout.nav")
@section("css")
    <link rel="stylesheet" type="text/css" href="/backstage/css/info.css?2018080131820">
@endsection
@section("content")
    <div id="Content">
        <table>
            @foreach($articles as $article)
                <tr>
                    <td>
                        <a href="{{$article['url']}}" target="_blank">
                            {{$article['title']}}
                        </a>
                    </td>
                    <td>{{$article['date']}}</td>
                    <td>{{$article['created_at']}}</td>
                    <td>{{$article['status']}}</td>
                    <td><button onclick="publish('{{$article['id']}}')">发布</button></td>
                </tr>
                @endforeach
        </table>
        {{ $articles->links() }}
    </div>
@endsection
@section("js")
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });

        function publish(id) {
            var data = {};
            data["id"] = id;
            $.ajax({
                "url": "/bs/article/publish",
                "type": "post",
                "data": data,
                "dataType": "json",
                "success": function (json) {
                    if (json) {
                        alert(json.message);
                        if (json.code == 0) {
//                            location.reload();
                        }
                    } else {
                        alert("保存失败");
                    }
                }
            });
        }
    </script>
@endsection
