<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public function attributeGroup() {
		return $this->hasOne('App\AttributeGroup');
	}
	
	//返回属性选项集
	public function attributeOptions() {
		return $this->hasMany('App\AttributeOption');
	}
}
