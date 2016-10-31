@include('layouts.header')
    <body style="background: url({{ asset('images/main_bg.jpg') }}) no-repeat center 50px #181818;">
		<div class="fixed_center_parent">

			<div class="panel panel-danger dispatch-info fixed_center_child">
				<div class="panel-heading text-center">
					<h2>{{ $title or '操作失败' }}</h2>
				</div>
				<div class="panel-body">
					<div class="dispatch-info-content">
						{!! $content or '非法的操作请求' !!}
					</div>
					<div class="text-center">
						<button class="btn btn-default m_lr_10" onclick="history.back();">返回</button>
						<button class="btn btn-success" onclick="location.href='/'">首页</button>
					</div>
				</div>
			</div>
		
		</div>
    </body>
</html>