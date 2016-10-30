<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//登陆退出
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::group(['middleware' => 'auth'], function () {
	Route::any('', 'IndexController@index');
	
	//admin管理员特有操作
	Route::group(['middleware' => 'auth.admin'], function () {
		//职员管理
		Route::any('staff', 'UserController@index');
		Route::post('staff/add', 'UserController@store');
		Route::post('staff/resetPassword', 'UserController@resetPasswordByAdmin');
		Route::get('staff/edit', 'UserController@edit');
		Route::post('staff/update', 'UserController@update');
		Route::get('staff/delete/{id}', 'UserController@destroy')->where('id', '[0-9]+');
		
		//网站管理
		Route::get('website', 'WebsiteController@index');
		Route::post('website/add', 'WebsiteController@store');
		Route::get('website/edit', 'WebsiteController@edit');
		Route::post('website/update', 'WebsiteController@update');
		Route::get('website/delete/{id}', 'WebsiteController@destroy')->where('id', '[0-9]+');
	});
	
	//common
	//属性组管理
	Route::any('attribute/group', 'AttributeGroupController@index');
	Route::post('attribute/group/add', 'AttributeGroupController@store');
	Route::get('attribute/group/edit', 'AttributeGroupController@edit');
	Route::get('attribute/group/show/{id}', 'AttributeGroupController@show');
	Route::post('attribute/group/update', 'AttributeGroupController@update');
	Route::get('attribute/group/delete/{id}', 'AttributeGroupController@destroy')->where('id', '[0-9]+');
	
	//属性管理
	Route::any('attribute', 'AttributeController@index');
	Route::get('attribute/create', 'AttributeController@create');
	Route::post('attribute/add', 'AttributeController@store');
	Route::get('attribute/edit/{id}', 'AttributeController@edit');
	Route::post('attribute/update', 'AttributeController@update');
	Route::get('attribute/delete/{id}', 'AttributeController@destroy')->where('id', '[0-9]+');
	//删除指定属性选项
	Route::get('attribute/option/delete/{id}', 'AttributeController@destroyOption')->where('id', '[0-9]+');
	
	//属性集管理	attribute
	Route::any('attribute/set', 'AttributeSetController@index');
	Route::get('attribute/set/create', 'AttributeSetController@create');
	Route::post('attribute/set/add', 'AttributeSetController@store');
	Route::get('attribute/set/edit/{id}', 'AttributeSetController@edit');
	Route::post('attribute/set/update', 'AttributeSetController@update');
	Route::get('attribute/set/delete/{id}', 'AttributeSetController@destroy')->where('id', '[0-9]+');
	//清除属性集中单个属性
	Route::get('attribute/set/entity/delete/{entityId}', 'AttributeSetController@destroyEntity')->where('entityId', '[0-9]+');
	//获取产品对应 产品类型信息--
	Route::get('attribute/set/detail/{id}', 'AttributeSetController@showSetDetail')->where('id', '[0-9]+');
	
	//产品库
	Route::any('product', 'ProductController@index');
	Route::get('product/create', 'ProductController@create');
	Route::post('product/add', 'ProductController@store');
	Route::get('product/edit/{id}', 'ProductController@edit');
	Route::post('product/update', 'ProductController@update');
	Route::get('product/delete/{id}', 'ProductController@destroy')->where('id', '[0-9]+');
	
	//网站产品
	Route::any('product/website', 'WebsiteProductController@index');
	Route::get('product/website/create', 'WebsiteProductController@create');
	Route::post('product/website/add', 'WebsiteProductController@store');
	Route::get('product/website/edit/{id}', 'WebsiteProductController@edit');
	Route::post('product/website/update', 'WebsiteProductController@update');
	Route::get('product/website/delete/{id}', 'WebsiteProductController@destroy')->where('id', '[0-9]+');

});
