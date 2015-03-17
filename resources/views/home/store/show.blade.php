@extends('app')

@section('content')
<div id="wrapper">
    <div id="page-wrapper">

        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{{ $store->name }}</h1>
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
                    <div class="panel-body">
                        <h3>Facebook留言</h3>
                        <div class="fb-comments" data-href="{{ route('store.showById', $store->id) }}" data-numposts="5" data-colorscheme="light" data-width="100%"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>Disqus留言</h3>
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