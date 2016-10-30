<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeSet extends Model
{
    public function attributeSetEntities() {
		return $this->hasMany('App\AttributeSetEntity');
	}
	
	//取出属性集对应的所有产品
	public function products() {
		return $this->hasMany('App\Product');
	}
}
