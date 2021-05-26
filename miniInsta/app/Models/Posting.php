<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posting extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function linkToMannyLike(){
        return $this->hasMany('App\Models\Like','id_posting','id');
    }

    public function linkToMannyComment(){
        return $this->hasMany('App\Models\Comment','id_posting','id');
    }
}
