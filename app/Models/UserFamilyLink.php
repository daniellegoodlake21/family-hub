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
    public function create()
    {
        $userFamilyLinkInput = Request::all();
        UserFamilyLink::create($userFamilyLinkInput);
        $familyInput = [Request::get('family_username')];
        Family::create($familyInput);
    }
    public function membership()
    {
        return $this->belongsToMany(Family::class);
    }
}
