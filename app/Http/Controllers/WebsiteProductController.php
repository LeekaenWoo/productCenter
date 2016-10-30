<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use App\WebsiteProduct;
use App\Website;
use App\Product;

class WebsiteProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$originalSku = $this->request->input('originalSku');
		$sku = $this->request->input('sku');
		$websiteId = $this->request->input('websiteId');
		$websiteSku = $this->request->input('websiteSku');

		$products = DB::table('website_products')->leftJoin('products', 'products.id', '=', 'website_products.product_id')
												->leftJoin('websites', 'website_products.website_id', '=', 'websites.id')
												->leftJoin('attribute_sets', 'attribute_sets.id', '=', 'products.attribute_set_id')
												->leftJoin(DB::raw('(select * from (select * from media_galleries order by sort desc, id asc) as tmp group by product_id) as media_galleries'), 
															'products.id', '=', 'media_galleries.product_id');
												
		if (!empty($sku)) {
			$products->where('products.sku', 'like', '%' . $sku . "%");
		}
		
		$products = $products->select('website_products.*', 'websites.name as website_name', 'attribute_sets.name as attribute_set_name','products.sku as product_sku', 'media_galleries.path')->paginate(env('COUNT_PER_PAGE', 20));
	
        return view('websiteProduct.index', ['sku' => $sku, 'products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();
		$products = Product::select('id', 'name', 'description')->get();
		
		return view('websiteProduct.create', ['websites' => $websites, 'products' => $products]);
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
			'product_id' => 'required|exists:products,id',
			'website_id' => 'required|exists:websites,id',
			'sku' => 'required|unique:website_products,sku,null,website_id,website_id,' . $this->request->input('website_id'),
			'name' => 'required|max:64',
			'price' => 'required',
			'qty' => 'required|integer',
			'status' => 'required|integer|between:1,2',
			'visibility' => 'required|integer|between:1,2',
        ]);
		//验证sku 为64位英字母， alpha_num ,正则 有BUG，另行在此验证
		$validator->after(function($validator) {
			if (preg_match('/^[0-9a-zA-Z]{1,64}$/', $this->request->input('sku', '')) == 0) {
				$validator->errors()->add('sku', 'The sku field should be alpha_num which length is less than 64');
			}
		});	
		

		if (!$validator->fails()) {
			$websiteProduct = new WebsiteProduct;
			$websiteProduct->product_id = $this->request->input('product_id');
			$websiteProduct->website_id = $this->request->input('website_id');
			
			$websiteProduct->sku = $this->request->input('sku');
			$websiteProduct->name = $this->request->input('name');
			$websiteProduct->price = $this->request->input('price');
			$websiteProduct->special_price = $this->request->input('special_price');
			$websiteProduct->cost = $this->request->input('cost');
			$websiteProduct->qty = $this->request->input('qty');
			
			$websiteProduct->status = $this->request->input('status');
			$websiteProduct->visibility = $this->request->input('visibility');
			
			$websiteProduct->description = $this->request->input('description');
			$websiteProduct->meta_title = $this->request->input('meta_title');
			$websiteProduct->meta_keyword = $this->request->input('meta_keyword');
			$websiteProduct->meta_description = $this->request->input('meta_description');
			$websiteProduct->save();		

			$data['status'] = 'OK';
			$data['msg'] = '新增网站产品' . $websiteProduct->sku . '<br />' . $websiteProduct->name . '成功<br />';
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
        $webProduct = WebsiteProduct::findOrFail($id);
		$website = $webProduct->website;
		
		$data = Product::getProductDetail($webProduct->product_id);
		if ($data == false) {
			$productError = '未找到所属产品信息';
			return view('websiteProduct.edit', ['webProduct' => $webProduct, 'website' => $website, 'productError' => $productError]);
		} else {
			return view('websiteProduct.edit',
										['webProduct' => $webProduct, 'website' => $website, 
										'product' => $data['product'], 'productImages' => $data['productImages'], 'productAttributeJson' => $data['productAttributeJson']
						]);
		}	
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $id = $this->request->input('id', '');
		$websiteProduct = WebsiteProduct::findOrFail($id);
				
		$data = ['status' => 'NG', 'data' => '', 'msg' => '操作失败'];
		
		//检验输入  属性ID ,VALUE数组 
		$validator = Validator::make($this->request->input(), [
			//非本网站产品 同一website sku不重复
			'sku' => 'required|unique:website_products,sku,' . $id . ',id,website_id,' . $this->request->input('website_id'),
			'name' => 'required|max:64',
			'price' => 'required',
			'qty' => 'required|integer',
			'status' => 'required|integer|between:1,2',
			'visibility' => 'required|integer|between:1,2',
        ]);
		//验证sku 为64位英字母， alpha_num ,正则 有BUG，另行在此验证
		$validator->after(function($validator) {
			if (preg_match('/^[0-9a-zA-Z]{1,64}$/', $this->request->input('sku', '')) == 0) {
				$validator->errors()->add('sku', 'The sku field should be alpha_num which length is less than 64');
			}
		});	
		
		if (!$validator->fails()) {
			$websiteProduct->sku = $this->request->input('sku');
			$websiteProduct->name = $this->request->input('name');
			$websiteProduct->price = $this->request->input('price');
			$websiteProduct->special_price = $this->request->input('special_price');
			$websiteProduct->cost = $this->request->input('cost');
			$websiteProduct->qty = $this->request->input('qty');
			
			$websiteProduct->status = $this->request->input('status');
			$websiteProduct->visibility = $this->request->input('visibility');
			
			$websiteProduct->description = $this->request->input('description');
			$websiteProduct->meta_title = $this->request->input('meta_title');
			$websiteProduct->meta_keyword = $this->request->input('meta_keyword');
			$websiteProduct->meta_description = $this->request->input('meta_description');
			$websiteProduct->save();		

			$data['status'] = 'OK';
			$data['msg'] = '更新网站产品' . $websiteProduct->sku . '<br />' . $websiteProduct->name . '成功<br />';
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
		$product = WebsiteProduct::find($id);
		if ($product) {
			$product->delete();
		}
		
		return response()->json(['status' => 'OK', 'data' => '', 'msg' => '删除产品成功']);
    }
}
