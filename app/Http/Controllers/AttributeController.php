<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Attribute;
use App\AttributeGroup;
use App\AttributeOption;
use Validator;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$attributeGroupId = $this->request->input('attributeGroupId', '');
		$code = $this->request->input('code');
		
		$attributeGroups = AttributeGroup::all();
		$attributes = Attribute::select();
		
		if (!empty($attributeGroupId)) {
			$attributes->where('attribute_group_id', '=', $attributeGroupId);
		}
		if (!empty($code)) {
			$attributes->where('code', 'like', '%' . $code . "%");
		}
		
		$attributes = $attributes->paginate(env('COUNT_PER_PAGE', 20));
		return view('attribute.index', [
										'attributeGroupId' => $attributeGroupId, 'code' => $code, 
										'attributeGroups' => $attributeGroups, 'attributes' => $attributes
										]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$attributeGroupId = $this->request->input('attributeGroupId', 0);
		$attributeGroups = AttributeGroup::all();
		
        return view('attribute.create', ['attributeGroupId' => $attributeGroupId, 'attributeGroups' => $attributeGroups]);
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
		
		$validator = Validator::make($this->request->input(), [
			'attribute_group_id' => 'required|integer',
            'code' => 'required|max:64|unique:attributes',
			'type' => 'required',
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
		
		if (!$validator->fails()) {
			//新建属性
			$attribute = new Attribute;
			$attribute->attribute_group_id = $this->request->input('attribute_group_id');
			$attribute->code = $this->request->input('code');
			$attribute->label = empty($this->request->input('label')) ? $attribute->code : $this->request->input('label');
			
			$this->request->input('label', $attribute->code);
			$attribute->type = $this->request->input('type', 'text');
			$attribute->description = $this->request->input('description');
			$attribute->save();
			
			$attributeId = $attribute->id;
			
			$requests = $this->request->input();
			ksort($requests);
			
			//新增相应属性选项并统计数目
			$optionCount = 0;
			foreach ($requests as $key => $request) {
				if (substr($key, 0, 9) == 'attrValue') {
					$attributeOption = new AttributeOption();
					$attributeOption->attribute_id = $attributeId;
					$attributeOption->value = $request;
					$attributeOption->label = empty($requests['attrLabel' . substr($key, 9)]) ? $attributeOption->value : $requests['attrLabel' . substr($key, 9)];
					$attributeOption->save();
					$optionCount++;
				}
			}

			$data['status'] = 'OK';
			$data['msg'] = '新增属性' . $attribute->label . '成功<br />选项个数' . $optionCount;
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$attribute = Attribute::findOrfail($id);
		$attributeOptions = $attribute->attributeOptions;
		$attributeGroups = AttributeGroup::all();
		
		return view('attribute.edit',['attributeGroups' => $attributeGroups, 'attribute' => $attribute, 'attributeOptions' => $attributeOptions]);
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
		$data = ['status' => 'NG', 'data' => '', 'msg' => '操作失败'];
		if (empty($this->request->input('id'))) {
			abort(404);
		}
		$validator = Validator::make($this->request->input(), [
			'attribute_group_id' => 'required|integer',
            'code' => 'required|max:64|unique:attributes,code,' . $this->request->input('id'),
			'type' => 'required',
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
		
		if (!$validator->fails()) {
			//新建属性
			$attribute = Attribute::findOrfail($this->request->input('id'));
			$attribute->attribute_group_id = $this->request->input('attribute_group_id');
			$attribute->code = $this->request->input('code');
			$attribute->label = empty($this->request->input('label')) ? $attribute->code : $this->request->input('label');
			
			$this->request->input('label', $attribute->code);
			$attribute->type = $this->request->input('type', 'text');
			$attribute->description = $this->request->input('description');
			$attribute->save();
			
			$attributeId = $attribute->id;
			
			$requests = $this->request->input();
			ksort($requests);
			
			//新增相应属性选项并统计数目
			$optionCount = 0;
			$updateCount = 0;
			foreach ($requests as $key => $request) {
				$namePre = substr($key, 0, 10);
				if ($namePre == 'nAttrValue') {
					$attributeOption = new AttributeOption();
					$attributeOption->attribute_id = $attributeId;
					$attributeOption->value = $request;
					$attributeOption->label = empty($requests['nAttrLabel' . substr($key, 9)]) ? $attributeOption->value : $requests['nAttrLabel' . substr($key, 9)];
					$attributeOption->save();
					$optionCount++;
				} elseif ($namePre == 'oAttrValue') {
					//对原有选项的更新操作 name 格式 oAttrValue_id
					$oAttributeOptionId = substr($key, 10);
					if ($oAttributeOption = AttributeOption::find($oAttributeOptionId)) {
						$oAttributeOption->value = $request;
						$oAttributeOption->label = empty($requests['oAttrLabel' . $oAttributeOptionId]) ? $oAttributeOption->value : $requests['oAttrLabel' . $oAttributeOptionId];
						$oAttributeOption->save();
						$updateCount ++;
					}
				}
			}

			$data['status'] = 'OK';
			$data['msg'] = '修改属性' . $attribute->label . '成功<br />新增选项' . $optionCount . '个<br />' . 
							'更新选项' . $updateCount . '个';
		} else {
			foreach ($validator->errors()->all() as $error) {
				$data['msg'] .= '<br />' . $error;
			}
		}

		return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
	 * 删除属性 同时删除关联属性选项
	 * attribute_set_entitys product_attributes 是否需相应操作待考量
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$attribute = Attribute::find($id);
		if ($attribute) {
			AttributeOption::where('attribute_id', $id)->delete();
			$attribute->delete();
			return response()->json(['status' => 'OK', 'data' => '', 'msg' => '']);
		}
		return response()->json(['status' => 'NG', 'data' => '', 'msg' => '删除失败']);
    }
	
	/**
	 *移除指定ID的属性选项
	 *
	 *@param int $id
	 *@return \Illuminate\Http\Response
	 */
	public function destroyOption($id)
    {
		$attributeOption = AttributeOption::find($id);
		if ($attributeOption) {
			if ($attributeOption->delete()) {
				return response()->json(['status' => 'OK', 'data' => '', 'msg' => '删除成功']);
			} else {
				return response()->json(['status' => 'NG', 'data' => '', 'msg' => '删除失败，请联系管理员']);
			}
			
		}
		return response()->json(['status' => 'OK', 'data' => '', 'msg' => '未找到相应选项']);
    }
}
