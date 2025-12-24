<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  public function resources() {
    return $this->hasMany(Resource::class); // si enseignant
}

public function downloads() {
    return $this->hasMany(Download::class);
}

public function views() {
    return $this->hasMany(View::class);
}


    }

