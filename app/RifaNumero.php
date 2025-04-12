<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;


class RifaNumero extends Model
{
    protected $table = 'rifa_numeros';

    protected $primaryKey = 'id';

    protected $fillable = [
        'numero',
        'nome',
        'telefone',
        'comprovante',
        'user_id',
        'vendedor_id'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    //
    public function vendedor()
{
    return $this->belongsTo(\App\User::class, 'vendedor_id');
}

}
