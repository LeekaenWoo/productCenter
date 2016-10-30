<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Attribute;
use App\AttributeSet;
use App\AttributeGroup;
use App\AttributeSetEntity;
use DB;
use Validator;

class AttributeSetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$name = $this->request->input('name');	
		$attributeSets = AttributeSet::select();

		if (!empty($name)) {
			$attributeSets->where('name', 'like', '%' . $name . "%");
		}
		
		$attributeSets = $attributeSets->paginate(env('COUNT_PER_PAGE', 20));
		return view('attributeSet.index', ['name' => $name, 'attributeSets' => $attributeSets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		//method 1
		//取出所有属性分组并初始化 群名等
		$attributeGroups = AttributeGroup::all();
		$attributeData = array();
		foreach ($attributeGroups as $attributeGroup) {
			$attributeData[$attributeGroup->id] = ['groupName' => $attributeGroup->name, 'groupAttributes' => []]; 
		}
		//额外增加一项 未分组
		$attributeData['ungroup'] = ['groupName' => '未分组/分组信息缺失', 'groupAttributes' => []]; 
		
		$attributes = Attribute::all();
		foreach ($attributes as $attribute) {
			if (isset($attributeData[$attribute->attribute_group_id])) {
				$attributeData[$attribute->attribute_group_id]['groupAttributes'][] = $attribute;
			} else {
				$attributeData['ungroup']['groupAttributes'][] = $attribute;
			}
		}	
		/**method 2
			$attributeGroups = AttributeGroup::all();
			$data = array();
			foreach ($attributeGroups as $attributeGroup) {
				$groupAttributes = $attributeGroup->attributes;
				$data[]['groupName'] = $attributeGroup->name;
				$data[]['groupAttributes'] = $groupAttributes;
			}
			$ungroupAttributes = DB::select('select * from attributes where attribute_group_id not in (select id from attribute_groups)');
		*/
		
        return view('attributeSet.create', ['attributeData' => $attributeData]);
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
			'name' => 'required|max:64|unique:attribute_sets',
        ]);
		
		if (!$validator->fails()) {
			//新建属性集
			$attributeSet = new AttributeSet;
			$attributeSet->name = $this->request->input('name');
			$attributeSet->save();
			
			$attributeSetId = $attributeSet->id;
			
			//新增相应属性选项并统计数目
			$attributeCount = 0;
			if ($this->request->has('attributeId')) {
				foreach ($this->request->input('attributeId') as $attributeId) {
					$attributeSetEntity = new AttributeSetEntity();
					$attributeSetEntity->attribute_set_id = $attributeSetId;
					$attributeSetEntity->attribute_id = $attributeId;
					$attributeSetEntity->save();
					$attributeCount++;
				}
			}
			
			$data['status'] = 'OK';
			$data['msg'] = '新增产品类型' . $attributeSet->name . '成功<br />属性个数' . $attributeCount;
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
        
		$attributeSet = AttributeSet::findOrFail($id);
		//$attributeSetEntities = $attributeSet->attributeSetEntities;
		
		//获取 原set对应 entity的 id,和attribute_id
		$oAttributeIdArray = DB::table('attribute_set_entities')->where('attribute_set_id', '=', $id)->lists('attribute_id', 'id');
		
		$oAttributeIds = implode(',', $oAttributeIdArray);
		$oEntityIds = implode(',', array_keys($oAttributeIdArray));
		
		//method 1
		//取出所有属性分组并初始化 群名等
		$attributeGroups = AttributeGroup::all();
		//所有属性-
		$attributeData = array();
		foreach ($attributeGroups as $attributeGroup) {
			$attributeData[$attributeGroup->id] = ['groupName' => $attributeGroup->name, 'groupAttributes' => []]; 
		}
		//额外增加一项 未分组
		$attributeData['ungroup'] = ['groupName' => '未分组/分组信息缺失', 'groupAttributes' => []]; 
		
		$attributes = Attribute::all();
		foreach ($attributes as $attribute) {
			if (isset($attributeData[$attribute->attribute_group_id])) {
				$attributeData[$attribute->attribute_group_id]['groupAttributes'][] = $attribute;
			} else {
				$attributeData['ungroup']['groupAttributes'][] = $attribute;
			}
		}	
        return view('attributeSet.edit', ['attributeSet' => $attributeSet, 'attributeData' => $attributeData, 
											'oAttributeIds' => $oAttributeIds, 'oEntityIds' => $oEntityIds]);
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
		$attributeSet = AttributeSet::findOrFail($id);
		
		$validator = Validator::make($this->request->input(), [
			'name' => 'required|max:64|unique:attribute_sets,name,' . $id
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
		if (!$validator->fails()) {
			$attributeSet->name = $this->request->input('name');	
			$attributeSet->save();

			//新增相应属性选项并统计数目
			$attributeDelCount = 0;
			$attributeAddCount = 0;
			if ($this->request->has('attributeId')) {
				$attributeIds = $this->request->input('attributeId');
				$entityAttributeIds = DB::table('attribute_set_entities')->where('attribute_set_id', '=', $id)->lists('attribute_id', 'id');
				//需删除的项目
				$toDelAttributeIds = array_diff($entityAttributeIds, $attributeIds);
				$attributeDelCount = count($toDelAttributeIds);
				if ($attributeDelCount) {
					DB::table('attribute_set_entities')->whereIn('id', array_keys($toDelAttributeIds))->delete();
				}

				//需新增的项目
				$toAddAttributeIds = array_diff($attributeIds, $entityAttributeIds);
				foreach ($toAddAttributeIds as $attributeId) {
					$attributeSetEntity = new AttributeSetEntity();
					$attributeSetEntity->attribute_set_id = $id;
					$attributeSetEntity->attribute_id = $attributeId;
					$attributeSetEntity->save();
					$attributeAddCount++;
				}
			}
			
			$data['status'] = 'OK';
			$data['msg'] = '修改成功' . '<br />删除属性' . $attributeDelCount . '个<br />新增属性' . $attributeAddCount . '个';

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
		$attributeSet = AttributeSet::find($id);
		if ($attributeSet) {
			$relatedProductCount = count($attributeSet->products);
			if ($relatedProductCount > 0) {
				return response()->json(['status' => 'NG', 'data' => '', 'msg' => '此产品类型下有<span style="color: red"> ' . $relatedProductCount . ' </span>款关联产品，不能删除']);
			}
			
			DB::table('attribute_set_entities')->where('attribute_set_id', '=', $id)->delete();
			$attributeSet->delete();
		}

		return response()->json(['status' => 'OK', 'data' => '', 'msg' => '']);
    }
	
	/**
     * 移除属性集内的属性 attribute_set_entities	
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyEntity($entityId)
    {
		$attributeSetEntity = AttributeSetEntity::find($entityId);
		if ($attributeSetEntity) {
			$attributeSetEntity->delete();
		}
		
		return response()->json(['status' => 'OK', 'data' => '', 'msg' => '移除属性成功']);
    }
	/**
	 * 获取产品类型所有详细信息
	 * @param int $id
	 * return \Illuminate\Http\Response
	 */
	public function showSetDetail($id) {
		$data = ['status' => 'NG', 'data' => '', 'msg' => '获取信息失败'];
		$attributeSet = AttributeSet::find($id);
		if ($attributeSet) {
			$entityAttributeIds = AttributeSetEntity::where('attribute_set_id', '=', $id)->lists('attribute_id', 'id');
			
			$tmpArray = DB::table('attributes')->join('attribute_set_entities as entity', 'attributes.id', '=', 'entity.attribute_id')
									->leftJoin('attribute_options as options', 'attributes.id', '=', 'options.attribute_id')
									->where('entity.attribute_set_id', '=', $id)->select('attributes.*', 'options.label as option_label', 'options.value as option_value')->orderBy('attribute_group_id', 'asc')
									->orderBy('id', 'asc')->get();

			$data['data'] = [];
			foreach ($tmpArray as $key => $tmp) {
				//以属性ID为键值新增数组
				if (!isset($data['data'][$tmp->id])) {
					$data['data'][$tmp->id] = [ 'id' => $tmp->id, 'code' => $tmp->code, 
												'label' => $tmp->label, 'type' => $tmp->type, 
												'description' => $tmp->description, 
												'options' => [] ];
				}
				
				//push选项
				$data['data'][$tmp->id]['options'][] = ['option_label' => $tmp->option_label, 'option_value' => $tmp->option_value];
				
				unset($tmpArray[$key]);
			}
			
			$data['status'] = 'OK';
			$data['msg'] = '获取信息成功';
		}
		
		return response()->json($data);
	}
}
