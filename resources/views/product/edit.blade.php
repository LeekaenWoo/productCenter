@extends('layouts.main')
@section('title','编辑产品')
@section('head')
<script src="{{ asset('js/product.js') }}"></script>
<script>
var picInputIndex = 1;

$(function() {
	changeStatus(($("#status-toggle .btn:nth-child({{ $product->status }})")));
	reLoadAttributes({!! $productAttributeJson !!});
	addAjaxListener("#submitButton", "#productEdit");
	
	$("#productPics").change(productPicsChange);
});

function productPicsChange() {
	var files = Array.prototype.slice.call($("#productPics")[0].files);
		
	files.forEach(function(file) {
		var imageId = addProductImage(file, picInputIndex);
		if (imageId) {
			var fileInputEle = $("#productPics");
			$("#" + imageId).parent().append(fileInputEle);
			$("#" + imageId).siblings("#productPics").removeAttr("id");
			
			$("#addSinglePicButton").before('<input type="file" name="productPics[]" id="productPics"  class="hidden" accept="image/png,image/jpeg"  />');
			$("#productPics").change(productPicsChange);
			picInputIndex ++;
		}
	});
}
</script>
@endsection
@section('content')
	<div class="col-lg-9">
		<form method="post" id="productEdit" class="form-horizontal" action="{{ url('product/update') }}" data-href="{{ url('product') }}">
			{!! csrf_field() !!}
			<input type="hidden" name="id" value="{{ $product->id }}" />
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
							{{ $product->attributeSetName }}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">sku*</label>
						<div class="col-md-10">
							<input type="text" name="sku" id="sku" value="{{ $product->sku }}" class="form-control" placeholder="64位内英字母数字，必填，不重复">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">name*</label>
						<div class="col-md-10">
							<input type="text" name="name" id="name" value="{{ $product->name }}" class="form-control" placeholder="64位内，产品名">
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
							<input type="text" name="quote" id="quote" value="{{ $product->quote }}" class="form-control" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">描述</label>
						<div class="col-md-10">
							<textarea name="description" id="description" rows="3" class="form-control">{{ $product->description }}</textarea>
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
						<input type="file" name="productPics[]" id="productPics"  class="hidden" accept="image/png,image/jpeg"  />
						<button type="button" id="addSinglePicButton" class="m_lr_10 btn btn-success" onclick="$('#productPics').click();">
							<i class="fa fa-plus fa-fw"></i>
						</button>
					</h3>
					<div id="productPreview">
						@foreach ($productImages as $productImage)
							<div class='productPreviewSub'>
								<div>
									<img class="img-responsive" src="{{ asset('productImages') }}{{ $productImage->path }}" />
									<i class="fa fa-2x fa-times-circle" onclick='$(this).parents(".productPreviewSub").remove();'></i>
									<input name="oId[]" type="hidden" value="{{ $productImage->id }}" />
									<input name="oSort[]" type="number" value="{{ $productImage->sort }}" class='form-control' placeholder='顺序' />
									<input name="oLabel[]" type="text" value="{{ $productImage->label }}" maxlength='64' class='form-control' placeholder='标签' />
								</div>
							</div>
						@endforeach
					</div>
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