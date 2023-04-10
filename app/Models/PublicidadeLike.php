<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicidadeLike extends Model
{
    use HasFactory;

    protected $table = 'publicidade_like';

    protected $fillable = ['id_publicidade', 'id_like'];
}
