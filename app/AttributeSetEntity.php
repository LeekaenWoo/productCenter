<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeSetEntity extends Model
{
    public function attributeSet() {
		return $this->hasOne('App\AttributeSet');
	}
	
	public function attribute() {
		return $this->hasOne();
	}
}
