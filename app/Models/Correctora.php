<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Correctora extends Model
{
    use HasFactory;

    protected $table = 'correctoras';

    protected $fillable = [

        'id_user',
        'tipo_documento',
        'data_nascimento',
        'numero_documento',
        'documento',
        'foto_doc'

    ];

    public function userCorrectora(): BelongsTo{
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function funcionarioCorrectora():HasMany{
        return $this->hasMany(Funcionario::class, 'id_empresa', 'id');
    }
}
