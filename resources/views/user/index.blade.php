@extends('layouts.main')
@section('title','职员管理')
@section('head')
<script>
	$(function() {
		addAjaxListenerForModal("#submitButton", "#userModal");
	});
	
	function showUserResetPass(url,id) {
		resetForm("#userModal form", false);
		$("#userModal form").attr('action',url);
		$("#userModal form [name=id]").val(id);
		
		$("#userModalTitle").text('重置密码');
		$('#edit_div').addClass('hidden');
		$('#pass_div').removeClass('hidden')
		$("#userModal").modal();
	}

	function showUserEdit(url,id) {
		loading();
		var response = getByUrl('{{ url('staff/edit') }}?id=' + id);
		loaded();
		if (response && response.status == 'OK') {
			resetForm("#userModal form", true)
			
			var user = response.data;
			$("#userModal form").attr('action',url);
			$("#userModal form [name=id]").val(id);
			
			$("#department").val(user.department);
			$("#title").val(user.title);
			$("#name").val(user.name);
			$("#email").val(user.email);
			
			$("#userModalTitle").text('编辑职员信息');
			$('#pass_div').addClass('hidden');
			$('#edit_div').removeClass('hidden')
			$("#userModal").modal();
		} else {
			showMsg('NG', '获取信息失败。');
		}
	}
	
	function showUserAdd(url) {
		resetForm("#userModal form", true)
		
		$("#userModal form").attr('action',url);
		$("#userModalTitle").text('新增职员');
		$('#pass_div').removeClass('hidden');
		$('#edit_div').removeClass('hidden')
		$("#userModal").modal();
	}
</script>
@endsection
@section('content')
	<div class="alert alert-info">
		<strong>提示：</strong>共有{{ count($users) }}名职员。
	</div>
	<div class="form-group">
		<button class="btn btn-success" onclick="showUserAdd('{{ url('staff/add') }}');"><i class="fa fa-user-plus fa-fw"></i>添加职员</button>
	</div>
	<table class="table table-responsive table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>姓名</th>
				<td>邮箱</td>
				<th>部门</th>
				<th>头衔</th>
				<th class="text-center">类型</th>
				<th class="text-center">操作</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)
			<tr>
				<td>{{$user->id}}</td>
				<td>{{$user->name}}</td>
				<td>{{$user->email}}</td>
				<td>{{$user->department}}</td>
				<td>{{$user->title}}</td>
				<td class="text-center">{{$user->type}}</td>
				<td class="text-center">
					@if ($user->type != 'ADMIN' || $user->id == Auth::user()->id)
					<button type="button"  class="btn btn-info" onclick="showUserResetPass('{{ url('staff/resetPassword') }}',{{ $user->id }});">
						<i class="fa fa-key fa-fw"></i>
						改密
					</button>
					<button type="button"  class="btn btn-success" onclick="showUserEdit('{{ url('staff/update') }}',{{ $user->id }});">
						<i class="fa fa-edit fa-fw"></i>
						编辑
					</button>
					@endif
					@if ($user->type != 'ADMIN')
					<button type="button"  class="btn btn-danger" onclick="confirmDel('{{ url('staff/delete') }}/{{ $user->id }}','');">
						<i class="fa fa-trash fa-fw"></i>
						删除
					</button>
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	<!-- 模态框（Modal）start -->
	<div class="modal fade in" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="">
		<div class="modal-dialog">
			<div class="modal-content panel panel-primary">
				<div class="modal-header panel-heading">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title text-center" id="myModalLabel">
						<div><i class="fa fa-user fa-3x fa-fw"></i></div>
						<span id="userModalTitle">重置密码</span>
					</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" role="form" method='post' data-fresh="true">
						{!! csrf_field() !!}
						<input type="hidden" name="id" value="" />
						<div class="error_msg col-sm-7 col-sm-offset-3">
						</div>
						<div class="clearfix"></div>
						<div id="edit_div">
							<div class="form-group">
								<label class="col-sm-3 control-label">部门</label>
								<div class="col-sm-7">
									<input type="text" name="department" id="department" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">头衔</label>
								<div class="col-sm-7">
									<input type="text" name="title" id="title" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">姓名*</label>
								<div class="col-sm-7">
									<input type="text" name="name" id="name" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">邮箱*</label>
								<div class="col-sm-7">
									<input type="text" name="email" id="email" class="form-control">
								</div>
							</div>
						</div>
						<div id="pass_div">
							<div class="form-group">
								<label class="col-sm-3 control-label">密码*</label>
								<div class="col-sm-7">
									<input type="password" name="password" id="password" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">重复密码*</label>
								<div class="col-sm-7">
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
								</div>
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