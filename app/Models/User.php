<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>\
     */

    protected $table = 'users';
    protected $fillable = [
        'user_tipo',
        'name',
        'username', 
        'email',
        'password'
    ];

    public function correctoraUser(): HasOne{
        return $this->hasOne(Correctora::class, 'id_user', 'id'); 
    }

    public function agenteUser(): HasOne{

        return $this->hasOne(Agente::class, 'id_user', 'id');
    }

    public function construtoraUser(): HasOne{

        return $this->hasOne(Construtora::class, 'id_user', 'id');
    }

    public function clienteUser(): HasOne{

        return $this->hasOne(Cliente::class, 'id_user', 'id');
    }

    public function anuncioCorrectora(): HasMany{
        return $this->hasMany(Anuncio::class, 'id_empresa', 'id');
    }

    public function anuncioConstrutora(): HasMany{
        return $this->hasMany(Anuncio::class, 'id_empresa', 'id');
    }

    public function anuncioAgente(): HasMany{
        return $this->hasMany(Anuncio::class, 'id_empresa', 'id');
    }

    public function funcionarioUser(): HasOne{
        return $this->hasOne(Funcionario::class, 'id_user', 'id');
    }


    public function funcionarioEmpresa(){
        return $this->hasMany(Funcionario::class, 'id_empresa', 'id');
    }

    public function notificacaoUser(): HasMany{
        return $this->hasMany(Notificacao::class, 'id_user', 'id');
    }

    public function chatUser(): HasMany{
        return $this->hasMany(Chat::class, 'id_user', 'id');
    }

    public function publicidadeUser(): HasMany{
        return $this->hasMany(Publicidade::class, 'id_user', 'id');

    }

    public function anuncioLike(){
        return $this->belongsToMany(Anuncio::class, 'anuncio_like', 'id_anuncio', 'id_user');
    }

    public function publicidadeLike(){
        return $this->belongsToMany(Publicidade::class, 'publicidade_like', 'id_publicidade', 'id_user');
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
