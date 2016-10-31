@include('layouts.header')
    <body style="background: url({{ asset('images/main_bg.jpg') }}) no-repeat center 50px #181818;">
		<div class="fixed_center_parent">
			<div class="reg_login_div fixed_center_child">
				<form class="flow_auto" role="form" name="login" action="{{ url('auth/login') }}" method="post">
					{!! csrf_field() !!}
					<h2 class="text-center">钜合产品系统</h2>
					<br>
					<div class="input-group form-group">
						<span class="input-group-addon white_back">
							<i class="glyphicon glyphicon-envelope"></i>
						</span>
						<input type="text" name="email" class="form-control" placeholder="邮箱">
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon white_back">
							<i class="glyphicon glyphicon-lock"></i>
						</span>
						<input type="password" name="password" class="form-control" placeholder="密码">
					</div>
					<div class="checkbox center-block" style="padding-left: 20px;">
							<input type="checkbox" name="autologin" value="true" style='top: 4px'>记住帐号&nbsp;
							<a href="getback_password.php" class="btn btn-success btn-sm pull-right" style="margin-top:-3px;">找回密码</a>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary form-control" style="height: 46px;">登录</button>
					</div>
				</form>
			</div>
		</div>
    </body>
</html>