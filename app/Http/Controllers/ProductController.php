<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use App\AttributeSet;
use App\ProductAttribute;
use App\MediaGallery;
use App\ResizeImage;
use DB;
use Validator;
use Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {		
		$sku = $this->request->input('sku');
		//return $result = print_r($products = Product::with('attributeSet')->get(), true);

		$products = DB::table('products')->leftJoin('attribute_sets', 'products.attribute_set_id', '=', 'attribute_sets.id')
										->leftJoin(DB::raw('(select * from (select * from media_galleries order by sort desc, id asc) as tmp group by product_id) as media_galleries'), 
															'products.id', '=', 'media_galleries.product_id')
															->orderBy('products.id', 'asc');

		if (!empty($sku)) {
			$products->where('products.sku', 'like', '%' . $sku . "%");
		}
		
		$products = $products->select('products.*', 'attribute_sets.name as attribute_set_name', 'media_galleries.path')->paginate(env('COUNT_PER_PAGE', 20));
	
        return view('product.index', ['sku' => $sku, 'products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$attributeSets = AttributeSet::all();
        return view('product.create', ['attributeSets' => $attributeSets]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
		$data = ['status' => 'NG', 'data' => '', 'msg' => '操作失败'];
		
		//检验输入  属性ID ,VALUE数组 
		$validator = Validator::make($this->request->input(), [
			'attribute_set_id' => 'required|integer|exists:attribute_sets,id',
			'sku' => 'required|unique:products,sku',
			'name' => 'required|max:64',
			'status' => 'required|integer|between:1,2',
			'quote' => 'digits_between:0,99999999.9999',
			'attributeId' => 'required',
			'attributeValue' => 'required'
        ]);
		//验证sku 为64位英字母， alpha_num ,正则 有BUG，另行在此验证
		$validator->after(function($validator) {
			if (preg_match('/^[0-9a-zA-Z]{1,64}$/', $this->request->input('sku', '')) == 0) {
				$validator->errors()->add('sku', 'incorrect format,it should be alpha_num which length is less than 64');
			}
		});	
		

		if (!$validator->fails()) {
			//新建属性集
			$product = new Product;
			$product->attribute_set_id = $this->request->input('attribute_set_id');
			$product->sku = $this->request->input('sku');
			$product->name = $this->request->input('name');
			$product->status = $this->request->input('status');
			$product->quote = $this->request->input('quote');
			$product->description = $this->request->input('description');
			$product->save();
			
			$productId = $product->id;
			
			//保存产品属性
			foreach ($this->request->input('attributeId') as $key => $attributeId) {
				$productAttribute = new ProductAttribute();
				//$productAttribute->product_id = $productId;
				$productAttribute->attribute_id = $attributeId;
				$productAttribute->value = isset($this->request->input('attributeValue')[$key]) ? $this->request->input('attributeValue')[$key] : '';
				$productAttribute->save();
			}
			
			$imagePath = public_path() . '/productImages';
			$thumbPath = public_path() . '/productThumbnails';

			//0-9目录手动预创建0777
			$subDir = '/' . substr($productId, 0, 1) . '/' . $productId;
			mkdir($imagePath . $subDir, 0777);
			mkdir($thumbPath . $subDir, 0777);
		
			//图片处理
			if ($this->request->hasFile('productPics') && $this->request->has('sort') && $this->request->has('label')) {
				
				$sortArray = $this->request->input('sort');
				$labelArray = $this->request->input('label');
				
				//$imagePath = public_path() . '/productImages';
				//$thumbPath = public_path() . '/productThumbnails';
				//取拼音首字母较麻烦， 随机字母作为下一级目录
				foreach ($this->request->file('productPics') as $key => $file) {
					$sort = (isset($sortArray[$key]) && is_numeric($sortArray[$key])) ? $sortArray[$key] :  0;
					$label = isset($labelArray[$key]) ? $labelArray[$key] :  '';
					MediaGallery::saveMedia($file, $productId, $this->request->user()->id, $sort, $label);
				}
			}
			$data['status'] = 'OK';
			$data['msg'] = '新增产品' . $product->sku . '<br />' . $product->name . '成功<br />';
		} else {
			foreach ($validator->errors()->all() as $error) {
				$data['msg'] .= '<br />' . $error;
			}
		}

		return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$data = Product::getProductDetail($id);
		if ($data == false) {
			abort(404);
		}
		
		return view('product.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function update()
    {		
        $id = $this->request->input('id', '');
		$product = Product::findOrFail($id);
				
		$data = ['status' => 'NG', 'data' => '', 'msg' => '操作失败'];
		
		//检验输入  属性ID ,VALUE数组 
		$validator = Validator::make($this->request->input(), [
			'sku' => 'required|unique:products,sku,' . $id,
			'name' => 'required|max:64',
			'status' => 'required|integer|between:1,2',
			'quote' => 'digits_between:0,99999999.9999',
			'attributeId' => 'required',
			'attributeValue' => 'required'
        ]);
		//验证sku 为64位英字母， alpha_num ,正则 有BUG，另行在此验证
		$validator->after(function($validator) {
			if (preg_match('/^[0-9a-zA-Z]{1,64}$/', $this->request->input('sku', '')) == 0) {
				$validator->errors()->add('sku', 'incorrect format,it should be alpha_num which length is less than 64');
			}
		});	
		
		if (!$validator->fails()) {
			//新建属性集
			$product->sku = $this->request->input('sku');
			$product->name = $this->request->input('name');
			$product->status = $this->request->input('status');
			$product->quote = $this->request->input('quote');
			$product->description = $this->request->input('description');
			$product->save();
			
			$attributeIds = $this->request->input('attributeId');
			$attributeValues = $this->request->input('attributeValue');
			
			//编辑显示页面 产品属性直接与属性集/产品类型相同，考虑到属性集（新增/删除属性）
			$oldProductAttributeIds = DB::table('product_attributes')->where('product_id', '=', $id)->lists('attribute_id', 'id');
			
			//需删除的项目
			$toDelAttributeIds = array_diff($oldProductAttributeIds, $attributeIds);
			if (!empty($toDelAttributeIds)) {
				DB::table('product_attributes')->whereIn('id', array_keys($toDelAttributeIds))->delete();
			}

			
			//需新增的项目
			$toAddAttributeIds = array_diff($attributeIds, $oldProductAttributeIds);
			foreach ($toAddAttributeIds as $key => $attributeId) {
				$productAttribute = new ProductAttribute();
				$productAttribute->product_id = $id;
				$productAttribute->attribute_id = $attributeId;
				$productAttribute->value = isset($attributeValues[$key]) ? $attributeValues[$key] : '';
				$productAttribute->save();
			}
			//需更新的项目 输入，原有属性的交集
			$toUpdateAttributeIds = array_intersect($attributeIds, $oldProductAttributeIds);
			
			foreach ($toUpdateAttributeIds as $key => $attributeId) {
				DB::table('product_attributes')->where('product_id', '=', $id)
												->where('attribute_id', '=', $attributeId)
												->update(['value' => isset($attributeValues[$key]) ? $attributeValues[$key] : '']);
			}
			
			
			/**
			 * 更新部分图片记录， oIdArray处理
			 */
			$oIdArray = [];
			//注意这个一定要预先(优先于新增图片)取出
			$mediaGalleryPaths = DB::table('media_galleries')->where('product_id', '=', $id)->lists('path','id');
			
			if ($this->request->has('oId')  && $this->request->has('oSort') && $this->request->has('oLabel')) {
				$oIdArray = $this->request->input('oId');
				$oSortArray = $this->request->input('oSort');
				$oLabelArray = $this->request->input('oLabel');
				
				//取交集， 要更新的media_galleries记录, 注意新数组保留了原有input里的键值
				$toUpdateMediaIds = array_intersect ($this->request->input('oId'), array_keys($mediaGalleryPaths));
				foreach ($toUpdateMediaIds as $key => $toUpdateAttributeId) {
					$sort = (isset($oSortArray[$key]) && is_numeric($oSortArray[$key])) ? $oSortArray[$key] :  0;
					$label = isset($oLabelArray[$key]) ? $oLabelArray[$key] :  '';
					MediaGallery::where('id', '=', $toUpdateAttributeId)->update(['sort' => $sort, 'label' => $label]);
				}
			}
			
			/**
			 * 删除部分产品图片及记录
			 */
			$toDelMediaIds = array_diff(array_keys($mediaGalleryPaths), $oIdArray);
			foreach ($toDelMediaIds as $toDelMediaId) {
				$toDeleteMediaPaths[$toDelMediaId] = $mediaGalleryPaths[$toDelMediaId];
			}
			//删除文件， 清除相应 media_galleries记录
			if (!empty($toDeleteMediaPaths)) {
				//删除部分产品图片
				MediaGallery::deleteMedia($toDeleteMediaPaths);
				MediaGallery::whereIn('id', array_keys($toDeleteMediaPaths))->delete();
			}

			//新增图片
			if ($this->request->hasFile('productPics') && $this->request->has('sort') && $this->request->has('label')) {
				$sortArray = $this->request->input('sort');
				$labelArray = $this->request->input('label');
				
				foreach ($this->request->file('productPics') as $key => $file) {
					if (!in_array($file->getMimeType(), ['image/png', 'image/gif', 'image/jpeg'])) {
						continue;
					}
					$sort = (isset($sortArray[$key]) && is_numeric($sortArray[$key])) ? $sortArray[$key] :  0;
					$label = isset($labelArray[$key]) ? $labelArray[$key] :  '';
					//新增产品图片并保存记录
					MediaGallery::saveMedia($file, $id, $this->request->user()->id, $sort, $label);
				}
			}
			
			$data['status'] = 'OK';
			$data['msg'] = '更新产品' . $product->sku . '<br />' . $product->name . '成功<br />';
		} else {
			foreach ($validator->errors()->all() as $error) {
				$data['msg'] .= '<br />' . $error;
			}
		}

		return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$product = Product::find($id);
		if ($product) {
			//删除产品属性
			DB::table('product_attributes')->where('product_id', '=', $id)->delete();
		
			//清除media_galleries记录并删除文件
			DB::table('media_galleries')->where('product_id', '=', $id)->delete();
			
			//删除相应文件及目录
			$subDir = '/' . substr($id, 0, 1) . '/' . $id;
			$imagePath = public_path() . '/productImages' . $subDir;
			$thumbPath = public_path() . '/productThumbnails' . $subDir;	
			foreach ([$imagePath, $thumbPath] as  $dir) {
				if (is_dir($dir)) {
					$fileList = scandir($dir);
					foreach ($fileList as $file) {
						if ($file == '.' || $file == '..') {
							continue;
						}
						unlink($dir . '/' . $file);
					}
					rmdir($dir);
				}
			}
		
			$product->delete();
		}
		
		return response()->json(['status' => 'OK', 'data' => '', 'msg' => '删除产品成功']);
    }
}
