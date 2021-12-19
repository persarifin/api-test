<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wage extends Model
{
    use HasFactory;
    protected $fillable = ['todo_id', 'wage_price', 'user_id','percentage'];

    // protected $appends = ['name'];

    // public function getNameAttribute()
    // {
    //     $name =  $this->user()->first();
    //     return $name->name;
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
