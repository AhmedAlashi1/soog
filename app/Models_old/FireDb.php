<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class FireDb extends Model
{
    use SyncsWithFirebase;
    protected $table='todos';
    protected $fillable = ['task', 'is_done'];

    protected $visible = ['id', 'task', 'is_done'];

}
