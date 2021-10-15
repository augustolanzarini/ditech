<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'id',
        'id_user',
    
    protected $dates = ['data_hora'];
    
    public $timestamps = false;
}
