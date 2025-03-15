<?php

namespace App;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use FilterableTrait;

    protected $connection = 'mysql';
    protected $table = 'attendance';

    protected $primaryKey = 'id_attendance';
}
