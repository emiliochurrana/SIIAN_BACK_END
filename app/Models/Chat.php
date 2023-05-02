<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillabel = [
        'id_user',
        'mensagem',
        'nome_user'
    ];

    public function userChat(): BelongsTo{

        return $this->belongsTo(User::class, 'id_user', 'id');
    }

}
