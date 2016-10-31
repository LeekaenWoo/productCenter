@extends('layouts.main')
@section('title','添加产品类型')
@section('head')
<script src="{{ asset('js/attribute.js') }}"></script>
<script>
$(function() {
	addAjaxListener("#submitButton", "#attributeSetCreate");
});
</script>
@endsection
@section('content')
	<div class="col-lg-9">
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title">产品类型</h3>
			</div>
			<div class="panel-body">
			<form method="post" id="attributeSetCreate" class="form-horizontal" action="{{ url('attribute/set/add') }}" data-href="{{ url('attribute/set') }}">
				{!! csrf_field() !!}
				<div class="alert alert-danger hidden">
				</div>
				<div class="form-group">
					<label class="col-md-2">产品类型名*</label>
					<div class="col-md-10">
						<input type="text" name="name" id="name" class="form-control" placeholder="64位内">
					</div>
				</div>
				<hr />
				<div class="col-sm-6" style="border-right: 1px solid rgba(180, 170, 170, 0.3);">
					<h3 class="m_t_b_5" >已选属性</h3>
					<hr class="m_t_b_5" />
					<ul class="list-group" id="selectedAttr" ondrop="dropToSelected(event)" ondragover="allowDrop(event)">
					</ul>
				</div>
			</form>
				<div class="col-sm-6">
					<h3 class="m_t_b_5">属性池</h3>
					<hr class="m_t_b_5" />
					<ul class="list-group" id="unSelectedAttr" ondrop="dropToUnselected(event)" ondragover="allowDrop(event)">	
						@foreach ($attributeData as $key => $attributeGroupData)
							<li>
							<div type="button" data-toggle="collapse"
									data-target="#collapse_{{ $key }}">
								<a href="#collapse_{{ $key }}" data-toggle="collapse" data-target="#collapse{{ $key }}">
									<span class="fa fa-fw fa-folder-o"></span>
									{{ $attributeGroupData['groupName'] }}
								</a>
							</div>
							<ul id="collapse{{ $key }}" class="collapse in">
								@foreach ($attributeGroupData['groupAttributes'] as $attribute)
									<li id="collapse{{ $key }}-{{ $attribute->id }}" title="{{ $attribute->description }}" class="c_pointer"
										draggable="true" ondragstart="drag(event)">
										<input type="hidden" name="attributeId[]" value="{{ $attribute->id }}" />
										<i class="fa fa-newspaper-o fa-fw"></i>
										{{ $attribute->label }}
									</li>
								@endforeach
							</ul>
							</li>	
						@endforeach
					</ul>
				</div>
			</div>
		</div>
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