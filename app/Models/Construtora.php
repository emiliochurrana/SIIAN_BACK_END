<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construtora extends Model
{
    use HasFactory;

    protected $table = 'construtoras';

    protected $fillable = [

            'num_alvara',
            'num_nuit',
            'doc_alvara',
            'doc_nuit',
            'endereco'
        
    ];

    public function user(){
        return $this->belongsToMany(User::class, 'user_construtora', 'id_user', 'id_construtora');
    }
}
