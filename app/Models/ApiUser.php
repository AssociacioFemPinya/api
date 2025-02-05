<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Picts\ApiUserCasteller;
use App\Pivots\ApiUserCasteller as PivotsApiUserCasteller;
use App\State\EventsStateProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


#[ApiResource(
    shortName: 'ApiUser',
    operations: [
        new Get(
        ),
        new GetCollection(
        ),
    ],
)]
class ApiUser extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;


    protected $connection = 'mysql_api';
    protected $table = 'api_users';
    protected $primaryKey = 'id_api_user';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations

    public function castellers(): ?BelongsToMany
    {
        return $this->belongsToMany(Casteller::class, env('DB_DATABASE_API').'.casteller_api_user', 'api_user_id', 'casteller_id' );
    }
   
    public function getCastellerActive(): ?Casteller
    {
        return $this->castellers->first();
    }        
        
}