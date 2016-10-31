@extends('layouts.main')
@section('title','网站管理')
@section('head')
<script>
	$(function() {
		addAjaxListenerForModal("#submitButton", "#websiteModal");
	});
	
	function showWebsiteEdit(url,id) {
		loading();
		var response = getByUrl('{{ url('website/edit') }}?id=' + id);
		loaded();
		if (response && response.status == 'OK') {
			resetForm("#websiteModal form", true);
			
			var user = response.data;
			$("#websiteModal form").attr('action',url);
			$("#websiteModal form [name=id]").val(id);
			$("#domain").val(user.domain);
			$("#IP").val(user.IP);
			$("#name").val(user.name);
			
			$("#websiteModalTitle").text('编辑网站信息');
			$("#websiteModal").modal();
		} else {
			showMsg('NG', '获取信息失败。');
		}
	}
	function showWebsiteAdd(url) {
		resetForm("#websiteModal form", true);
		
		$("#websiteModal form").attr('action',url);
		$("#websiteModalTitle").text('新增网站');
		$("#websiteModal").modal();
	}
</script>
@endsection
@section('content')
	<div class="alert alert-info">
		<strong>提示：</strong>共有{{ count($websites) }}个网站。
	</div>
	<div class="form-group">
		<button class="btn btn-success" onclick="showWebsiteAdd('{{ url('website/add') }}');"><i class="fa fa-user-plus fa-fw"></i>新增网站</button>
	</div>
	<table class="table table-responsive table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>域名</th>
				<td>IP</td>
				<th>目录名</th>
				<th class="text-center">操作</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($websites as $website)
			<tr>
				<td>{{$website->id}}</td>
				<td>{{$website->domain}}</td>
				<td>{{$website->IP}}</td>
				<td>{{$website->name}}</td>
				<td class="text-center">
					<button type="button"  class="btn btn-success" onclick="showWebsiteEdit('{{ url('website/update') }}',{{ $website->id }});">
						<i class="fa fa-edit fa-fw"></i>
						编辑
					</button>
					<button type="button"  class="btn btn-danger" onclick="confirmDel('{{ url('website/delete') }}/{{ $website->id }}','');">
						<i class="fa fa-trash fa-fw"></i>
						删除
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<div class="text-center">
		{!! $websites->render() !!}
	</div>
	
	<!-- 模态框（Modal）start -->
	<div class="modal fade in" id="websiteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="">
		<div class="modal-dialog">
			<div class="modal-content panel panel-primary">
				<div class="modal-header panel-heading">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title text-center" id="myModalLabel">
						<div><i class="fa fa-sitemap fa-3x fa-fw"></i></div>
						<span id="websiteModalTitle">编辑网站信息</span>
					</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" role="form" method='post' data-fresh="true">
						{!! csrf_field() !!}
						<input type="hidden" name="id" value="" />
						<div class="error_msg col-sm-7 col-sm-offset-3">
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">域名*</label>
							<div class="col-sm-7">
								<input type="text" name="domain" id="domain" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">IP*</label>
							<div class="col-sm-7">
								<input type="text" name="IP" id="IP" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">目录*</label>
							<div class="col-sm-7">
								<input type="text" name="name" id="name" class="form-control" placeholder="(英数字-_)">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer text-center">
					<button type="button" class="btn btn-primary" id="submitButton">
						确认
					</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">
						取消
					</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>
	<!-- 模态框（Modal）end -->
@endsection