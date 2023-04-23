<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = [
        'tipo_servico',
        'categoria_servico'
    ];

    public function anuncioServico(){

        return $this->hasOne(Anuncio::class, 'id_servico', 'id');
    }
}
