<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    protected $fillable = [
        'id',
        'nome'
    ];
    
    public $timestamps = false;
}
