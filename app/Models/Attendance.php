<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use App\Traits\TimeStampsGetterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use FilterableTrait;
    use HasFactory;
    use TimeStampsGetterTrait;

    protected $connection = 'mysql';
    protected $table = 'attendance';

    protected $primaryKey = 'id_attendance';

}
