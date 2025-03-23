<?php
namespace App\Pivots;
    
use Illuminate\Database\Eloquent\Relations\Pivot;

class CastellerapiUser extends Pivot {
    
    public function user()
    {
        return $this->belongsTo('App\Models\ApiUser');
    }
    
    public function casteller()
    {
        return $this->belongsTo('App\Models\Casteller');
    }
    
    public function audioFiles()
    {
        return $this->hasManyThrough('App\AudioFiles', 'App\Podcast');
    }
   
}