<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    public function websiteProducts() {
		return $this->hasMany('App\WebsiteProduct');
	}
}
