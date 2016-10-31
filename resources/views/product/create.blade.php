@extends('layouts.main')
@section('title','新增产品')
@section('head')
<script src="{{ asset('js/product.js') }}"></script>
<script>
	$(function() {
		$("#attribute_set_id").bind("change", function() {
			$("#attribute_set_id").prop("readonly", true);
			var setId = $("#attribute_set_id option:selected").val();
			loading();
			var response = getByUrl('../../attribute/set/detail/' + setId);
			reLoadAttributes(response);
			$("#attribute_set_id").prop("readonly", false);
			loaded();
		});
	
		$("#attribute_set_id").change();
		addAjaxListener("#submitButton", "#productCreate");
		
		var inputIndex = 1;
		$("#productPics").change(function() {
			if ($(this).data('clear')) {
				$("#productPreview").html("");
			}
			
			var files = Array.prototype.slice.call($("#productPics")[0].files);
			
			files.forEach(function(file, i) {
				addProductImage(file, inputIndex);
				inputIndex ++;
			});
		});
	});
	
</script>
@endsection
@section('content')
	
	<div class="col-lg-9">
		<form method="post" id="productCreate" class="form-horizontal" action="{{ url('product/add') }}" data-href="{{ url('product') }}">
			{!! csrf_field() !!}
			<div class="alert alert-danger hidden">
			</div>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">产品属性</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-2">产品类型*</label>
						<div class="col-md-10">
							<select name="attribute_set_id" id="attribute_set_id" class="form-control">
							@foreach ($attributeSets as $attributeSet)
								<option value="{{ $attributeSet->id }}">{{ $attributeSet->name }}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">sku*</label>
						<div class="col-md-10">
							<input type="text" name="sku" id="sku" class="form-control" placeholder="64位内英字母数字，必填，不重复">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">name*</label>
						<div class="col-md-10">
							<input type="text" name="name" id="name" class="form-control" placeholder="64位内，产品名">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">status*</label>
						<div class="col-md-10">
							<div class="btn-group" data-toggle="buttons" id="status-toggle">
								<label class="btn btn-default">
									<input type="radio" name="status" value="1">enabled
								</label>
								<label class="btn btn-default">
									<input type="radio" name="status" value="2">disabled
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">quote</label>
						<div class="col-md-10">
							<input type="text" name="quote" id="quote" class="form-control" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">描述</label>
						<div class="col-md-10">
							<textarea name="description" id="description" rows="3" class="form-control"></textarea>
						</div>
					</div>
					<hr />
					<h3 class="panel-title m_b_15">产品类型属性表</h3>
					<table class="table table-responsive table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>code</th>
								<td>属性名</td>
								<td>值</td>
							</tr>
						</thead>
						<tbody id="setAttributes">
						</tbody>
					</table>
					<hr />
					<h3 class="panel-title m_b_15">
						产品图片
						<input type="file" name="productPics[]" id="productPics" multiple="multiple" class="hidden" data-clear="true" accept="image/png,image/jpeg"  />
						<button type="button" class="m_lr_10 btn btn-primary" onclick="$('#productPics').click();">
							<i class="fa fa-plus fa-fw"></i>
						</button>
					</h3>
					<div id="productPreview">
					</div>
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