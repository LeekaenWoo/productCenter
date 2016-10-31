@extends('layouts.main')
@section('title','品类管理')
@section('head')
<script>
	$(function() {
		addAjaxListenerForModal("#submitButton", "#attributeGroupModal");
	});
	
	function showWebsiteEdit(url,id) {
		loading();
		var response = getByUrl('{{ url('attribute/group/edit') }}?id=' + id);
		loaded();
		if (response && response.status == 'OK') {
			$("#attributeGroupModal form").resetForm();
			
			var user = response.data;
			$("#attributeGroupModal form").attr('action',url);
			$("#attributeGroupModal form [name=id]").val(id);
			$("#domain").val(user.domain);
			$("#IP").val(user.IP);
			$("#name").val(user.name);
			
			$("#attributeGroupModalTitle").text('编辑属性组');
			$("#attributeGroupModal").modal();
		} else {
			showMsg('NG', '获取信息失败。');
		}
	}
	function showWebsiteAdd(url) {
		$("#attributeGroupModal form").resetForm();
		
		$("#attributeGroupModal form").attr('action',url);
		$("#attributeGroupModalTitle").text('新增属性组');
		$("#attributeGroupModal").modal();
	}
</script>
@endsection
@section('content')
	<div class="alert alert-info">
		<strong>提示：</strong>共有{{ count($attributeGroups) }}个属性组。
	</div>
	<div class="form-group">
		<button class="btn btn-success" onclick="showWebsiteAdd('{{ url('attribute/group/add') }}');">
			<i class="fa fa-plus fa-fw"></i>新增属性组
		</button>
	</div>
	<table class="table table-responsive table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>属性组名</th>
				<td>更新时间</td>
				<th class="text-center">操作</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($attributeGroups as $attributeGroup)
			<tr>
				<td>{{$attributeGroup->id}}</td>
				<td>{{$attributeGroup->name}}</td>
				<td>{{$attributeGroup->updated_at}}</td>
				<td class="text-center">
					<button class="btn btn-info" onclick="location.href='{{ url('attribute/group/show') }}/{{ $attributeGroup->id }}';">
						<i class="fa fa-edit fa-fw"></i>
						查看
					</button>
					<button class="btn btn-success" onclick="showWebsiteEdit('{{ url('attribute/group/update') }}',{{ $attributeGroup->id }});">
						<i class="fa fa-edit fa-fw"></i>
						编辑
					</button>
					<button class="btn btn-danger" onclick="confirmDel('{{ url('attribute/group/delete') }}/{{ $attributeGroup->id }}','');">
						<i class="fa fa-trash fa-fw"></i>
						删除
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	<!-- 模态框（Modal）start -->
	<div class="modal fade in" id="attributeGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="">
		<div class="modal-dialog">
			<div class="modal-content panel panel-primary">
				<div class="modal-header panel-heading">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title text-center" id="myModalLabel">
						<div><i class="fa fa-sitemap fa-3x fa-fw"></i></div>
						<span id="attributeGroupModalTitle">编辑属性组信息</span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="error_msg">
					</div>
					<form class="form-horizontal" role="form" method='post' data-fresh="true">
						{!! csrf_field() !!}
						<input type="hidden" name="id" value="" />
						<div class="form-group">
							<label class="col-sm-3 control-label">属性组名*</label>
							<div class="col-sm-7">
								<input type="text" name="name" id="name" class="form-control">
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