<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasApiTokens;
    use HasFactory;
    use Notifiable;


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
    public function castellers(): ?Collection
    {
        return Casteller::where('id_casteller', $this->id_api_user)->get();
    }

    // TODO: This is not correct, fix the relation
    public function getCastellerActive(): ?Casteller
    {
        return Casteller::where('id_casteller', $this->id_api_user)->first();
    }
}
