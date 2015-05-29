<div class="form-group">
    {!! Form::label('name', '店名:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('slug', '縮寫:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('slug', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => '這欄決定您的商店網址']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('info', '簡介:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('info', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('tag_list', '標籤:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('tag_list[]', $tags, null, ['class' => 'form-control select2', 'multiple']) !!}
    </div>
</div>

<div class="form-group row">
  <div class="col-lg-6 col-md-offset-4">
    <div class="input-group">
      {!! Form::text('address', null, ['class' => 'form-control', 'id' => 'pac-input', 'placeholder' =>'輸入地址']) !!}
      <span class="input-group-btn">
        <button id="refresh-map" class="btn btn-default" type="button">更新地圖</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->

<!--<div class="input-group">
    {!! Form::label('address', '地址:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('address', null, ['class' => 'form-control', 'id' => 'pac-input', 'placeholder' =>'輸入地址']) !!}
        <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
   </span>
    </div>
</div>-->
{!! Form::hidden('lat', null, ['id' => 'lat']) !!}
{!! Form::hidden('lng', null, ['id' => 'lng']) !!}

<div class="form-group">
    <div id="map-canvas" class="col-md-7 col-md-offset-3" style="height: 400px"></div>
</div>

<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        {!! Form::submit($formSubmitText, ['class' => 'btn btn-primary form-control']) !!}
    </div>
</div>

@section('footer')
    @include('partials.select2', ['select2_placeholder_text' => '選擇標籤'])
    <script src="{{ url( elixir('js/google-map_my.js') ) }}" type="text/javascript"></script>
@endsection
