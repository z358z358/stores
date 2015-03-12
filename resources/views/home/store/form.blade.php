        <div class="form-group">
            {!! Form::label('name', '店名:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('slug', '縮寫:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
            {!! Form::text('slug', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('info', '簡介:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
            {!! Form::textarea('info', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {!! Form::submit($formSubmitText, ['class' => 'btn btn-primary form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {{--@if ($user->updated_at != $user->created_at)
                <p><small class="text-muted">最近修改時間:{{ $user->updated_at }}</small></p>
                @endif
                <p><small class="text-muted">帳號建立時間:{{ $user->created_at }}</small></p>--}}
            </div>
        </div>
