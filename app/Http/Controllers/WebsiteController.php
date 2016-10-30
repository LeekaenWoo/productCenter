<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Website;
use Validator;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $websites = Website::paginate(env('COUNT_PER_PAGE',20));
		return view('website.index', ['websites' => $websites]);
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
    public function store()
    {
		$data = ['status' => 'NG', 'data' => '', 'msg' => '新增失败'];
		
		$validator = Validator::make($this->request->input(), [
			'domain' => 'required|max:64|unique:websites',
            'IP' => 'required|max:39|unique:websites',
			'name' => 'required|max:20',
        ]);
		
		if (!$validator->fails()) {
			$website = new Website;
			$website->domain = $this->request->input('domain');
			$website->IP = $this->request->input('IP');
			$website->name = $this->request->input('name');			
			$website->save();

			$data['status'] = 'OK';
			$data['msg'] = '新增成功';
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
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
		$data = ['status' => 'NG', 'data' => '', 'msg' => '很抱歉，未找到相关信息'];
        if ($id = $this->request->input('id')) {
			$website = Website::find($id);
			if ($website) {		
				$data['status'] = 'OK';
				$data['data'] = $website;
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
			'domain' => 'required|max:64|unique:websites,domain,' . $this->request->input('id'),
            'IP' => 'required|max:39|unique:websites,IP,' . $this->request->input('id'),
			'name' => 'required|max:20|alpha_dash',
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
		if (!$validator->fails()) {
			$id = $this->request->input('id');
			if ($website = Website::find($id)) {
				$website->domain = $this->request->input('domain');
				$website->IP = $this->request->input('IP');
				$website->name = $this->request->input('name');	
				$website->save();

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
		$website = Website::find($id);
		if ($website) {
			$website->delete();
			return response()->json(['status' => 'OK', 'data' => '', 'msg' => '']);
		}
		return response()->json(['status' => 'NG', 'data' => '', 'msg' => '删除失败']);
    }
}
