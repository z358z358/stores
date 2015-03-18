@extends('app')

@section('title')
會員中心
@stop

@section('content')
<div class="container-fluid">
    @include('home.user.nav')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::model($user, ['route' => ['settings.update', $tab], 'method' => 'put', 'class' => 'form-horizontal']) !!}
                @include('home.user.' . $tab)
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
