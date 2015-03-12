@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::model($store = new App\Store, ['route' => 'store.store', 'method' => 'post', 'class' => 'form-horizontal']) !!}

				<div class="panel panel-default">
				    <div class="panel-heading">建立商店</div>
				    <div class="panel-body">
                		@include('home.store.form', ['formSubmitText' => '開店!'])
                	</div>
				</div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
