<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacaoChat extends Model
{
    use HasFactory;

    protected $table = 'notificacao_chat';

    protected $fillable = ['id_notificacao', 'id_chat'];
}
