@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::model($store, ['route' => ['store.update', $store->id], 'method' => 'put', 'class' => 'form-horizontal']) !!}

            	<div class="panel panel-default">
				    <div class="panel-heading">修改商店資料</div>
				    <div class="panel-body">
				    		<p class="text-center">您的商店網址:<a href="{{ url('/' . $store->slug) }}">{{ url('/' . $store->slug) }}</a></p>
						    @include('home.store.form', ['formSubmitText' => '修改'])
                	</div>
                	<div class="form-group">
			            <div class="col-md-6 col-md-offset-4">
			                @if ($store->updated_at != $store->created_at)
			                <p><small class="text-muted">最近修改時間:{{ $store->updated_at }}</small></p>
			                @endif
			                <p><small class="text-muted">建立時間:{{ $store->created_at }}</small></p>
			            </div>
			        </div>
				</div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
