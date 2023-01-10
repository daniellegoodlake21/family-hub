<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = 'family'; 
    
    use HasFactory;
    
    protected $fillable = [
        'family_username',
    ];

    public function users()
    {
        // previously was hasMany(UserFamilyLink::class);
        return $this->belongsToMany(UserFamilyLink::class);
    }   
}
