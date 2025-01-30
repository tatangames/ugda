<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesosSolicitante extends Model
{
    use HasFactory;
    protected $table = 'proceso_solicitante';
    public $timestamps = false;
}
