@extends('layouts.main')
@section('title','产品类型管理')
@section('head')
<script>
	$(function() {
		addAjaxListenerForModal("#submitButton", "#attributeModal");
	});
</script>
@endsection
@section('content')
	<div class="">
		<form action="{{ url('attribute/set') }}" method="post">
			{!! csrf_field() !!}
			<input type="text" name="name" class="form-control width_initial" value="{{ $name }}" placeholder="name" />
			<button type="submit" class="btn btn-info btn_wide"><i class="fa fa-search fa-fw"></i>&nbsp;查找</button>
		</form>	
	</div>
	<div class="form-group">
		<button class="btn btn-success" onclick="location.href='{{ url('attribute/set/create') }}';">
			<i class="fa fa-plus fa-fw"></i>新增产品类型
		</button>
	</div>
	<table class="table table-responsive table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>产品类型名</th>
				<th>创建时间</th>
				<th class="text-center">操作</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($attributeSets as $attributeSet)
			<tr>
				<td>{{$attributeSet->id}}</td>
				<td>{{$attributeSet->name}}</td>
				<td>{{$attributeSet->created_at}}</td>
				<td class="text-center">
					<button class="btn btn-success" onclick="location.href='{{ url('attribute/set/edit') }}/{{ $attributeSet->id }}';">
						<i class="fa fa-edit fa-fw"></i>
						编辑
					</button>
					<button class="btn btn-danger" onclick="confirmDel('{{ url('attribute/set/delete') }}/{{ $attributeSet->id }}','您确认删除此产品类型？');">
						<i class="fa fa-trash fa-fw"></i>
						删除
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<div class="text-center">
		{!! $attributeSets->appends(['name' => $name])->render() !!}
	</div>
@endsection