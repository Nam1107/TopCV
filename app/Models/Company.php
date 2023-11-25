<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        "name",
        "email",
        "address",
        "district",
        "city",
        "phone",
        "logo",
        "detail",
        "url_page",
        "owner_id",
        "follow_count",
    ];
    protected $primaryKey = 'id';
    protected $table = 'company';

    public function ownedBy()
    {
        return $this->belongsTo(User::class,'owner_id','id');
    }

    public function following()
    {
        return $this->hasMany(Company_follow_list::class,'company_id','id');
    }

    // public function isFollowing(User $user)
    // {
    //     return !! $this->following()->where('user_id', $user->id)->count();
    // }
}
