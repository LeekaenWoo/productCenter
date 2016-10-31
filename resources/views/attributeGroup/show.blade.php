@extends('layouts.main')
@section('title','品类管理')
@section('head')
<script>
	
</script>
@endsection
@section('content')
	<div class="alert alert-info">
		<strong>提示：</strong>属性组【{{ $attributeGroup->name }}】中共有{{ count($attributes) }}个属性。
	</div>
	<div class="form-group">
		<button class="btn btn-success" onclick="location.href='{{ url('attribute/create') }}?attributeGroupId={{ $attributeGroup->id }}'">
			<i class="fa fa-plus fa-fw"></i>新增属性
		</button>
	</div>
	<table class="table table-responsive table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>CODE</th>
				<td>标签</td>
				<td>类型</td>
				<td>备注</td>
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
				<td>{{$attribute->description}}</td>
				<td class="text-center">
					<button class="btn btn-success" onclick="location.href= '{{ url('attribute/edit') }}/{{ $attribute->id }}';">
						<i class="fa fa-edit fa-fw"></i>
						编辑
					</button>
					<button class="btn btn-danger" onclick="confirmDel('{{ url('attribute/delete') }}/{{ $attribute->id }}','');">
						<i class="fa fa-trash fa-fw"></i>
						删除
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	<!-- 模态框（Modal）start -->
	<div class="modal fade in" id="attributeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="">
		<div class="modal-dialog">
			<div class="modal-content panel panel-primary">
				<div class="modal-header panel-heading">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title text-center" id="myModalLabel">
						<div><i class="fa fa-sitemap fa-3x fa-fw"></i></div>
						<span id="attributeModalTitle">编辑属性信息</span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="error_msg">
					</div>
					<form class="form-horizontal" role="form" method='post' data-fresh="true">
						{!! csrf_field() !!}
						<input type="hidden" name="id" value="" />
						<div class="form-group">
							<label class="col-sm-3 control-label">品类名*</label>
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