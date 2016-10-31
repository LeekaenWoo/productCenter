@extends('layouts.main')

@section('title', '标题测试')
@section ('head','')
@section('content')

<style>
#f-panel {
	max-width: 600px;
	margin: 40px auto;
}
#f-panel .col-title {
	width: 35%;
}
#f-panel .input-group-addon {
	min-width: 100px;
}
</style>
<div class="panel panel-success" id="f-panel">
	<div class="panel-heading text-center">
		<h4>Laravel 用户登录</h4>
	</div>
	<div class="panel-body">
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
		<form method="POST" action="/password/reset" role="form">
			{!! csrf_field() !!}

			<div class="form-group input-group">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-envelope"></span>
				</span>
				<input type="text" name="email" class="form-control" value="{{ old('email') }}" />
			</div>
			<div class="form-group input-group">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-lock"></span>
				</span>
				<input type="password" name="password" class="form-control" value="{{ old('password') }}" />
			</div>
			<div class="form-group input-group">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-lock"></span>
				</span>
				<input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" />
			</div>
			
			<div class="form-group text-center">
				<button type="submit" class="btn btn-primary m_lr_10">重置密码</button>	
			</div>
		</form>
	</div>
</div>

@endsection