<?php

namespace App\Models;

use App\Casts\PercentualCast;
use App\Casts\ValorCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comissao extends Proposta
{
    use HasFactory, SoftDeletes;

    protected $table = 'propostas';

}
