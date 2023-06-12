<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publicidade extends Model
{
    use HasFactory;

    protected $table = 'publicidades';

    protected $fillabel = [
        'id_user',
        'tipo_publicidade',
        'espaco',
        'imovel_servico',
        'empreendimento',
        'descricao',
        'telefone',
        'link',
        'tipo_promocao',
        'promocao',
        'paragem',
        'tempo',
        'informacao_legal',
        'imagem',
        'imagem_predefinida',
        'instituicao',
        'validade',
        'limite_finaciamento',
        'taxa_juro',
        'primeira_prestacao',
        'logotipo'
    ];

    public function userPublicidade(): BelongsTo{
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function likePublicidade(){
        return $this->belongsToMany(User::class, 'publicidade_like', 'id_publicidade', 'id_user');
    }
}
