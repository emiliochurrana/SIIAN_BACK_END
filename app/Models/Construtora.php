<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Construtora extends Model
{
    use HasFactory;

    protected $table = 'construtoras';

    protected $fillable = [

            'id_user',
            'num_alvara',
            'num_nuit',
            'doc_alvara',
            'doc_nuit',
            'endereco'
        
    ];

    public function userConstrutora(): BelongsTo{
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function funcionarioConstrutora(): HasMany{
        return $this->hasMany(Funcionario::class, 'id_empresa', 'id');
    }
}
