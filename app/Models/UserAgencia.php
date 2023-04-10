<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAgencia extends Model
{
    use HasFactory;

    protected $table = 'user_agente';

    protected $fillable = ['id_user', 'id_agente'];
}
