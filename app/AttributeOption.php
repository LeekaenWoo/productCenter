<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
    public function attribute() {
		return $this->hasOne('App\Attribute');
	}
}
