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

		<form method="POST" action="/password/email">
			{!! csrf_field() !!}
			
			<div class="form-group input-group">
				<span class="input-group-addon">邮箱</span>
				<input type="text" name="email" class="form-control" value="{{ old('email') }}" />
			</div>

			<div class="form-group text-center">
				<button type="submit" class="btn btn-primary m_lr_10">
					发送重置密码邮件
				</button>
			</div>
		</form>
	</div>
</div>

@endsection