<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnuncioLike extends Model
{
    use HasFactory;

    protected $table = 'anuncio_like';

    protected $fillable = ['id_anuncio', 'id_user'];
}
