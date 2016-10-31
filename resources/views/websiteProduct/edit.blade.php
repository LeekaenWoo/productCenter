@extends('layouts.main')
@section('title','编辑网站产品')
@section('head')
<script src="{{ asset('js/product.js') }}"></script>
<script>
	$(function() {
		changeStatus(($("#status-toggle .btn:nth-child({{ $webProduct->status }})")));
		
		@if (!empty($productAttributeJson))
			reLoadAttributes({!! $productAttributeJson !!});
		@endif
		
		
		$("#setAttributes input").prop("readonly", true);
		addAjaxListener("#submitButton", "#websiteProductEdit");
	});
</script>

<style>
#setAttributes input[readonly] {
	background-color : #fff;
}
</style>
@endsection
@section('content')
	<div class="col-lg-9">
		<form method="post" id="websiteProductEdit" class="form-horizontal" action="{{ url('product/website/update') }}" data-href="{{ url('product/website') }}">
			{!! csrf_field() !!}
			<input type="hidden" name="id" value="{{ $webProduct->id }}" />
			<div class="alert alert-danger hidden">
			</div>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">网站产品属性</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-2">归属网站*</label>
						<div class="col-md-10">
							{{ !empty($website) ? $website->name : '' }}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">产品*</label>
						<div class="col-md-10">
							{{ !empty($product) ?  $product->name : '' }}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">sku*</label>
						<div class="col-md-10">
							<input type="text" name="sku" id="sku" value="{{ $webProduct->sku }}" class="form-control" placeholder="64位内英字母数字，必填，不重复">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">name*</label>
						<div class="col-md-10">
							<input type="text" name="name" id="name" value="{{ $webProduct->name }}" class="form-control" placeholder="64位内，产品名">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">price*</label>
						<div class="col-md-10">
							<input type="number" name="price" id="price" value="{{ $webProduct->price }}" class="form-control" placeholder="价格">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">special_price</label>
						<div class="col-md-10">
							<input type="number" name="special_price" id="special_price" value="{{ $webProduct->special_price }}" class="form-control" placeholder="special_price">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">cost</label>
						<div class="col-md-10">
							<input type="number" name="cost" id="cost" class="form-control" value="{{ $webProduct->cost }}" placeholder="cost">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">qty*</label>
						<div class="col-md-10">
							<input type="number" name="qty" id="qty" value="{{ $webProduct->qty }}" class="form-control" placeholder="cost">
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
					<div class="form-group hidden">
						<label class="col-md-2">visibility*</label>
						<div class="col-md-10">
							<div class="btn-group" data-toggle="buttons" id="status-toggle">
								<label class="btn btn-default">
									<input type="radio" name="visibility" value="1">yes
								</label>
								<label class="btn btn-default">
									<input type="radio" name="visibility" value="2">none
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">描述</label>
						<div class="col-md-10">
							<textarea name="description" id="description" rows="3" class="form-control">{{ $webProduct->description }}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">SEO标题</label>
						<div class="col-md-10">
							<input type="text" name="meta_title" id="meta_title" value="{{ $webProduct->meta_title }}" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">SEO关键字</label>
						<div class="col-md-10">
							<input type="text" name="meta_keyword" id="meta_keyword	" value="{{ $webProduct->meta_keyword }}" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">SEO描述</label>
						<div class="col-md-10">
							<textarea name="meta_description" id="meta_description" rows="3" class="form-control">{{ $webProduct->meta_description }}</textarea>
						</div>
					</div>
				<form>
				</div>
			</div>
		</form>
		<hr />
		
		<!-- 产品情况一览 -->
		@if (isset($productError))
			<div class="alert alert-danger">
				{{ $productError }}
			</div>
		@else
		<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">产品属性</h3>
				</div>
				<div class="panel-body">
				<!--form仅为布局-->
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-md-2">产品类型*</label>
							<div class="col-md-10">
								{{ $product->attributeSetName }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2">sku*</label>
							<div class="col-md-10">
								{{ $product->sku }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2">name*</label>
							<div class="col-md-10">
								{{ $product->name }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2">status*</label>
							<div class="col-md-10">
									<label class="btn btn-info">
										{{ $product->status == 1 ? 'enabled' : 'disabled'}}
									</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2">quote</label>
							<div class="col-md-10">
								{{ $product->quote }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2">描述</label>
							<div class="col-md-10">
								{{ $product->description }}
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
						</h3>
						<div id="productPreview">
							@foreach ($productImages as $productImage)
								<div class='productPreviewSub'>
									<div>
										<img class="img-responsive" src="{{ asset('productImages') }}{{ $productImage->path }}" />
										<input name="oId[]" type="hidden" value="{{ $productImage->id }}" />
										<input name="oSort[]" type="number" value="{{ $productImage->sort }}" readonly class='form-control' placeholder='顺序' />
										<input name="oLabel[]" type="text" value="{{ $productImage->label }}" readonly maxlength='64' class='form-control' placeholder='标签' />
									</div>
								</div>
							@endforeach
						</div>
						<div id="productPreview">
						</div>
					</form>
				</div>
			</div>
			@endif
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