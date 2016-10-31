<!DOCTYYPE html>
<html lang="zh">
    <head>
		<meta charset="UTF-8">
        <title>产品管理系统-@yield('title')</title>
		<link rel="shortcut icon" href="http://php.net/favicon.ico">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="author" content="@summerblue">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
		<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datetimepicker.min.css') }}">
		<link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
		<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="{{ asset('css/jquery-ui-1.10.0.custom.css') }}">
		<link rel="stylesheet" href="{{ asset('css/jquery.gritter.css') }}">
		<link rel="stylesheet" href="{{ asset('css/common.css') }}">
		<link rel="stylesheet" href="{{ asset('css/style.css') }}">
		<script src="{{ asset('js/jquery2.0.min.js') }}"></script>
		<script src="{{ asset('js/jquery.form.js') }}"></script>
		<script src="{{ asset('js/jquery.gritter.min.js') }}"></script>
		<script src="{{ asset('js/jquery-ui-1.10.0.custom.min.js') }}"></script>
		<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
		<script src="{{ asset('js/base.js') }}"></script>
		<script src="{{ asset('js/common.js') }}"></script>
		 @yield('head')
    </head>