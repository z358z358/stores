<div class="form-group">
    {!! Form::label('name', '店名:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('slug', '縮寫:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('slug', null, ['class' => 'form-control', 'placeholder' => '這欄決定您的商店網址']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('info', '簡介:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('info', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('tag_list', '標籤:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('tag_list[]', $tags, null, ['class' => 'form-control select2', 'multiple']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('address', '地址:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('address', null, ['class' => 'form-control controls', 'id' => 'pac-input', 'placeholder' =>'輸入地址']) !!}
    </div>
</div>

<div class="form-group">
    <div id="map-canvas" class="col-md-8" style="height: 500px"></div>
</div>

<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        {!! Form::submit($formSubmitText, ['class' => 'btn btn-primary form-control']) !!}
    </div>
</div>

@section('footer')
    @include('partials.select2')
    <script src="{{ url( elixir('js/google-map_my.js') ) }}" type="text/javascript"></script>
@endsection
