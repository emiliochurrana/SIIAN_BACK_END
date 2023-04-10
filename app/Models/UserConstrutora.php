<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConstrutora extends Model
{
    use HasFactory;

    protected $table = 'user_construtora';

    protected $fillable = ['id_user', 'id_construtora'];
}
