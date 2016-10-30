<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AttributeGroup;
use Validator;

class AttributeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$attributeGroups = AttributeGroup::all();
		return view('attributeGroup.index', ['attributeGroups' => $attributeGroups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$data = ['status' => 'NG', 'data' => '', 'msg' => '操作失败'];
		
		$validator = Validator::make($this->request->input(), [
			'name' => 'required|max:64|unique:attribute_groups',
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
		if (!$validator->fails()) {
			$attributeGroup = new AttributeGroup;
			$attributeGroup->name = $this->request->input('name');
			$attributeGroup->save();

			$data['status'] = 'OK';
			$data['msg'] = '修改成功';
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
		$attributeGroup = AttributeGroup::findOrfail($id);
		$attributes = $attributeGroup->attributes;
		
		return view('attributeGroup.show',['attributeGroup' => $attributeGroup, 'attributes' => $attributes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $data = ['status' => 'NG', 'data' => '', 'msg' => '很抱歉，未找到相关信息'];
        if ($id = $this->request->input('id')) {
			$attributeGroup = AttributeGroup::find($id);
			if ($attributeGroup) {		
				$data['status'] = 'OK';
				$data['data'] = $attributeGroup;
				$data['msg'] = '';
			}
		}
		
		return response()->json($data);
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
		$validator = Validator::make($this->request->input(), [
			'id' => 'required|integer',
			'name' => 'required|max:64|unique:attribute_groups,name,' . $this->request->input('id')
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
		if (!$validator->fails()) {
			$id = $this->request->input('id');
			if ($attributeGroup = attributeGroup::find($id)) {
				$attributeGroup->name = $this->request->input('name');	
				$attributeGroup->save();

				$data['status'] = 'OK';
				$data['msg'] = '修改成功';
			}
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
		$attributeGroup = AttributeGroup::find($id);
		//删除 属性组下 关联属性 同时删除关联属性选项
		if ($attributeGroup) {
			
			/*
			$attributes = $attributeGroup->attributes;
			foreach ($attributes as $groupAttribute) {
				$attributeOptions = $groupAttribute->attributeOptions;
				foreach ($attributeOptions as $attributeOption) {
					$attributeOption->delete();
				}
				$groupAttribute->delete();
			}
			*/
			
			$attributeGroup->delete();
			
			return response()->json(['status' => 'OK', 'data' => '', 'msg' => '']);
		}
		return response()->json(['status' => 'NG', 'data' => '', 'msg' => '删除失败']);
    }
}
