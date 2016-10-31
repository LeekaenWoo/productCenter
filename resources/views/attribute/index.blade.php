@extends('layouts.main')
@section('title','属性管理')
@section('head')
<script>
	$(function() {
		addAjaxListenerForModal("#submitButton", "#attributeModal");
	});
	
	function showWebsiteEdit(url,id) {
		loading();
		var response = getByUrl('{{ url('attribute/group/edit') }}?id=' + id);
		loaded();
		if (response && response.status == 'OK') {
			$("#attributeModal form").resetForm();
			
			var user = response.data;
			$("#attributeModal form").attr('action',url);
			$("#attributeModal form [name=id]").val(id);
			$("#domain").val(user.domain);
			$("#IP").val(user.IP);
			$("#name").val(user.name);
			
			$("#attributeModalTitle").text('编辑属性组');
			$("#attributeModal").modal();
		} else {
			showMsg('NG', '获取信息失败。');
		}
	}
	function showWebsiteAdd(url) {
		$("#attributeModal form").resetForm();
		
		$("#attributeModal form").attr('action',url);
		$("#attributeModalTitle").text('新增属性组');
		$("#attributeModal").modal();
	}
</script>
@endsection
@section('content')
	<div class="">
		<form action="{{ url('attribute') }}" method="post">
			{!! csrf_field() !!}
			<select name="attributeGroupId" class="form-control width_initial" style="display: inline-block;">
				<option value="">所有</option>
				@foreach ($attributeGroups as $attributeGroup)
				<option value="{{ $attributeGroup->id }}"{{ $attributeGroupId == $attributeGroup->id ? ' selected' : '' }}>{{ $attributeGroup->name }}</option>
				@endforeach
			<select>
			<input type="text" name="code" class="form-control width_initial" value="{{ $code }}" placeholder="code" />
			<button type="submit" class="btn btn-info btn_wide"><i class="fa fa-search fa-fw"></i>&nbsp;查找</button>
		</form>	
	</div>
	<div class="form-group">
		<button class="btn btn-success" onclick="location.href='{{ url('attribute/create') }}';">
			<i class="fa fa-plus fa-fw"></i>新增属性
		</button>
	</div>
	<table class="table table-responsive table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>code</th>
				<th>属性名</th>
				<td>类型</td>
				<th class="text-center">操作</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($attributes as $attribute)
			<tr>
				<td>{{$attribute->id}}</td>
				<td>{{$attribute->code}}</td>
				<td>{{$attribute->label}}</td>
				<td>{{$attribute->type}}</td>
				<td class="text-center">
					<button class="btn btn-success" onclick="location.href='{{ url('attribute/edit') }}/{{ $attribute->id }}';">
						<i class="fa fa-edit fa-fw"></i>
						编辑
					</button>
					<button class="btn btn-danger" onclick="confirmDel('{{ url('attribute/delete') }}/{{ $attribute->id }}','您确认删除此属性吗（属性删除属敏感操作，请慎重）？');">
						<i class="fa fa-trash fa-fw"></i>
						删除
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<div class="text-center">
		{!! $attributes->appends(['attributeGroupId' => $attributeGroupId, 'code' => $code])->render() !!}
	</div>
@endsection