<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteProduct extends Model
{
	//所属网站
    public function website() {
		return $this->belongsTo('App\Website');
	}
	
	//所属产品库产品
	public function product() {
		return $this->hasOne('App\Product');
	}
}
