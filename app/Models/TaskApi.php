<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskApi extends Model
{
    use HasFactory;
    public $table='task';
    public $timestamps=false;
}
