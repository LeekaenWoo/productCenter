<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
		
		return view('user.index', ['users' => $users]);
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
		$data = ['status' => 'NG', 'data' => '', 'msg' => '操作失败'];
		
		$validator = Validator::make($this->request->input(), [
			'name' => 'required|max:20',
            'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
		if (!$validator->fails()) {
			$user = new User;
			$user->department = $this->request->input('department');
			$user->title = $this->request->input('title');
			$user->name = $this->request->input('name');
			$user->email = $this->request->input('email');
			$user->password = $this->request->input('password');					
			$user->save();

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
			$user = User::find($id);
			if ($user) {
				unset($user->type);
				unset($user->password);
				unset($user->remember_token);
				unset($user->created_at);
				unset($user->updated_at);
				
				$data['status'] = 'OK';
				$data['data'] = $user;
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
		//ID必须，$this->request->input('id')
		$validator = Validator::make($this->request->input(), [
			'id' => 'required|integer',
			'name' => 'required|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . $this->request->input('id')
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
		if (!$validator->fails()) {
			$id = $this->request->input('id');
			if ($user = User::find($id)) {
				$user->department = $this->request->input('department');
				$user->title = $this->request->input('title');
				$user->name = $this->request->input('name');
				$user->email = $this->request->input('email');			
				$user->save();

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
	
	public function resetPasswordByAdmin() {
		$validator = Validator::make($this->request->input(), [
            'password' => 'required|confirmed|min:6',
			'id' => 'required|integer'
        ]);
		
		$data = ['status' => 'NG', 'data' => '', 'msg' => '修改失败'];
	
		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $error) {
				$data['msg'] .= '<br />' . $error;
			}
		} else {
			$user = User::find($this->request->input('id'));
			if ($user) {
				$user->password = bcrypt($this->request->input('password'));
				$user->save();
				$data['status'] = 'OK';
				$data['msg'] = '修改成功';
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
        $user = User::find($id);
		if ($user && $user->type != 'ADMIN') {
			$user->delete();
			return response()->json(['status' => 'OK', 'data' => '', 'msg' => '']);
		}
		return response()->json(['status' => 'NG', 'data' => '', 'msg' => '删除失败']);
    }
}
