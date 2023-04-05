<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construtora extends Model
{
    use HasFactory;

    protected $fillable = [

        'id_user',
        'nome_construtora',
        'doc_indentificacao',
        'sobre',
        'ano_criacao',
        'endereco',
        'telefone',
        
    ];

    public function user(){
        return $this->hashMany('App\Models\User');
    }
}
