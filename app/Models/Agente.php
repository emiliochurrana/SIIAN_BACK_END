<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agente extends Model
{
    use HasFactory;

    protected $table = 'agentes';
    protected $fillable = [

            'id_user',
            'num_alvara',
            'num_nuit',
            'doc_alvara',
            'doc_nuit',
            'endereco'

    ];

    public function userAgente(): BelongsTo{

        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
