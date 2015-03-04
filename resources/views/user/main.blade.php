<div class="panel panel-default">
    <div class="panel-heading">修改基本資料</div>
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('email', 'E-Mail:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('email', $user->email, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('name', '使用者名稱:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
            {!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {!! Form::submit('修改資料', ['class' => 'btn btn-primary form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                @if ($user->updated_at != $user->created_at)
                <p><small class="text-muted">最近修改時間:{{ $user->updated_at }}</small></p>
                @endif
                <p><small class="text-muted">帳號建立時間:{{ $user->created_at }}</small></p>
            </div>
        </div>
    </div>
</div>
