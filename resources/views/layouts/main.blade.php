@include('layouts.header')
    <body>
		<!--loading-->
		<div class="loading">
			<span class="loading_text" style="display:inline;"><i class="fa fa-refresh fa-fw"></i>数据处理中，请稍后...</span>
		</div>
		<!--loading end-->
		
		<!--header start-->
		<div class="navbar navbar-default navbar-fixed-top" role="navigation" id="header">
			<div class="navbar-header">
			  <a class="navbar-brand hidden-sm" href="{{ url('') }}">产品管理系统</a>
			</div>
			<div class="pull-right m_lr_10">
				<ul class="nav navbar">
					<li class="dropdown">
						<a href="#" id="dropdownMenuUser" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-user fa-fw"></i><i class="fa fa-caret-down"></i>
						</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuUser">
							<li>
								<a href="{{ url('user/profile') }}"><i class="fa fa-user fa-fw"></i>个人资料</a>
							</li>
							<li class="divider"></li>
							<li><a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out fa-fw"></i>退出登录</a></li>
						</ul>
					</li>
				</ul>
			</div>	
		</div>
		<!--header end-->
		<div class="navbar navbar-default navbar-fixed-left" role="navigation" id="sideMenu">
			<ul class="list-group">
				@if (Auth::user()->type == 'ADMIN')
				<li class="list-group-item">
					<a href="{{ url('website') }}"><i class="fa fa-globe fa-fw"></i>网站管理</a>
				</li>
				<li class="list-group-item">
					<a href="{{ url('staff') }}"><i class="fa fa-group fa-fw"></i>职员管理</a>
				</li>
				@endif
				<li class="list-group-item">
					<a href="#collapseProduct" data-toggle="collapse">
						<i class="fa fa-database fa-fw"></i>产品管理
						<i class="pull-right fa fa-angle-down fa-fw"></i>
					</a>
				</li>
				<ul id="collapseProduct" class="collapse in">
					<li>
						<a href="{{ url('product') }}"><i class="fa fa-group fa-fw"></i>产品库</a>
					</li>
					<li>
						<a href="{{ url('product/website') }}"><i class="fa fa-group fa-fw"></i>网站产品</a>
					</li>
				</ul>
				
				<li class="list-group-item">
					<a href="#collapseAttribute" data-toggle="collapse">
						<i class="fa fa-gear fa-fw"></i>属性管理
						<i class="pull-right fa fa-angle-down fa-fw"></i>
					</a>
				</li>
				<ul id="collapseAttribute" class="collapse in">
					<li>
						<a href="{{ url('attribute/group') }}"><i class="fa fa-navicon fa-fw"></i>属性组</a>
					</li>
					<li>
						<a href="{{ url('attribute') }}"><i class="fa fa-group fa-fw"></i>属性</a>
					</li>
					<li>
						<a href="{{ url('attribute/set') }}"><i class="fa fa-database fa-fw"></i>属性集</a>
					</li>
				</ul>	
			</ul>
		</div>

		<div class="page-wrapper">
			<div id="content">
				<div id="cus_error_info"></div>
				@yield('content')
			</div>
		</div>
				
		<div id="footer">
		</div>
    </body>
</html>