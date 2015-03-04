@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">重設密碼</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					{!! Form::open(['url' => 'password/reset', 'class' => 'form-horizontal']) !!}
						{!! Form::hidden('token', $token) !!}

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
							{!! Form::label('password_confirmation', '確認密碼:', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-7 col-md-offset-3">
								{!! Form::submit('重設密碼', ['class' => 'btn btn-primary form-control']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
