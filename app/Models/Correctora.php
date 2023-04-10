<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correctora extends Model
{
    use HasFactory;

    protected $table = 'correctoras';

    protected $fillable = [

        'tipo_documento',
        'data_nascimento',
        'numero_documento',
        'documento',
        'foto_doc'

    ];

    public function user(){
        return $this->belongsToMany(User::class, 'user_corrector', 'id_user', 'id_corrector');
    }
}
