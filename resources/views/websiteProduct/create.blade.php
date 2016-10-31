@extends('layouts.main')
@section('title','新增网站产品')
@section('head')
<script src="{{ asset('js/product.js') }}"></script>
<script>
	$(function() {
		addAjaxListener("#submitButton", "#websiteProductCreate");
	});
</script>
@endsection
@section('content')
	<div class="col-lg-9">
		<form method="post" id="websiteProductCreate" class="form-horizontal" action="{{ url('product/website/add') }}" data-href="{{ url('product/website') }}">
			{!! csrf_field() !!}
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
							<select name="website_id" id="website_id" class="form-control">
							@foreach ($websites as $website)
								<option value="{{ $website->id }}">{{ $website->name }}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">产品*</label>
						<div class="col-md-10">
							<select name="product_id" id="product_id" class="form-control">
							@foreach ($products as $product)
								<option value="{{ $product->id }}" title="{{ $product->description }}">{{ $product->name }}</option>
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
						<label class="col-md-2">price*</label>
						<div class="col-md-10">
							<input type="number" name="price" id="price" class="form-control" placeholder="价格">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">special_price</label>
						<div class="col-md-10">
							<input type="number" name="special_price" id="special_price" class="form-control" placeholder="special_price">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">cost</label>
						<div class="col-md-10">
							<input type="number" name="cost" id="cost" class="form-control" placeholder="cost">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">qty*</label>
						<div class="col-md-10">
							<input type="number" name="qty" id="qty" class="form-control" placeholder="cost">
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
							<textarea name="description" id="description" rows="3" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">SEO标题</label>
						<div class="col-md-10">
							<input type="text" name="meta_title" id="meta_title" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">SEO关键字</label>
						<div class="col-md-10">
							<input type="text" name="meta_keyword" id="meta_keyword	" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2">SEO描述</label>
						<div class="col-md-10">
							<textarea name="meta_description" id="meta_description" rows="3" class="form-control"></textarea>
						</div>
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