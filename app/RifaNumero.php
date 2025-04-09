<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;


class RifaNumero extends Model
{
    //
    public function vendedor()
{
    return $this->belongsTo(\App\User::class, 'vendedor_id');
}

}
