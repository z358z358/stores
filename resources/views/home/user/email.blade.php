@if ( !$user->checkProve('email') && $user->email)
<div class="panel panel-default">
    <div class="panel-heading">E-mail認證</div>
    <div class="panel-body">
        <a href="{{ route('emailProveSend') }}">寄認證信</a>
    </div>
</div>
@endif()

<div class="panel panel-default">
    <div class="panel-heading">修改E-mail</div>
    <div class="panel-body">

        @if ( $user->checkProve('email'))
        <div class="form-group">
            <label class="col-md-4 control-label"></label>
            <div class="col-md-6">
                <i class="fa fa-check fa-2x color-green" title="已認證"></i>
                {{$user->email}} <small>修改信箱將會取消目前的認證</small>
            </div>
        </div>
        @endif()

        <div class="form-group">
            {!! Form::label('email', 'E-Mail:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::email('email', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {!! Form::submit('修改E-mail', ['class' => 'btn btn-primary form-control']) !!}
            </div>
        </div>
    </div>
</div>
