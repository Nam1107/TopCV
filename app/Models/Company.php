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
        "manager_id",
        "follow_count"
    ];
    protected $primaryKey = 'id';
    protected $table = 'company';

    public function managedBy()
    {
        return $this->belongsTo(User::class,'manager_id','id');
    }

    public function following()
    {
        return $this->hasMany(Company_follow_list::class)->get();
    }

    public function member(){
        return $this->hasMany(Member::class)->get();
    }

    public function listJob(){
        return $this->hasMany(Job::class)->get();
    }
    
    public function isMember(string $user_id){
        return !!$this->member()->where('member_id', $user_id)->count();
    }

    public function isFollow(){
        $user = auth()->user();
        $user_id = 0;
        if($user){
            $user_id = $user->id;
        }
        return  !!$this->following()->where('user_id', $user_id)->count();
    }

}
