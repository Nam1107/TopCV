<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        "name",
        "email",
        "address",
        "district",
        "province",
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
        return $this->hasMany(Company_follow_list::class)->get();
    }

    public function member(){
        return $this->hasMany(Member::class)->get();
    }
    public function isMember(string $user_id){
        return $this->member()->where('member_id', $user_id)->count();
    }

    public function isFollowing(string $user_id)
    {
        return !! $this->following()->where('user_id', $user_id)->count();
    }
}
