<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'likes';

    protected $fillable = [
        'favorito'
    ];

    public function anuncio(){

        return $this->belongsToMany(Anuncio::class, 'anuncio_like', 'id_anuncio', 'id_like');
    }
}
