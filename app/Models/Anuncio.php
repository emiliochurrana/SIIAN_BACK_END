<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Anuncio extends Model
{
    use HasFactory;

    protected $table = 'anuncios';

    protected $fillable = [

        'tipo_conta',
        'tipo_servico',
        'tipo_arrenda',
        'tipo_imovel',
        'infraestrutura',
        'endereco',
        'paragem',
        'distancia_paragem',
        'meio_locomocao',
        'num_cadastro',
        'tipo_infraestrutura',
        'num_quarto',
        'area_total',
        'num_andar',
        'reparacoes',
        'varanda',
        'vista',
        'estilo_cozinha',
        'planificacao',
        'nome_infraestrutura',
        'data_construcao',
        'elevador',
        'elevador_carga',
        'rampa',
        'coletor_lixo',
        'seguranca',
        'parqueamento',
        'garagem',
        'imagem',
        'video',
        'titulo_anuncio',
        'descricao',
        'preco_mensal',
        'preco_negociavel',
        'preco_mensal_extenso',
        'taxa_mensal',
        'pre_pagamento',
        '%_cliente',
        '%_agente',
        'telefone',
        'whatsapp',
        'id_empresa',
        'id_servico'

    ];

    public function likeAnuncio(): BelongsToMany{

        return $this->belongsToMany(User::class, 'anuncio_like', 'id_anuncio', 'id_user');

    }
    public function ConstrutoraAnuncio():BelongsTo{
        return $this->belongsTo(User::class, 'id_empresa', 'id');
    }

    public function agenteAnuncio():BelongsTo{
        return $this->belongsTo(User::class, 'id_empresa', 'id');
    }

    public function correctoraAnuncio():BelongsTo{
        return $this->belongsTo(User::class, 'id_empresa', 'id');
    }

    public function servicoAnuncio(): BelongsTo{
        return $this->belongsTo(Servico::class, 'id_servico', 'id');
    }
   
}
