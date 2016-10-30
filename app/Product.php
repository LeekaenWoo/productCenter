<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    public function attributeSet() {
		return $this->belongsTo('App\AttributeSet');
	}
	
	public function productAttributes() {
		return $this->hasMany('App\ProductAttribute');
	}
	
	//各网站相同产品
	public function websiteProducts() {
		return $this->hasMany('App\WebsiteProduct');
	}
	
	public function mediaGalleries() {
		return $this->hasMany('App\MediaGallery');
	}
	
	public static function getProductDetail($id) {
		$product = DB::table('products')->where('products.id', '=', $id)
						->leftJoin('attribute_sets', 'products.attribute_set_id', '=', 'attribute_sets.id')
						->select('products.*', 'attribute_sets.name as attributeSetName')
						->first();

		if ($product == null || $product->attributeSetName == null) {
			return false;
		}

		//图片信息
		$productImages = MediaGallery::where('product_id', '=', $id)->orderBy('sort', 'desc')->orderBy('id', 'asc')->get();
		
		/**
		 * 属性信息获取
		 */
		//全局变量tmpProductId -- join子句
		$GLOBALS['tmpProductId'] = $id;
		$originalPoductAttributes = DB::table('attributes')->join('attribute_set_entities as entity', 'attributes.id', '=', 'entity.attribute_id')
			->leftJoin('attribute_options as options', 'attributes.id', '=', 'options.attribute_id')
			->leftJoin('product_attributes', function($join) {
				$join->on('product_attributes.attribute_id', '=', 'attributes.id')
						->where('product_attributes.product_id', '=', $GLOBALS['tmpProductId']);
			})
			->where('entity.attribute_set_id', '=', $product->attribute_set_id)->select('attributes.*', 'product_attributes.value', 'options.label as option_label', 'options.value as option_value')->orderBy('attribute_group_id', 'asc')
			->orderBy('id', 'asc')->get();
		
		
		$data = ['status' => 'OK', 'data' => [], 'msg' => '获取产品属性信息成功'];
		foreach ($originalPoductAttributes as $key => $tmp) {
			//以属性ID为键值新增数组
			if (!isset($data['data'][$tmp->id])) {
				$data['data'][$tmp->id] = [ 'id' => $tmp->id, 'code' => $tmp->code, 
											'label' => $tmp->label, 'type' => $tmp->type, 
											'value' => $tmp->value,
											'description' => $tmp->description, 
											'options' => [] ];
			}
			
			//push选项
			$data['data'][$tmp->id]['options'][] = ['option_label' => $tmp->option_label, 'option_value' => $tmp->option_value];
			unset($originalPoductAttributes[$key]);
		}
		unset($originalPoductAttributes);
		
		return ['product' => $product, 'productImages' => $productImages, 'productAttributeJson' => json_encode($data)];
	}
}

