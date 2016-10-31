@extends('layouts.main')
@section('title','添加属性')
@section('head')
<script>
	var inputIndex = 1;
	var trIdPre = "attrTr";
	var inputNamePre = "attrLabel";
	var inputValuePre = "attrValue";
	$(function() {
		//addAjaxListenerForModal("#submitButton", "#attributeModal");
		$("#addOptionBtn").bind("click", addOption);
		
		addAjaxListener("#submitButton", "#attributeCreate");
	});
	
	function addOption() {
		var trId = trIdPre + inputIndex;
		var inputName = inputNamePre + inputIndex;
		var inputValue = inputValuePre + inputIndex;
		var trObj = $('<tr id="' + trId + '"><td><input type="text" name="' + inputName + '" class="form-control" /></td>' + 
						'<td><input type="text" name="' + inputValue + '" class="form-control" /></td>' + 
						'<td class="text-center"><button type="button" class="btn btn-danger"><i class="fa fa-trash fa-fw"></i>删除</button></td></tr>');
		$("#attributeOptionTable tbody").append(trObj);
		
		removeOptionBtn = trObj.find(".btn-danger");
		removeOptionBtn.bind("click", function() {
										removeOption("#" + trId);
							});
		inputIndex ++;
	}
	function removeOption(selector) {
		$(selector).remove();
	}
</script>
@endsection
@section('content')
	
	<div class="col-lg-9">
		<form method="post" id="attributeCreate" class="form-horizontal" action="{{ url('attribute/add') }}" data-href="{{ url('attribute') }}">
			{!! csrf_field() !!}
			<div class="alert alert-danger hidden">
			</div>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">属性</h3>
				</div>
				<div class="panel-body">
				
					<div class="form-group">
						<label class="col-md-2">属性组*</label>
						<div class="col-md-10">
							<select name="attribute_group_id" id="attribute_group_id" class="form-control">
							@foreach ($attributeGroups as $attributeGroup)
								<option value="{{ $attributeGroup->id }}"<?php echo $attributeGroupId == $attributeGroup->id ? ' selected' : '' ?>>{{ $attributeGroup->name }}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">code*</label>
						<div class="col-md-10">
							<input type="text" name="code" id="code" class="form-control" placeholder="64位内英字母数字，不重复">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">标签</label>
						<div class="col-md-10">
							<input type="text" name="label" id="label" class="form-control" placeholder="不填默认为code值">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">类型*</label>
						<div class="col-md-10">
							<select name="type" id="type" class="form-control">
								<option value ="text">文本</option>
								<!--<option value ="html">html</option>-->
								<option value ="dropdown">下拉</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">描述</label>
						<div class="col-md-10">
							<textarea name="description" id="description" rows="3" class="form-control"></textarea>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">属性选项(非下拉时选项不生效)</h3>
				</div>
				<div class="panel-body">
					<div class="m_b_15">
						<button type="button" class="btn btn-success" id="addOptionBtn">
							<i class="fa fa-plus fa-fw"></i>
							添加选项
						</button>
					</div>
					<table class="table table-responsive table-bordered" id="attributeOptionTable">
						<thead>
							<tr>
								<td>显示名（不填默认为值的内容）</td>
								<td>值*</td>
								<td class="text-center">操作</td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
	<div class="clearfix"></div>
	<div class="col-lg-9">
		<div class="panel-body text-right">
			<button type="button" class="btn btn-default m_lr_10" onclick="history.back();">
				<i class="fa fa-rotate-left fa-fw"></i>
				返回
			</button>
			<button type="button" class="btn btn-primary" id="submitButton">
				<i class="fa fa-save fa-fw"></i>
				保存
			</button>
		</div>
	</div>
@endsection