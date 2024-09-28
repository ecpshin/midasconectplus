<?php

namespace App\Models;

use App\Casts\TotalCast;
use App\Casts\ValorCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposta extends Model
{
    use HasFactory;

    protected $table = 'propostas';

    protected $casts = [
        'data_digitacao' => 'date',
        'data_pagamento' => 'date',
    ];

    public function comissao(): HasOne
    {
        return $this->hasOne(Comissao::class, 'proposta_id', 'id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(Situacao::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function financeira(): BelongsTo
    {
        return $this->belongsTo(Financeira::class);
    }

    public function organizacao(): BelongsTo
    {
        return $this->belongsTo(Organizacao::class);
    }

    public function correspondente(): BelongsTo
    {
        return $this->belongsTo(Correspondente::class);
    }

    public function tabela(): BelongsTo
    {
        return $this->belongsTo(Tabela::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
