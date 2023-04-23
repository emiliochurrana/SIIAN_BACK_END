<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function correctoraUser(): BelongsTo{
        return $this->belongsTo(Correctora::class, 'id_user', 'id'); 
    }

    public function agenteUser(): BelongsTo{

        return $this->belongsTo(Agente::class, 'id_user', 'id');
    }

    public function construtoraUser(): BelongsTo{

        return $this->belongsTo(Construtora::class, 'id_user', 'id');
    }

    public function anuncioUser(): HasMany{
        return $this->hasMany(Anuncio::class, 'id_empresa', 'id');
    }

    public function funcionarioUser(): BelongsTo{
        return $this->belongsTo(Funcionario::class, 'id_user', 'id');
    }

    public function correctoraFuncionario(): BelongsTo{
        return $this->belongsTo(Correctora::class, 'id_empresa', 'id');
    }

    public function construtoraFuncionario(): BelongsTo{
        return $this->belongsTo(Construtora::class, 'id_empresa', 'id');
    }
    public function agenciaFuncionario(): BelongsTo{
        return $this->belongsTo(Agente::class, 'id_empresa', 'id');
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
