<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proprietario extends Model
{
    use HasFactory;

    protected $fillable =[

        'name',
        'doc_identificacao',
        'data_nasc',
        'telefone',
        'endereco',

    ];

    public function user(){
        return $this->hashMany('App\Models\User');
    }
}
