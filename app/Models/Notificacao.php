<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notificacao extends Model
{
    use HasFactory;
    protected $table = 'notificacoes';

    protected $fillabel = [
        'notificacao',
        'cont_notificacao'
    ];


    public function userNotificacao(): BelongsTo{

        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
