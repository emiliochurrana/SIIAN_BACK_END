<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username', 
        'email',
        'password',
    ];

    public function correctora(){
        return $this->belongsToMany(Correctora::class, 'user_corrector', 'id_user', 'id_corrector'); 
    }

    public function agente(){

        return $this->belongsToMany(Agente::class, 'user_agente', 'id_user', 'id_agente');
    }

    public function construtora(){

        return $this->belongsToMany(Construtora::class, 'user_construtora', 'id_user', 'id_construtora');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
