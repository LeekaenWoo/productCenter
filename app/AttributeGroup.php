<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
	//返回组内所有属性集
	public function attributes() {
		return $this->hasMany('App\Attribute');
	}
}
