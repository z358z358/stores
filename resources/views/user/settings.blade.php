@extends('app')

@section('content')
<div class="container-fluid">

    @include('flash::message')
    @include('partials.errors')
    @include('user.nav')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::open(['route' => ['settings.update', $tab], 'method' => 'put', 'class' => 'form-horizontal']) !!}
                @include('user.' . $tab)
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
