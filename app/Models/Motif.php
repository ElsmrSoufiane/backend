<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motif extends Model
{
    protected $fillable = [
        'description',
        'evidence_image',
        'user_id',
        'costumer_id'
    ];

    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}