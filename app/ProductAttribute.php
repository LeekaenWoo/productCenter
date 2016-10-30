<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    public function product() {
		return $this->hasOne('App\Product');
	}
	
	public function attribute() {
		return $this->hasOne('App\Attribute');
	}
}
