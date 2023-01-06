<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFamilyLink extends Model
{
    use HasFactory;
    
    protected $table = 'user_family_link'; 
    
    protected $fillable = [
        'family_username',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
