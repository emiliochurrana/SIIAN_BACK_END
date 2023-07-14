<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'id_user',
        'endereco',
        'telefone'
    ];

    public function userCliente(){

        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
