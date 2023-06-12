<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;
    protected $table = 'funcionarios';

    protected $fillabel = [
        'id_user',
        'id_empresa',
        'name',
        'doc_indentificacao',
        'data_nascimento',
        'telefone',
        'endereco',
        'curriculum'
    ];

    public function userFuncionario(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function empresaFuncionario(){
        return $this->belongsTo(User::class, 'id_empresa', 'id');
    }

}
