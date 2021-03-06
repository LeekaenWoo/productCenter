@extends('layouts.main')
@section('title','网站产品管理')
@section('content')
	<div class="">
		<form action="{{ url('product') }}" method="post">
			{!! csrf_field() !!}
			<input type="text" name="sku" class="form-control width_initial" value="" placeholder="sku" />
			<button type="submit" class="btn btn-info btn_wide"><i class="fa fa-search fa-fw"></i>&nbsp;查找</button>
		</form>	
	</div>
	<div class="form-group">
		<button class="btn btn-success" onclick="location.href='{{ url('product/website/create') }}';">
			<i class="fa fa-plus fa-fw"></i>新增产品
		</button>
	</div>
	<table class="table table-responsive table-striped table-bordered table-hover v_middle">
		<thead>
			<tr>
				<th>ID</th>
				<th>网站</th>
				<th class="text-center">产品图</th>
				<th class="text-center">产品SKU</th>
				<th class="text-center">网站SKU</th>
				<th>产品名</th>
				<th>产品类型</th>
				<th>状态</th>
				<th class="text-center">操作</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($products as $product)
			<tr>
				<td>{{ $product->id }}</td>
				<td>{{ $product->website_name }}</td>
				<td>
					@if ($product->path)
						<img src="{{ asset('productThumbnails') }}{{ $product->path }}" class="center-block" />
					@endif
				</td>
				<td class="text-center">{{ $product->product_sku }}</td>
				<td class="text-center">{{ $product->sku }}</td>
				<td>{{ $product->name }}</td>
				<td>{{ $product->attribute_set_name }}</td>
				<td>{{ $product->status == 1 ? 'enabled' : 'disabled' }}</td>
				<td class="text-center">
					<button class="btn btn-success" onclick="location.href='{{ url('product/website/edit') }}/{{ $product->id }}';">
						<i class="fa fa-edit fa-fw"></i>
						编辑
					</button>
					<button class="btn btn-danger" onclick="confirmDel('{{ url('product/website/delete') }}/{{ $product->id }}','您确认删除此产品吗？（请谨慎操作）');">
						<i class="fa fa-trash fa-fw"></i>
						删除
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<div class="text-center">
		{!! $products->appends(['sku' => $sku])->render() !!}
	</div>
@endsection