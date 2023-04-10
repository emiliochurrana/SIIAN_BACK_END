<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCorrector extends Model
{
    use HasFactory;

    protected $table = 'user_corrector';

    protected $fillable = ['id_user', 'id_corrector'];
}
