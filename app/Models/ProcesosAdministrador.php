<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesosAdministrador extends Model
{
    use HasFactory;
    protected $table = 'proceso_administrador';
    public $timestamps = false;
}
