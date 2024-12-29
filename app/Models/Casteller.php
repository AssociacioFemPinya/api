<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Pivots\ApiUserCasteller as PivotsApiUserCasteller;
use App\Models\CastellerConfig;

#[ApiResource]
class Casteller extends Model
{
    protected $connection = 'mysql';
    protected $table = 'castellers';
    protected $primaryKey = 'id_casteller';

    #[ApiProperty(identifier: true)]
    private int $id_casteller;

    // Relations

    public function apiUsers(): ?BelongsToMany
    {
        return $this->belongsToMany(ApiUser::class, env('DB_DATABASE_API').'.casteller_api_user', 'casteller_id', 'api_user_id');
    }

    public function castellerConfig(): HasOne
    {
        return $this->hasOne(CastellerConfig::class, 'casteller_id', 'id_casteller');
    }

}
