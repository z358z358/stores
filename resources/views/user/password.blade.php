@if ($user->from)
    <div class="alert alert-warning">
        此帳號是由{{ $user->from }}登入，無法修改密碼
    </div>
@else

<div class="panel panel-default">
    <div class="panel-heading">修改密碼</div>
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('password', '目前的密碼:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::password('password_old', ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('password', '新密碼:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::password('password', ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('password_confirmation', '確認新密碼:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {!! Form::submit('修改密碼', ['class' => 'btn btn-primary form-control']) !!}
            </div>
        </div>
    </div>
</div>

@endif
