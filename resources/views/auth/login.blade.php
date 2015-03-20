@extends('app')

@section('title')
會員登入
@stop

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">會員登入</div>
				<div class="panel-body">

					{!! Form::open(['url' => 'auth/login', 'class' => 'form-horizontal']) !!}
						<div class="form-group">
							{!! Form::label('email', 'E-Mail:', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::email('email', null, ['class' => 'form-control']) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('password', '密碼:', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::password('password', ['class' => 'form-control']) !!}
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> 記住我
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-7 col-md-offset-3">
								{!! Form::submit('登入', ['class' => 'btn btn-primary form-control']) !!}
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<a href="{{ url('/password/email') }}">忘記密碼?</a>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<a class="social-login-btn social-facebook" href="{{ url('/auth/login-fb') }}" title="facebook登入">
									<i class="fa fa-facebook-official fa-5x"></i>
								</a>
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
