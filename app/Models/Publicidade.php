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
        'titulo',
        'imagem',
        'descricao',
        'endereco',
        'tempo_pago',
        'total_pago'
    ];

    public function userPublicidade(): BelongsTo{
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
