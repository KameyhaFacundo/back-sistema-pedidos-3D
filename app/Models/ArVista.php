<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArVista extends Model
{
    public $timestamps = false;

    protected $fillable = ['empresa_id', 'plato_id'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }
}
