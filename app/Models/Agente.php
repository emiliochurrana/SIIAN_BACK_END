<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agente extends Model
{
    use HasFactory;

    protected $table = 'agentes';
    protected $fillable = [

            'num_alvara',
            'num_nuit',
            'doc_alvara',
            'doc_nuit',
            'endereco'

    ];

    public function user(){

        return $this->belongsToMany(User::class, 'user_agente', 'id_user', 'id_agente');
    }
}
