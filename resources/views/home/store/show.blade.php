@extends('app')

@section('title')
{{ $store->name }}
@stop

@section('description')
{{ $store->info_desc }}
@stop

@section('content')
<div id="wrapper">
    <div id="page-wrapper">

        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    {{ $store->name }}
                    <span class="fb"><div class="fb-like" data-href="{{ route('store.showById', $store->id) }}" data-layout="button_count" data-action="like" data-show-faces="true"></div></span>
                    <span class="g-plus"><div class="g-plusone" data-size="medium"></div></span>
                    <span class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-url="{{ route('store.showById', $store->id) }}"></a></span>
                </h1>
            </div>
        </div>
        <!-- /row -->

        <!-- store.nav -->
        @include('home.store.nav')
        <!-- /store.nav -->

        <!-- row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">商店簡介</div>
                    <div class="panel-body">
                        <p>{!! $store->info_html !!}</p>
                        @if ($store->address)
                            <p>地址:{{ $store->address }}
                            @if ($store->lat && $store->lng)
                                <a href="http://maps.google.com/maps?ll={{$store->lat}},{{$store->lng}}&&q=loc:{{$store->lat}},{{$store->lng}}"><i title="前往Google Map" class="fa fa-map-marker"></i></a>
                                </p>
                            @endif
                            @if ($store->updated_at != $store->created_at)
                            <p><small class="text-muted">更新時間:{{ $store->updated_at }}</small></p>
                            @else
                            <p><small class="text-muted">建立時間:{{ $store->created_at }}</small></p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            @if ($store->tags)
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">商店標籤</div>
                    <div class="panel-body">
                        @foreach ($store->tags as $tag)
                            <a href="{{ route('tag.show', [$tag->slug, $tag->name]) }}">#{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
        <!-- /row -->

         <!-- row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                <div class="panel-heading">分享</div>
                    <div class="panel-body">
                        <div class="text-center">
                            <a href="{{ $share['facebook'] }}" class="btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
                            <a href="{{ $share['gplus'] }}" class="btn btn-social-icon btn-google-plus"><i class="fa fa-google-plus"></i></a>
                            <a href="{{ $share['twitter'] }}" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row -->

        <!-- row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                <div class="panel-heading">Facebook留言</div>
                    <div class="panel-body">
                        <div class="fb-comments" data-href="{{ route('store.showById', $store->id) }}" data-numposts="5" data-colorscheme="light" data-width="100%"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="panel panel-default">
                <div class="panel-heading">Disqus留言</div>
                    <div class="panel-body">
                        <div id="disqus_thread"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row -->


    </div>
    <!-- /#page-wrapper -->
</div>

@endsection

@section('footer')
    @include('partials.sb-admin-2')

    <script src="https://apis.google.com/js/platform.js" async defer>{lang: 'zh-TW'}</script>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
    </script>

    <script type="text/javascript">
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 'onininon';
    var disqus_identifier = '{{ $store->id }}';

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
    </script>
@endsection