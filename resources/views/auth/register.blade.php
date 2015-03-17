@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">會員註冊</div>
				<div class="panel-body">

					{!! Form::open(['url' => 'auth/register', 'class' => 'form-horizontal']) !!}

						<div class="form-group">
							{!! Form::label('name', '使用者名稱:', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('name', null, ['class' => 'form-control']) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('name', 'E-Mail:', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => '您的登入帳號']) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('password', '密碼:', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::password('password', ['class' => 'form-control']) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('password_confirmation', '確認密碼:', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								{!! Form::submit('註冊', ['class' => 'btn btn-primary form-control']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
