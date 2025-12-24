<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Module;
use App\Models\TagsResources;
use App\Models\View;
use App\Models\Download;

class Resource extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function tagsResources()
    {
        return $this->hasMany(Tags_Resources::class, 'resource_id');
}

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }
}
