<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Llamado extends Model
{
    protected $fillable = ['mesa_id', 'atendido'];

    protected $casts = [
        'atendido' => 'boolean',
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
}
