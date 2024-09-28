<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Ligacao
{
    use SoftDeletes;

    protected $table = 'ligacoes';
}
