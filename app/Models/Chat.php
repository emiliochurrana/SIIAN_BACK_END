<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillabele = [
        'convo_id',
        'user_id',
        'mensagem',
        'status'
    ];

    public function userChat(): BelongsTo{

        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function threadChat(): BelongsTo{
        return $this->belongsTo(Thread::class, 'convo_id', 'id');
    }

}
